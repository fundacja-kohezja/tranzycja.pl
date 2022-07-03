<?php

/**
 * Here are functions to put in the config file so they
 * can be available to use in page templates with access
 * to all page data (via automatically passed $page argument).
 * 
 * They all must be Closures, because function or method
 * names passed as callbacks in the config file won't work.
 * 
 */

use Illuminate\Support\Str;
use TightenCo\Jigsaw\Collection\CollectionItem;


/**
 * get the title from the first h1 (or first paragraph if no h1)
 * so authors don't need to specify it in frontmatter
 * 
 */
$title = function($page) {

    /*
     * this prevents error when page has no content
     * (like collection listing or home page)
     */
    if (!is_callable($page->_content)) return;

    $tresc = $page->getContent();

    if (preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches)) {
        return strip_tags(html_entity_decode($matches[1]));
    }

    preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches);

    return Str::of(html_entity_decode($matches[1] ?? $tresc))
        ->stripTags()
        ->limit(30);
};


/**
 * Get the beginning of the page content as an excerpt
 * 
 */
$excerpt = function(CollectionItem $page, int $words) {

    $content = $page->getContent();
    
    /* remove footnote refererences as there are no footnotes in excerpt */
    $content = preg_replace('|<sup[^>]*>(.*)</sup>|siU', '', $content);
    
    $found = preg_match('|<p[^>]*>(.*)</p>|siU', $content, $match);
    if ($found) {
        if (mb_strlen(strip_tags($match[1])) > $words * 2.5) {
            /* 
             * We want the excerpt to end nicely at the end of a sentence
             * so we get only the fist paragraph...
             */
            $content = $match[1];
        } else {
            /*
             * ...unless the first paragraph is very short.
             * In that case we get the whole text (we'll limit amount of words later anyway)
             */
            preg_match_all('|<p[^>]*>(.*)</p>|siU', $content, $matches);
            $content = implode('<br><br>', $matches[1]);
        }
    }

    return Str::of($content)
        ->stripTags('<br>')
        ->replace('<br><br><br><br>', '<br><br>')
        ->words($words)
        . ' <b class="inline-block">Czytaj dalej →</b>';
};
