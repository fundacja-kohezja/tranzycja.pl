<?php

namespace App\Traits;

use App\Markdown\{Alert, Attributes, Footnote, Spoiler};
use Kaoken\MarkdownIt\MarkdownIt;
use Kaoken\MarkdownIt\Plugins\{MarkdownItMark as Mark, MarkdownItEmoji as Emoji, MarkdownItSup as Superscript};

trait InitializesMarkdownIt
{    
    protected function initMarkdownIt()
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

        return $parser;
    }
}