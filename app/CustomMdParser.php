<?php

namespace App;

use App\Markdown\{Alert, Attributes, Footnote, Spoiler};
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Illuminate\View\Factory;
use Kaoken\MarkdownIt\MarkdownIt;
use Kaoken\MarkdownIt\Plugins\{MarkdownItMark as Mark, MarkdownItEmoji as Emoji, MarkdownItSup as Superscript};
use Mni\FrontYAML\Markdown\MarkdownParser;

class CustomMdParser implements MarkdownParser
{

    protected $content, $pageData;

    protected static $collectionsForTOC;
    
    protected const TOC_MAX_HEADING_LEVEL = 3;
    
    /**
     * Process our markdown file to turn it into the final html content
     * 
     * @param  string $markdown
     * @return  string
     */
    public function parse($markdown)
    {
        $this->content = $markdown;
        $this->pageData = $this->getPageDataFromContainer();

        $this->embedVideos()
             ->renderMarkdown()
             ->insertTOC()
             ->insertFooter()
             ->wrapTables()
             ->removeOrphans();

        $this->flushPageData();

        return $this->content;
    }

    /**
     * Parse markdown and render it as html
     */
    protected function renderMarkdown()
    {
        $parser = (new MarkdownIt([

            /* preserve raw html (like embedded videos) in markdown when parsing */
            'html' => true,

            /* automatically convert plain urls to links */
            'linkify' => true,

        ]))
            ->plugin(new Alert, 'success')      // :::success
            ->plugin(new Alert, 'info')         // :::info
            ->plugin(new Alert, 'warning')      // :::warning
            ->plugin(new Alert, 'danger')       // :::danger
            ->plugin(new Spoiler, 'spoiler')    // :::spoiler
            ->plugin(new Footnote)              // [^1]
            ->plugin(new Attributes)            // {.class}
            ->plugin(new Mark)                  // ==highlight==
            ->plugin(new Emoji)                 // :emoji:
            ->plugin(new Superscript)           // ^sup^
        ;

        $parser->linkify->set([
            /* autoconvert only full urls (with http) */
            'fuzzyLink'  => false
        ]);

        /* fix {{'@'}} in mailto links */
        $parser->renderer->rules->link_open = function($tokens, $idx, $options, $env, $slf) {
            if ($url = $tokens[$idx]->attrGet('href')) {
                $tokens[$idx]->attrSet('href', str_replace("%7B%7B'@'%7D%7D", '@', $url));
            }
            return $slf->renderToken($tokens, $idx, $options, $env, $slf);
        };

        $this->content = $parser->render($this->content);
        
        return $this;
    }

    protected function embedVideos()
    {
        $this->content = ContentHelpers::embedVideos($this->content);
        return $this;
    }

    /**
     * Insert Table of Contents into content as defined in config file
     */
    protected function insertTOC()
    {
        if ($this->pageData->TOC ?? false) {
            $headings = $this->extractHeadings();

            $label = $this->pageData->TOC->label ?? 'Spis treści';
            $hasAllPages = $this->pageData->TOC->allPages ?? false;

            if ($hasAllPages) {
                $collectionItems = $this->getAllCollectionItems();
                [$pagesBefore, $pagesAfter] = $this->splitAtCurrentPage($collectionItems);
            } else {
                $pagesBefore = [];
                $pagesAfter = [];
            }

            $viewFactory = Container::getInstance()[Factory::class];
            $toc = $viewFactory->make('__source.partials.toc', compact('headings', 'label', 'pagesBefore', 'pagesAfter'));

            $this->content = $hasAllPages
                ? ($toc . $this->content)
                : preg_replace('/<\/h1>/iu', "</h1>\n$toc", $this->content, 1);
        }

        return $this;
    }

    /**
     * Insert article footer before footnote references for some
     * collections as defined in config file unless frontmatter
     * explicitly disables it
     */
    protected function insertFooter()
    {

        return $this;
    }

    /**
     * Wrap tables with a div so they can be scrollable horizontally
     * if too wide to fit
     */
    protected function wrapTables()
    {
        $this->content = preg_replace('/<table>.*<\/table>/suU', '<div class="table_container">$0</div>', $this->content);
        return $this;
    }

    protected function removeOrphans()
    {
        $this->content = ContentHelpers::removeOrphans($this->content);
        return $this;

    }

    /**
     * Add id attribute to headings in the content and return all
     * these headings as an array to put in TOC
     */
    protected function extractHeadings()
    {
        $headings = [];

        $this->content = preg_replace_callback(
            '|<h([^>]+)>(.*)</h([^>]+)>|iU',
            function (&$matches) use (&$headings) {
                if (in_array($matches[1][0], range(1, self::TOC_MAX_HEADING_LEVEL))) {
                    $headings[] = [
                        'level' => $matches[1][0],
                        'text' => $matches[2],
                        'slug' => $slug = Str::slug(html_entity_decode($matches[2]))
                    ];
                    return "<h$matches[1] id=\"$slug\">$matches[2]</h$matches[3]>";
                }
                return $matches[0];
            },
            $this->content
        );

        return $headings;
    }

    /**
     * Get all pages from the collection (slugs and titles)
     * to use in TOC (if this collection is set to display
     * all pages in TOC)
     */
    protected function getAllCollectionItems()
    {
        $collectionName = $this->pageData->collection->name;

        if (isset(static::$collectionsForTOC[$collectionName])){
            /* get from cache if possible */
            return static::$collectionsForTOC[$collectionName];
        } else {
            $collectionItems = [];

            $this->flushPageData(); // necessary to avoid infinite recursion

            foreach($this->pageData->collection as $slug => $item) {
                $collectionItems[$slug] = $item->title();
            }

            /* cache collection so it doesn't get rebuilt with every item */
            static::$collectionsForTOC[$collectionName] = $collectionItems;
            
            return $collectionItems;
        }
    }

    /**
     * Split all pages of the collection for TOC into two chunks:
     * those before the current page and those after
     */
    protected function splitAtCurrentPage(array $collectionItems)
    {
        
        $chunked = collect($collectionItems)
            /* split at the path of the current page */
            ->chunkWhile(fn($value, $key) => $key !== $this->pageData->_meta->filename)
            ->toArray();
        
        if (!isset($chunked[1])) {
            /**
             * if array has not been split (has only 1 chunk), that means the current page
             * is the first one from the collection so we need to move the chunk
             * to the position of the "after" chunk and the "before" chunk must be empty
             */
            array_unshift($chunked, []);
        };
        [$chunk1, $chunk2] = $chunked;

        /* remove the current page from the "after" chunk */
        array_shift($chunk2);
        
        return [$chunk1, $chunk2];
    }

    protected function getPageDataFromContainer()
    {
        return Container::getInstance()['page'];
    }

    protected function flushPageData()
    {
        Container::getInstance()->bind('page', fn() => null);
    }

}