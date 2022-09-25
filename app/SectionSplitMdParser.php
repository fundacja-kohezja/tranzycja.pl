<?php

namespace App;

use App\Traits\InitializesMarkdownIt;
use Mni\FrontYAML\Markdown\MarkdownParser;
use stdClass;

class SectionSplitMdParser implements MarkdownParser
{
    use InitializesMarkdownIt;

    protected $content, $parser, $tokens, $sections, $slugs;
    
    /**
     * Process our markdown file to turn it into the collection of content sections split by headings
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
     * Make separate chunk for each section by splitting token list before every heading or spoiler opening and closing
     */
    protected function splitTokens()
    {
        $this->tokens = $this->tokens
            ->chunkWhile(fn($t) => !in_array($t->type, [
                'heading_open',
                'container_spoiler_open',
                'container_spoiler_close',
                'footnote_block_open'
            ]));

        return $this;
    }

    /**
     * Render each section separately to turn it from markdown to plain text
     * with titles, levels and slugs extracted from headings
     */
    protected function renderSections()
    {
        foreach ($this->tokens as $sectionTokens) {

            if ($sectionTokens->isEmpty()) continue;

            /* reindexing from 0 is necessary because split chunks have preserved keys */
            $sectionTokens = $sectionTokens->values();

            switch ($sectionTokens[0]->type) {
                case 'heading_open':
                    $this->headingSection($sectionTokens);
                    break;

                case 'container_spoiler_open':
                    $this->spoilerSection($sectionTokens);
                    break;

                case 'container_spoiler_close':
                    $this->sectionAfterSpoiler($sectionTokens);
                    break;

                case 'footnote_block_open':
                    $this->footnoteSection($sectionTokens);
                    break;

                default:
                    /* failsafe if page doesn't begin with heading even though it should */
                    $this->sections[] = (object)[
                        'content' => $this->renderPlainText($sectionTokens)
                    ];
            }
        }

        return $this;
    }

    protected function headingSection($tokens)
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

    protected function spoilerSection($tokens)
    {
        $section = [
            'content' => $this->renderPlainText($tokens->skip(1))
        ];

        $text = explode(' ', trim($tokens[0]->info), 2)[1] ?? null;

        if ($text) {
            $title = $this->parser->renderInline($text);
            $slug = uniqueSlug(html_entity_decode(strip_tags($title)), $this->slugs);

            $section += compact('title', 'slug');
        }

        $this->sections[] = (object)$section;
    }

    protected function sectionAfterSpoiler($tokens)
    {
        $content = $this->renderPlainText($tokens->skip(1));

        if (trim($content)) {
            $this->sections[] = (object)compact('content');
        }
    }

    protected function footnoteSection($tokens)
    {
        $this->sections[] = (object)[
            'title' => 'Przypisy',
            'slug' => 'fn1',
            'level' => 2,
            'content' => $this->renderPlainText($tokens)
        ];
    }

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