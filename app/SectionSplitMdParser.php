<?php

namespace App;

use App\Traits\InitializesMarkdownIt;
use Mni\FrontYAML\Markdown\MarkdownParser;
use stdClass;

class SectionSplitMdParser implements MarkdownParser
{
    use InitializesMarkdownIt;

    protected $content, $parser, $tokens, $sections;
    
    /**
     * Process our markdown file to turn it into the collection of content sections split by headings
     * 
     * @param  string $markdown
     * @return  Illuminate\Support\Collection
     */
    public function parse($markdown)
    {
        $this->content = $markdown;

        $this->parseMarkdown()
             ->addAnchors()
             ->splitTokens()
             ->renderSections();

        return $this->sections;
    }

    /**
     * Parse markdown to get the token list
     */
    protected function parseMarkdown()
    {
        $this->parser = $this->initMarkdownIt();

        $this->tokens = $this->parser->parse($this->content, new stdClass);

        return $this;
    }

    /**
     * Enhance the token list with anchors generated for headings and spoilers
     */
    protected function addAnchors()
    {
        
        return $this;
    }

    /**
     * Make separate chunk for each section by splitting token list before every heading
     */
    protected function splitTokens()
    {
        $this->sections = collect($this->tokens)
            ->chunkWhile(fn($t) => $t->type !== 'heading_open');

        return $this;
    }

    /**
     * Render each section separately to turn it from markdown to plain text
     * with titles and levels extracted from headings
     */
    protected function renderSections()
    {
        $rendered = [];

        foreach ($this->sections as $sectionTokens) {

            if ($sectionTokens->isEmpty()) continue;

            /* reindexing from 0 is necessary because split chunks have preserved keys */
            $sectionTokens = $sectionTokens->values();

            if ($sectionTokens[0]->type === 'heading_open') {
                /* page starts with heading – this should always be the case */

                dd($sectionTokens);
                $contentTokens = $sectionTokens
                    ->skip(3) // skip the heading
                    ->values() // reindex from 0 after skipping
                    ->toArray();

                $renderedSection = [
                    'title' => collect($sectionTokens[1]->children)
                                ->filter(fn($t) => in_array($t->type, ['text', 'code_inline']))
                                ->implode('content'),

                    'level' => $sectionTokens[0]->tag[1],

                    'content' => strip_tags(
                        $this->parser->renderer->render(
                            $contentTokens,
                            $this->parser->options,
                            new stdClass
                        )
                    )
                ];

            } else {
                /* page doesn't start with heading even though it should */

                $contentTokens = $sectionTokens->toArray();

                $renderedSection = [
                    'title' => null,
                    'level' => null,
                    'content' => strip_tags(
                        $this->parser->renderer->render(
                            $contentTokens,
                            $this->parser->options,
                            new stdClass
                        )
                    )
                ];

            }
            
            $rendered[] = $renderedSection;
        }

        $this->sections = collect($rendered);

        return $this;
    }

}