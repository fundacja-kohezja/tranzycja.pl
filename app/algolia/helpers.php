<?php

use Illuminate\Support\Collection;

function build_title_path($title, $level, Collection $prevItems)
{
    while ($level > 1) {
        $parent = $prevItems->last(fn($item) => isset($item->level) && $item->level < $level);
        if (!$parent) break;

        $level = $parent->level;
        $title = "$parent->title-->$title";
    }
    return $title;
}
