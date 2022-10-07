<?php

namespace App\Listeners;

use Exception;
use TightenCo\Jigsaw\Jigsaw;
use Symfony\Component\DomCrawler\Crawler;

class GenerateSearchCaches
{
    public function handle(Jigsaw $jigsaw)
    {
        $collected_data = array();
        $all_used_tags = [];
        foreach ($jigsaw->getOutputPaths() as $output_path) {
            $content = null;
            try {
                $content = $jigsaw->readOutputFile($output_path . '/index.html');
            } catch (Exception $e) {
                continue;
            }
            $crawler = new Crawler($content);

            $body = $crawler->filter('body')->getNode(0);
            $nodes = iterator_to_array($body->childNodes);

            $comment_tags = $nodes[count($nodes) - 4];
            $comment_str = html_entity_decode($comment_tags->nodeValue);
            $tags = str_replace(', ', ',', $comment_str);
            $tags = str_replace('TAGS: ', '', $tags);
            $tags = array_filter(explode(',', $tags));
            if (count($tags) === 0) {
                continue;
            }

            $all_used_tags = array_merge($tags, $all_used_tags);

            $article_title_node = $crawler->filter('h1[id]')->getNode(0);
            $article_lead_node = $crawler->filter('article p')->getNode(0);
            if ($article_title_node === null || $article_lead_node === null) {
                continue;
            }
            $collected_data[] = array(
                'tags' => $tags,
                'title' => $article_title_node->textContent,
                'lead' => $article_lead_node->textContent,
                'redirect' => ltrim($output_path, '/'),
            );
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
