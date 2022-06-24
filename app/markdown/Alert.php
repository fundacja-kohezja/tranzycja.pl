<?php

namespace App\Markdown;

use Kaoken\MarkdownIt\Plugins\MarkdownItContainer;
use Kaoken\MarkdownIt\RulesBlock\StateBlock;

class Alert extends MarkdownItContainer
{
    function renderDefault(array &$tokens, int $idx, object $options, ?object $env, $slf): string
    {

        // add a class to the opening tag
        if ($tokens[$idx]->nesting === 1) {
            $tokens[$idx]->attrJoin('class', 'alert');
            $tokens[$idx]->attrJoin('class', 'alert-' . $this->name);
        }

        return $slf->renderToken($tokens, $idx, $options, $env, $slf);
    }

    function container(StateBlock $state, int $startLine, int $endLine, $silent=false): bool
    {
//    var $pos, $nextLine, $marker_count, $markup, $params, $token,
//    $old_parent, $old_line_max,
        $auto_closed = false;
        $start = $state->bMarks[$startLine] + $state->tShift[$startLine];
        $max = $state->eMarks[$startLine];

        // Check out the first character quickly,
        // this should filter out most of non-containers
        //
        if ($this->marker_char !== $state->src[$start]) { return false; }

        // Check out the rest of the marker string
        //
        for ($pos = $start + 1; $pos <= $max; $pos++) {
            if ($this->marker_str[($pos - $start) % $this->marker_len] !== $state->src[$pos]) {
                break;
            }
        }

        $marker_count = floor(($pos - $start) / $this->marker_len);
        if ($marker_count < $this->min_markers) { return false; }
        $pos -= ($pos - $start) % $this->marker_len;

        $markup = substr($state->src, $start, $pos-$start);
        $params = substr($state->src, $pos, $max-$pos);
        if( is_array($this->validate) ) {
            if (!$this->validate[0]->{$this->validate[1]}($params)) { return false; }
        }else if( is_callable($this->validate) ){
            $fn = $this->validate;
            if (!$fn($params,$markup)) { return false; }
        }


        // Since $start is found, we can report success here in validation mode
        //
        if ($silent) { return true; }

        // Search for the end of the block
        //
        $nextLine = $startLine;

        for (;;) {
            $nextLine++;
            if ($nextLine >= $endLine) {
                // unclosed block should be autoclosed by end of document.
                // also block seems to be autoclosed by end of parent
                break;
            }

            $start = $state->bMarks[$nextLine] + $state->tShift[$nextLine];
            $max = $state->eMarks[$nextLine];

            if ($start < $max && $state->sCount[$nextLine] < $state->blkIndent) {
                // non-empty line with negative indent should stop the list:
                // - ```
                //  test
                break;
            }

            if ($this->marker_char !== $state->src[$start]) { continue; }

            if ($state->sCount[$nextLine] - $state->blkIndent >= 4) {
                // closing fence should be indented less than 4 spaces
                continue;
            }

            for ($pos = $start + 1; $pos <= $max; $pos++) {
                if (($this->marker_str[($pos - $start) % $this->marker_len] ?? null) !== ($state->src[$pos] ?? null)) {
                    break;
                }
            }

            // closing code fence must be at least as long as the opening one
            if (floor(($pos - $start) / $this->marker_len) < $marker_count) { continue; }

            // make sure tail has spaces only
            $pos -= ($pos - $start) % $this->marker_len;
            $pos = $state->skipSpaces($pos);

            if ($pos < $max) { continue; }

            // found!
            $auto_closed = true;
            break;
        }

        $old_parent = $state->parentType;
        $old_line_max = $state->lineMax;
        $state->parentType = 'container';

        // this will prevent lazy continuations from ever going past our end marker
        $state->lineMax = $nextLine;

        $token        = $state->push('container_' . $this->name . '_open', 'div', 1);
        $token->markup = $markup;
        $token->block  = true;
        $token->info   = $params;
        $token->map    = [ $startLine, $nextLine ];

        $state->md->block->tokenize($state, $startLine + 1, $nextLine);

        $token        = $state->push('container_' . $this->name . '_close', 'div', -1);
        $token->markup = substr($state->src, $start, $pos-$start);
        $token->block  = true;

        $state->parentType = $old_parent;
        $state->lineMax = $old_line_max;
        $state->line = $nextLine + ($auto_closed ? 1 : 0);

        return true;
    }

}