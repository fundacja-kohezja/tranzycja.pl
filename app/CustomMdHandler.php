<?php

namespace App;

use Illuminate\Support\Str;
use TightenCo\Jigsaw\{
    File\OutputFile,
    Handlers\MarkdownHandler,
    PageData
};

class CustomMdHandler extends MarkdownHandler {

    protected static $collectionsForTOC;

    public function handleCollectionItem($file, PageData $pageData)
    {
        /* generate output files to have the base for modifications */
        $outputFiles = parent::handleCollectionItem($file, $pageData);

        return $outputFiles->map(function($outputFile) use ($pageData) {
            $collection = $pageData->page->_meta->collection;

            switch ($collection) {

                case 'krok_po_kroku':
                    /**
                     * In this case TOC contains all pages of the collection
                     * so we need to get them, but only once, not every time an item
                     * is processed, hence the static property
                     */
                    if (!isset(static::$collectionsForTOC['krok_po_kroku'])){
                        static::$collectionsForTOC['krok_po_kroku'] = $this->getAllCollectionItems('krok_po_kroku', $pageData);
                    }

                    /**
                     * Split all pages of the collection into two chunks:
                     * those which are before the current page
                     * and those which are after the current page
                     */
                    $chunked = collect(static::$collectionsForTOC['krok_po_kroku'])

                        /* split at the path of the current page */
                        ->chunkWhile(fn($value, $key) => $key !== $pageData->page->_meta->path->first())
                        ->toArray();
                    
                    if (!isset($chunked[1])) {
                        /**
                         * if array has not been split (has only 1 chunk), that means the current page
                         * is the first one from the collection so we need to move the chunk
                         * to the position of the "after" chunk and the "before" chunk must be empty
                         */
                        array_unshift($chunked, []);
                    };
                    [$chunk1, $chunk2] = $chunked;

                    /* remove the current page from the "after" chunk */
                    array_shift($chunk2);

                    $new_content = $this->insertTOC(
                        $outputFile->contents(),
                        'Tranzycja krok po kroku',
                        true,
                        $chunk1,
                        $chunk2
                    );

                    break;

                case 'publikacje':
                    $new_content = $this->insertTOC($outputFile->contents(), 'Spis treści');
                    break;
                    
                case 'publications':
                    $new_content = $this->insertTOC($outputFile->contents(), 'Contents');
                    break;

                default:
                    return $outputFile; // no modifications
            }
            
            /*
             * make new output file with modified content
             */
            return new OutputFile(
                $outputFile->inputFile(),
                $outputFile->path(),
                $outputFile->name(),
                $outputFile->extension(),
                $new_content,
                $outputFile->data()
            );
        });
    }

    protected function insertTOC(
        string $contents,
        string $label,
        bool $beforeTitle = false,
        array $pagesBefore = [],
        array $pagesAfter = []
    )
    {

        $processed_headings = [];

        $new_content = preg_replace_callback(
            '|<h([^>]+)>(.*)</h([^>]+)>|iU',
            function (&$matches) use (&$processed_headings) {
                if (in_array($matches[1][0], ['1', '2', '3'])) {
                    $processed_headings[] = [
                        'level' => $matches[1][0],
                        'text' => $matches[2],
                        'slug' => $slug = Str::slug(html_entity_decode($matches[2]))
                    ];
                    return "<h$matches[1] id=\"$slug\">$matches[2]</h$matches[3]>";
                }
                return $matches[0];
            },
            $contents
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

    protected static function getAllCollectionItems(string $collectionName, PageData $pageData)
    {
        $collection = [];

        foreach($pageData->{$collectionName} as $item) {
            $collection[$item->_meta->path->first()] = $item->title();
        }

        return $collection;
    }
}
