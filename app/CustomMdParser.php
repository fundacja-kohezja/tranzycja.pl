<?php

namespace App;

use App\ContentHelpers\{EmbedVideos, InsertFooter, InsertTOC, RemoveOrphans};
use App\Markdown\{Alert, Attributes, Footnote, Spoiler};
use Illuminate\Container\Container;
use Kaoken\MarkdownIt\MarkdownIt;
use Kaoken\MarkdownIt\Plugins\{MarkdownItMark as Mark, MarkdownItEmoji as Emoji, MarkdownItSup as Superscript};
use Mni\FrontYAML\Markdown\MarkdownParser;

class CustomMdParser implements MarkdownParser
{

    protected $content, $pageData;
    
    /**
     * Process our markdown file to turn it into the final html content
     * 
     * @param  string $markdown
     * @return  string
     */
    public function parse($markdown)
    {
        $this->content = $markdown;
        $this->fillPageData();

        $this->process(EmbedVideos::class)
             ->renderMarkdown()
             ->process(InsertTOC::class)
             ->process(InsertFooter::class)
             ->wrapTables()
             ->process(RemoveOrphans::class);

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

        $this->fixEmails($parser);

        $this->content = $parser->render($this->content);
        
        return $this;
    }

    /**
     * Call classes responsible for different stages of content processing
     */
    protected function process($class)
    {
        $this->content = $class::process($this->content, $this->pageData);
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

    /**
     * Fix {{'@'}} in mailto links
     */
    protected function fixEmails($parser)
    {
        $parser->renderer->rules->link_open = function($tokens, $idx, $options, $env, $slf) {
            if ($url = $tokens[$idx]->attrGet('href')) {
                $tokens[$idx]->attrSet('href', str_replace("%7B%7B'@'%7D%7D", '@', $url));
            }
            return $slf->renderToken($tokens, $idx, $options, $env, $slf);
        };
    }

    /**
     * Get the page data (given by our custom handler) from the container
     */
    protected function fillPageData()
    {
        $c = Container::getInstance();
        $this->pageData = $c['page'];

        /*
         * we flush the binding immediately to prevent infinite recursion when
         * getting content of other pages than the current one (e.g. to extract
         * titles for TOC)
         */
        $c->bind('page', fn() => null);
    }

}