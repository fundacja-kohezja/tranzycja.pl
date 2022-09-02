<?php

namespace App;

use Illuminate\Container\Container;
use TightenCo\Jigsaw\{
    Handlers\MarkdownHandler,
    PageData
};

class CustomMdHandler extends MarkdownHandler
{
    
    public function handleCollectionItem($file, PageData $pageData)
    {
        $this->putPageDataInContainer($pageData);

        return parent::handleCollectionItem($file, $pageData);
    }    

    public function handle($file, $pageData)
    {

        $processed_headings = [];

        $new_content = preg_replace_callback(
            '|<h([^>]+)>(.*)</h([^>]+)>|iU',
            function (&$matches) use (&$processed_headings) {
                $slug = Str::slug(html_entity_decode($matches[2]));
                if (in_array($matches[1][0], ['1', '2', '3'])) {
                    $processed_headings[] = [
                        'level' => $matches[1][0],
                        'text' => $matches[2],
                        'slug' => $slug,
                    ];
                }
                return "<h$matches[1] id=\"$slug\">$matches[2]</h$matches[3]>";
            },
            $contents
        );

        $new_content = preg_replace_callback('|<details>.*?<summary>(.*?)<\/summary>(.*?)<\/details>|xs', function (&$matches) {
                $slug = Str::slug($matches[1]);
                return "<details id=\"$slug\">\n<summary>$matches[1]</summary>\n\n$matches[2]</details>";
            },
            $new_content
        );

        $toc = '<aside class="toc-container"><details id="toc" class="bg-gray-100 dark:bg-gray-800 rounded-lg px-4 py-1 lg:py-0 toc lg:my-4"><summary>' . $label . '</summary><nav><ul class="' . ($pagesBefore || $pagesAfter ? '' : 'list-none ') . 'pl-0 mt-0">';

        if ($pagesBefore || $pagesAfter) {

            foreach ($pagesBefore as $slug => $page) {
                $toc .= '<li><a class="block leading-tight font-bold border-b-0 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" href="' . $slug . '">' . $page . '</a></li>';
            }

            $toc .= '<li><ul class="list-none pl-0 mt-0">';
            foreach ($processed_headings as $h) {
                if ($h['level'] == 1){
                    $toc .= '<li><a onclick="if(window.matchMedia(\'(max-width: 1023px)\').matches)this.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.open = false" class="block leading-tight font-extrabold border-b-0 text-pink-700 dark:text-purple-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" href="#' . $h['slug'] . '">' . $h['text'] . '</a></li>';
                } else {
                    $toc .= '<li' . ($h['level'] > 2 ? ' class="foldable" ' : '') . '><a onclick="if(window.matchMedia(\'(max-width: 1023px)\').matches)this.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.open = false" class="block leading-tight font-light sm:text-xs border-b-0 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" style="padding-left: ' . ($h['level'] - 1) . '.5rem" href="#' . $h['slug'] . '">' . $h['text'] . '</a></li>';
                }
            }
            $toc .= '</ul></li>';

            foreach ($pagesAfter as $slug => $page) {
                $toc .= '<li><a class="block leading-tight font-bold border-b-0 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" href="' . $slug . '">' . $page . '</a></li>';
            }

        } else {

            foreach ($processed_headings as $h) {
                if ($h['level'] == 1){
                    $toc .= '<li><a onclick="if(window.matchMedia(\'(max-width: 1023px)\').matches)this.parentElement.parentElement.parentElement.parentElement.open = false" class="block leading-tight font-bold border-b-0 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" href="#' . $h['slug'] . '">' . $h['text'] . '</a></li>';
                } else {
                    $toc .= '<li' . ($h['level'] > 2 ? ' class="foldable" ' : '') . '><a onclick="if(window.matchMedia(\'(max-width: 1023px)\').matches)this.parentElement.parentElement.parentElement.parentElement.open = false" class="block leading-tight font-light sm:text-xs border-b-0 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" style="padding-left: ' . ($h['level'] - 1) . 'rem" href="#' . $h['slug'] . '">' . $h['text'] . '</a></li>';

                }
            }
    
        }

        $toc .= '</ul></nav></details></aside>';

        return $beforeTitle
            ? preg_replace('/<article([^>]*)>/iuU', "<article$1>\n$toc", $new_content, 1)
            : preg_replace('/<\/h1>/iu', "</h1>\n$toc", $new_content, 1);

    }

    protected function putPageDataInContainer($pageData)
    {
        Container::getInstance()->bind('page', fn() => $pageData->page);
    }
}
