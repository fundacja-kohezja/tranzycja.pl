<?php

namespace App;

use App\ContentHelpers\{EmbedVideos, InsertFooter, InsertMeta, InsertTOC, RemoveOrphans, WrapTables};
use App\Traits\InitializesMarkdownIt;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Mni\FrontYAML\Markdown\MarkdownParser;

class CustomMdParser implements MarkdownParser
{
    use InitializesMarkdownIt;

    protected $content, $pageData, $headings;
    
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
        $this->headings = [];

        $this->process(EmbedVideos::class)
             ->renderMarkdown()
             ->process(InsertMeta::class)
             ->process(InsertTOC::class)
             ->process(InsertFooter::class)
             ->process(WrapTables::class)
             ->process(RemoveOrphans::class);

        return $this->content;
    }

    /**
     * Parse markdown and render it as html
     */
    protected function renderMarkdown()
    {
        $parser = $this->initMarkdownIt();

        $this->fixEmails($parser);
        $this->processHeadings($parser);

        $this->content = $parser->render($this->content);
        
        return $this;
    }

    /**
     * Call classes responsible for different stages of content processing
     */
    protected function process($class)
    {
        $this->content = $class::process(
            $this->content,
            $this->pageData,
            $this->headings
        );
        return $this;
    }

    /**
     * Add id attrs to headings and use them to populate $headings array
     * which may be used later to generate TOC
     */
    protected function processHeadings($parser)
    {
        $parser->renderer->rules->heading_open = function($tokens, $idx, $options, $env, $slf) {
            $level = $tokens[$idx]->tag[1];

            $text = collect($tokens[$idx + 1]->children)
                    ->filter(fn($t) => in_array($t->type, ['text', 'code_inline']))
                    ->implode('content');

            $slug = $base_slug = Str::slug($text);
            for ($i = 2; isset($env->anchors[$slug]); $i++) { // prevent duplicate ids
                $slug = "$base_slug-$i";
            }
            $env->anchors[$slug] = true;

            $this->headings[] = compact('level', 'text', 'slug');
            $tokens[$idx]->attrSet('id', $slug);
            
            return $slf->renderToken($tokens, $idx, $options, $env, $slf);
        };
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
     * Get the page data (received from our custom handler) from the container
     */
    protected function fillPageData()
    {
        $c = Container::getInstance();
        $this->pageData = $c['page'];

        /*
         * flush the binding immediately to prevent infinite recursion when
         * getting content of other pages than the current one (e.g. to extract
         * titles for TOC)
         */
        $c->bind('page', fn() => null);
    }

}