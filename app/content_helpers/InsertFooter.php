<?php

namespace App\ContentHelpers;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Illuminate\View\Factory;

/**
 * Insert article footer for some collections as defined in config file
 * unless page frontmatter explicitly disables it
 */
class InsertFooter {
    
    public static function process($content, $data)
    {
        if (($data->footerBox ?? false) && ($data->cta ?? true)) {
            $viewFactory = Container::getInstance()[Factory::class];
            $box = $viewFactory->make('__source.partials.box', ['content_file' =>  "_ogolne.$data->footerBox"]);

            // insert before footnote references
            return Str::contains($content, '<hr class="footnotes-sep">')
                ? str_ireplace('<hr class="footnotes-sep">', "<hr>$box<hr class=\"footnotes-sep\">", $content)
                : "$content<hr>$box";
        }
        return $content;
    }
}