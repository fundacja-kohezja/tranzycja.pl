<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class GenerateSearchCaches
{
    public function handle(Jigsaw $jigsaw)
    {
        $collected_data = [];
        $all_used_tags = [];

        foreach ($jigsaw->getCollections() as $collection) {

            foreach ($collection as $page) {

                $tags = $page->getTags();

                if (!$tags) continue;

                $all_used_tags = array_merge($tags, $all_used_tags);

                $title = $page->title();
                $lead = $page->excerpt(60, true);
                $redirect = ltrim($page->getPath(), '/');

                $collected_data[] = compact('tags', 'title', 'lead', 'redirect');
            }
        }
        $jigsaw->writeOutputFile(
            'assets/search-caches/articles_with_tags.json',
            json_encode($collected_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $jigsaw->writeOutputFile(
            'assets/search-caches/tags.json',
            json_encode(array_count_values($all_used_tags), JSON_UNESCAPED_UNICODE)
        );
    }
}
