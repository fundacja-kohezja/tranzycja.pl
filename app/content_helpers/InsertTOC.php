<?php

namespace App\ContentHelpers;

use Illuminate\Container\Container;
use Illuminate\View\Factory;

/**
 * Insert Table of Contents into content as defined in config file
 */
class InsertTOC
{
    protected const MAX_HEADING_LEVEL = 3;
    protected const TEMPLATE_PATH = '__source.partials.toc';

    protected static $collectionsForTOC;

    public static function process($content, $data, $headings)
    {
        if ($data->TOC ?? false) {
            $label = $data->TOC->label ?? '';
            $hasAllPages = $data->TOC->allPages ?? false;

            if ($hasAllPages) {
                $collectionItems = self::getAllCollectionItems($data);
                $currentPageSlug = $data->getPath();
                [$pagesBefore, $pagesAfter] = self::splitAtPage($collectionItems, $currentPageSlug);
            } else {
                $pagesBefore = [];
                $pagesAfter = [];
            }

            $viewFactory = Container::getInstance()[Factory::class];
            $maxLevel = self::MAX_HEADING_LEVEL;
            $toc = $viewFactory->make(self::TEMPLATE_PATH, compact('headings', 'label', 'pagesBefore', 'pagesAfter', 'maxLevel'));

            return $hasAllPages
                ? ($toc . $content)
                : preg_replace('/<\/h1>/iu', "</h1>\n$toc", $content, 1);
        }
        return $content;
    }

    /**
     * Get all pages from the collection (slugs and titles)
     * to use in TOC (if this collection is set to display
     * all pages in TOC)
     */
    protected static function getAllCollectionItems($data)
    {
        $collectionName = $data->collection->name;

        if (isset(static::$collectionsForTOC[$collectionName])) {
            /* get from cache if possible */
            return static::$collectionsForTOC[$collectionName];
        } else {
            $collectionItems = [];

            foreach ($data->collection as $item) {
                $collectionItems[$item->getPath()] = $item->title();
            }

            /* cache collection so it doesn't get rebuilt with every item */
            static::$collectionsForTOC[$collectionName] = $collectionItems;

            return $collectionItems;
        }
    }

    /**
     * Split all pages of the collection for TOC into two chunks:
     * those before the current page and those after
     */
    protected static function splitAtPage(array $collectionItems, $currentPageSlug)
    {

        $chunked = collect($collectionItems)
            /* split at the path of the current page */
            ->chunkWhile(fn($value, $key) => $key !== $currentPageSlug)
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

        return [$chunk1, $chunk2];
    }
}
