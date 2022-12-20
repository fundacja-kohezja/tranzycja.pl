<?php

namespace App;

use App\Traits\InitializesMarkdownIt;
use Illuminate\Support\Str;
use Mni\FrontYAML\Markdown\MarkdownParser;
use stdClass;

class SectionSplitMdParser implements MarkdownParser
{
    use InitializesMarkdownIt;

    protected $content, $parser, $tokens, $sections, $slugs;
    
    /**
     * Process our markdown file to turn it into the collection of content sections split by headings and containers
     * 
     * @param  string $markdown
     * @return  Illuminate\Support\Collection
     */
    public function parse($markdown)
    {
        $this->content = $markdown;
        $this->sections = [];
        $this->slugs = [];

        $this->parseMarkdown()
             ->splitTokens()
             ->renderSections();

        return collect($this->sections);
    }

    /**
     * Parse markdown to get the token list
     */
    protected function parseMarkdown()
    {
        $this->parser = $this->initMarkdownIt();

        $this->tokens = collect($this->parser->parse($this->content, new stdClass));

        return $this;
    }

    /**
     * Make separate chunk for each section by splitting token list before every heading or container opening and closing
     */
    protected function splitTokens()
    {
        $this->tokens = $this->tokens
            ->chunkWhile(fn($t) => !Str::is([
                'heading_open',
                'container_*_open',
                'container_*_close',
                'footnote_block_open'
            ], $t->type));

        return $this;
    }

    /**
     * Render each section separately to turn it from markdown to plain text
     * and add section metadata if applicable
     */
    protected function renderSections()
    {
        foreach ($this->tokens as $sectionTokens) {

            if ($sectionTokens->isEmpty()) continue;

            /* reindexing from 0 is necessary because split chunks have preserved keys */
            $sectionTokens = $sectionTokens->values();

            /* execute proper method based on the type of the first token */
            foreach([
                'heading_open'           => 'afterHeading',
                'container_spoiler_open' => 'insideSpoiler',
                'container_*_open'       => 'insideContainer',
                'container_*_close'      => 'afterContainer',
                'footnote_block_open'    => 'footnoteSection',
                '*'                      => 'plainSection'
            ] as $pattern                => $methodCallback) {

                if (Str::is($pattern, $sectionTokens[0]->type)) {

                    [$this, $methodCallback]($sectionTokens);
                    break;
                }
            }
        }

        return $this;
    }

    /**
     * Handle sections beginning with heading
     */
    protected function afterHeading($tokens)
    {
        $title = collect($tokens[1]->children)
            ->filter(fn($t) => in_array($t->type, ['text', 'code_inline']))
            ->implode('content');

        $slug = uniqueSlug($title, $this->slugs);

        $level = $tokens[0]->tag[1];

        $content = $this->renderPlainText($tokens
            ->skipUntil(fn($t) => $t->type === 'heading_close')
            ->skip(1)
        );

        $this->sections[] = (object)compact('title', 'slug', 'level', 'content');
    }

    /**
     * Handle inside of a spoiler
     */
    protected function insideSpoiler($tokens)
    {
        $section = [
            'level' => 'open',
            'content' => $this->renderPlainText($tokens->skip(1))
        ];

        $text = explode(' ', trim($tokens[0]->info), 2)[1] ?? null;

        if ($text) {
            $renderedText = $this->parser->renderInline($text);
            $title = html_entity_decode(strip_tags($renderedText));
            $slug = uniqueSlug($title, $this->slugs);

            $section += compact('title', 'slug');
        }

        $this->sections[] = (object)$section;
    }

    /**
     * Handle inside of an alert (info, warning etc.)
     * 
     * Alerts usually don't need separation from the rest of the content
     * but sometimes they may have headings inside thus creating new sections
     * and those sections should be containted within the alert
     * so that's why we need alerts separated.
     */
    protected function insideContainer($tokens)
    {
        $this->sections[] = (object)[
            'level' => 'open',
            'content' => $this->renderPlainText($tokens->skip(1))
        ];
    }

    /**
     * Handle content that comes after spoiler or alert, before next heading
     */
    protected function afterContainer($tokens)
    {
        $this->sections[] = (object)[
            'level' => 'close',
            'content' => $this->renderPlainText($tokens->skip(1))
        ];
    }

    /**
     * Handle footnote section
     * 
     * Even though it doesn't have heading as part of the content
     * is should be teated as level 2 heading section.
     * First item always has 'fn1' id – hence the slug.
     */
    protected function footnoteSection($tokens)
    {
        $this->sections[] = (object)[
            'title' => 'Przypisy',
            'slug' => 'fn1',
            'level' => 2,
            'content' => $this->renderPlainText($tokens)
        ];
    }

    /**
     * Failsafe if there is a page that doesn't begin with heading
     * even though it should
     */
    protected function plainSection($tokens)
    {
        $this->sections[] = (object)[
            'level' => 1,
            'content' => $this->renderPlainText($tokens)
        ];
    }

    /**
     * Use markdown parser to render tokens to html
     * and then convert the result to plain text
     */
    protected function renderPlainText($tokens)
    {
        $preparedTokens = $tokens
            ->map(function($t) { // remove footnote references
                if ($t->children) {
                    $t->children = array_values(
                        array_filter($t->children, fn($child) => $child->type !== 'footnote_ref')
                    );
                }
                return $t;
            })
            ->values() // tokens must be indexed from 0 in order for parser to work
            ->toArray();

        return html_entity_decode(strip_tags(
            $this->parser->renderer->render(
                $preparedTokens,
                $this->parser->options,
                new stdClass
            )
        ));
    }

}