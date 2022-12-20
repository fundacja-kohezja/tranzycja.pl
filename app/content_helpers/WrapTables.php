<?php

namespace App\ContentHelpers;

/**
 * Wrap tables with a div so they can be scrollable horizontally
 * if too wide to fit
 */
class WrapTables
{
    public static function process($content) {
        
        return preg_replace(
            '/<table>.*<\/table>/suU',
            '<div class="table_container">$0</div>',
            $content
        );
    }
   
}