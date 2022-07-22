<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;
use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\Exceptions\BadRequestException;

function get_comments_from_document($body) {
    $i = iterator_to_array($body->childNodes);
    end($i);
    $comment = prev($i);
    return $comment->nodeType === 8 ? $comment->nodeValue : "";
}

function read_dom_depth(&$el, $fn) {
    if($el !== NULL) {
        if (count($el->childNodes) > 0) {
            foreach($el->childNodes as $child_node) {
                read_dom_depth($child_node, function($arg) use ($fn) {
                    return $fn($arg, true);
                });
            }
        }
        $next = $fn($el, false);
        if(!$next) {
            return;
        }
        read_dom_depth($el->nextSibling, $fn);
    }
}

function file_path_to_url($filename) {
    $splitted_path = explode('/', $filename);
    $splitted_path = array_slice($splitted_path, 0, -1);
    $splitted_path = array_slice($splitted_path, 1);

    return implode('/', $splitted_path);
}

$client = SearchClient::create("C8U4P0CC81", "ADMIN_API_KEY");
$articles_index = $client->initIndex('articles');
$articles_index->clearObjects();

$blacklist_pathes = [
    'strony', 'aktualnosci'
];
foreach (glob("build_local/*/**/index.html") as $filename) {
    $splitted_path = explode('/', $filename);
    $blacklisted = false;
    foreach($splitted_path as $path_part) {
        if(in_array($path_part, $blacklist_pathes) || is_numeric($path_part)) {
            $blacklisted = true;
        }
    }
    if(!$blacklisted) { 
        $raw_html = file_get_contents($filename);
        if($raw_html !== FALSE) {
            $crawler = new Crawler($raw_html);
            $body = $crawler->filter('body')->getNode(0);

            $tags_str = get_comments_from_document($body);
            $tags = str_replace(', ', ',', $tags_str);
            $tags = str_replace('TAGS: ', '', $tags);
            $tags = array_filter(explode(',', $tags));

            $header = $crawler->filter('h1')->getNode(0);
            $last_parent_id = $header->attributes['id']->textContent;
            $collected_data = array();
            $collected_data[$last_parent_id] = array(
                "path" => $header->textContent,
                "content" => '',
                "section" => $last_parent_id,
                "redirect" => file_path_to_url($filename),
                "tags" => $tags,
                "objectID" => md5($filename . $last_parent_id),
            );

            $path_to_header = [];
            $last_tag_level = 1;
            $parsed_lines = [];
            read_dom_depth($header, function(&$el, $is_child) use (&$last_tag_level, &$path_to_header, &$last_parent_id, &$collected_data, $filename, $tags, &$parsed_lines) {
                $attributes = $el->attributes;
                $tag_name = $el->tagName ?? NULL;
                $class_name = $attributes && $attributes->getNamedItem('class') ? $attributes->getNamedItem('class')->textContent : NULL;
                $href = $attributes['href']->textContent ?? NULL;
                $id =  $attributes && $attributes->getNamedItem('id') ? $attributes->getNamedItem('id')->textContent : NULL;
                $unique_id = $el->getLineNo() . strlen($el->textContent);
                $parsed = in_array($unique_id, $parsed_lines);
                if($parsed || strpos($tag_name, "sup") !== FALSE || strpos($class_name, 'toc') !== FALSE || strpos($href, 'fnref') !== FALSE || strpos($id, 'fn') !== FALSE) {                
                    return true;
                }

                if($id !== NULL) {
                    if($el->tagName[0] === 'h' && strlen($el->tagName) === 2) {
                        $tag_level = intval($el->tagName[1]);
                        if(count($path_to_header) > 1) {
                            if($last_tag_level === $tag_level) {
                                $path_to_header = array_slice($path_to_header, 0, -1);
                            }
                            if($last_tag_level > $tag_level) {
                                $path_to_header = array_slice($path_to_header, 0, -2);
                            }
                        }

                        $path_to_header[] = $el->textContent;
                        $last_tag_level = $tag_level;
                    }

                    $last_collected_data = $collected_data[$last_parent_id] ?? NULL;
                    $last_parent_id = $id;

                    if($last_collected_data && $last_collected_data['content']) {
                        $last_collected_data['content'] = preg_replace('/\s+/', ' ', $last_collected_data['content']);
                    }

                    if(!array_key_exists($last_parent_id, $collected_data)) {
                        $url_path = file_path_to_url($filename);
                        $collected_data[$last_parent_id] = array(
                            "path" => implode('-->', $path_to_header),
                            "content" => '',
                            "section" => $last_parent_id,
                            "redirect" => $url_path,
                            "tags" => $tags,
                            "objectID" => md5($filename . $last_parent_id),
                        );
                    }   
                    
                }
                
                $parent_node = $el->parentNode ?? NULL;
                $parent_node_attributes = $parent_node->attributes ?? NULL;
                $parent_node_id = $parent_node_attributes['id'] ?? NULL;
                if($id === NULL && $is_child && strlen(trim($el->textContent)) > 0 && $parent_node_id === NULL && strpos($parent_node->parentNode->tagName, 'SUP') === FALSE) {
                    $collected_data[$last_parent_id]["content"] .= $el->textContent;
                    $el->parsed = true;
                    $parsed_lines[] = $unique_id;
                }
                return true;
            });

            try {
                $articles_index->saveObjects(array_values($collected_data), [
                    'autoGenerateObjectIDIfNotExist' => true
                ]);
            }
            catch(BadRequestException $e) {
                var_dump($collected_data);
            }

        }
    }
}
?>