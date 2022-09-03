<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/helpers.php';


use Symfony\Component\DomCrawler\Crawler;
use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\Exceptions\BadRequestException;

$client = SearchClient::create('C8U4P0CC81', getenv('ADMIN_API_KEY'));
$articles_index = $client->initIndex('articles');
$tags_index = $client->initIndex('tags');

$blacklist_pathes = [
    'strony', 'aktualnosci',
];

array_shift($argv);
$use_files_from_args = count($argv) > 0;

if (!$use_files_from_args) {
    $articles_index->clearObjects();
    $tags_index->clearObjects();
}

$first_glob = glob(__DIR__ . '/../../build_local/*/**/index.html');
$second_glob = glob(__DIR__ . '/../../build_local/index.html');
$files_to_index = !$use_files_from_args ? array_merge($first_glob, $second_glob) : array_map(function ($md_path) {
    return md_to_html_path($md_path);
}, $argv);
$all_used_tags = [];

$exclude_fn = function ($el) {
    return basic_exclude_fn($el);
};

$extended_exclude_fn = function ($el, $type) {
    return extended_exclude_fn($el, $type);
};

foreach ($files_to_index as $filename) {
    $splitted_path = explode('/', $filename);
    $blacklisted = false;
    foreach ($splitted_path as $path_part) {
        if (in_array($path_part, $blacklist_pathes) || is_numeric($path_part)) {
            $blacklisted = true;
        }
    }

    if (!$blacklisted) {
        $raw_html = file_get_contents($filename);
        if ($raw_html !== false) {
            $crawler = new Crawler($raw_html);
            $body = $crawler->filter('body')->getNode(0);

            $comments = get_comments_from_document($body);
            $tags = extract_attr_from_comment($comments['tags'], 'TAGS');
            $lang = extract_attr_from_comment($comments['lang'], 'LANG');
            if ($use_files_from_args) {
                $redirect_url = file_path_to_url($filename);
                $articles_index->deleteBy([
                    'filters' => "redirect:'$redirect_url'"
                ]);
            }

            if (count($lang) > 0) {
                continue;
            }
            $all_used_tags = array_merge($tags, $all_used_tags);

            $header = $crawler->filter('h1[id]')->getNode(0);
            $last_parent_id = $header->attributes['id']->textContent;
            $collected_data = array();
            $collected_data[$last_parent_id] = create_agolia_article_object(
                $header->textContent,
                $last_parent_id,
                $filename,
                $tags
            );
            $parsed_lines = [];

            read_dom_depth(
                $header,
                function (
                    $el,
                    $is_child
                ) use (
                    $last_parent_id,
                    &$collected_data,
                    $filename,
                    $tags,
                    &$parsed_lines,
                    $extended_exclude_fn
                ) {
                    $attributes = $el->attributes;
                    $id =  $attributes && $attributes->getNamedItem('id') ?
                        $attributes->getNamedItem('id')->textContent : null;
                    $last_parent_with_id = find_last_parent_with_id($el, $extended_exclude_fn);
                    $last_parent_id = $last_parent_with_id->attributes['id']->textContent ?? $last_parent_id;

                    $unique_id = $el->getLineNo() . md5($el->textContent);
                    $parsed = in_array($unique_id, $parsed_lines);
                    if ($parsed || any_parent_have_element_name($el, 'summary')) {
                        return true;
                    }

                    if (!array_key_exists($last_parent_id, $collected_data)) {
                        $collected_data[$last_parent_id] = create_agolia_article_object(
                            build_title_path($el, $extended_exclude_fn),
                            $last_parent_id,
                            $filename,
                            $tags
                        );
                    }

                    if (any_parent_have_element_name($el, 'details')) {
                        $tag = $el->tagName ?? null;
                        if (strpos($tag, 'summary') !== false) {
                            $parsed_lines[] = $unique_id;
                            return true;
                        }
                    }

                    if ($id !== null) {
                        $last_collected_data = $collected_data[$last_parent_id] ?? [];
                        if ($last_collected_data && $last_collected_data['content']) {
                            $last_collected_data['content'] = preg_replace(
                                '/\s+/',
                                ' ',
                                $last_collected_data['content']
                            );
                        }
                    }
                    $parent_node = $el->parentNode ?? null;
                    $parent_node_attributes = $parent_node->attributes ?? null;
                    $parent_node_id = $parent_node_attributes && $parent_node_attributes->getNamedItem('id')  ?
                        $parent_node_attributes->getNamedItem('id')->textContent : null;

                    if ($id === null && $is_child && strlen(trim($el->textContent)) > 0 && $parent_node_id === null) {
                        $collected_data[$last_parent_id]['content'] .= $el->textContent;
                        $parsed_lines[] = $unique_id;
                    }
                    return true;
                },
                $exclude_fn
            );

            $non_empty_data = array_filter(array_values($collected_data), function ($obj) {
                return strlen($obj['content']) !== 0;
            });

            try {
                $articles_index->saveObjects($non_empty_data, [
                    'autoGenerateObjectIDIfNotExist' => true
                ]);
            } catch (BadRequestException $e) {
                var_dump($collected_data);
            }
        }
    }
}

$tags_objects = array();
foreach (array_unique($all_used_tags) as $tag) {
    $tags_objects[] = array(
        'name' => $tag
    );
}

$tags_index->saveObjects($tags_objects, [
    'objectIDKey' => 'name'
]);
