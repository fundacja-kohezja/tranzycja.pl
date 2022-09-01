<?php

use Illuminate\Support\Str;

return [
    
    /**
     * Get the title from the first h1 (or first paragraph if no h1)
     * so authors don't need to specify it in frontmatter
     * 
     */
    'title' => function($page) {

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
    },


    /**
     * Get the short excerpt of the page content as plain text, stripped
     * of headings and all html (except for <br> tags, which replace
     * beginnings of the new paragraph).
     * 
     */
    'excerpt' => function($page, int $words) {

        $content = $page->getContent();
        
        /* remove footnote refererences as there are no footnotes in excerpt */
        $content = preg_replace('|<sup[^>]*>(.*)</sup>|siU', '', $content);

        /* regex for grabbing only paragraphs and lists */
        $regex = '/<(p|ul|ol)[^>]*>(.*)<\/(p|ul|ol)>/siU';
        
        $found = preg_match($regex, $content, $match);
        if ($found) {
            if (mb_strlen(strip_tags($match[2])) > $words * 2.5) {
                /* 
                * We want the excerpt to end nicely at the end of a sentence
                * so we get only the fist paragraph...
                */
                $content = $match[2];
            } else {
                /*
                * ...unless the first paragraph is very short.
                * In that case we get the text up to the <!--more--> tag if present,
                * otherwise we get the whole text so it can be later limited
                * to specified amount of words.
                */
                preg_match_all($regex, explode('<!--more-->', $content)[0], $matches);
                $content = implode('<br>', $matches[2]);
            }
        }

        return Str::of($content)
            ->replace('</li><li>', ', ')
            ->stripTags('<br>')
            ->replace('<br>', '<br><span class="spacer"></span>')
            ->words($words)
            . ' <b class="inline-block">Czytaj dalej →</b>';
    },


    /**
     * Get the beginning of the page content with everything preserved.
     * All html tags left open at the end will be automatically closed.
     * 
     */
    'beginning' => function($page, int $length) {

        $content = $page->getContent();
        
        /* remove footnotes and their references */
        $content = preg_replace('|<sup[^>]*>(.*)</sup>|siU', '', $content);
        $content = preg_replace('|<section class="footnotes">(.*)</section>|siU', '', $content);

        if (strlen($content) <= $length) {
            return $content;
        }

        if (Str::contains($content, '<!--more-->')) {
            /**
             * finish at the <!--more--> tag if present
             */
            $content = explode('<!--more-->', $content)[0];
        } else {
            /**
             * otherwise finish gracefully at the end of the line
             * to avoid chopping part of a word or sentence
             */
            preg_match('/^.{0,' . $length. '}([^\r\n]*)/su', $content, $matches);
            $content = $matches[0];
        }

        /* close any html tags that are left open */
        $dom = new DOMDocument;
        $dom->loadHTML(

            /**
             * there needs to be some element wrapping the whole content
             * for DOMDocument to parse it correctly, hence the <div> wrapper
             * 
             * mb_convert_encoding is needed because DOMDocument doesn't support UTF-8
             */
            '<div>' . mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8') . '</div>',

            /* prevents wrapping in <!DOCTYPE><html><body> */
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        $content = $dom->saveHTML();

        return Str::of($content)

            /* unwrap the dummy div */
            ->replaceFirst('<div>', '')
            ->replaceLast('</div>', '')

            /**
             * add ellipsis inside the last element
             * to indicate there is more content
             */
            ->replaceLast('</', '...</')
            ->replaceLast('....</', '...</');
    }

];
