<?php

function get_comments_from_document($body)
{
    $nodes = iterator_to_array($body->childNodes);
    $comment_lang = $nodes[count($nodes) - 2];
    $comment_tags = $nodes[count($nodes) - 4];

    return array(
        'lang' => $comment_lang->nodeValue,
        'tags' => $comment_tags->nodeValue
    );
}

function extract_attr_from_comment($str, $field)
{
    $attr = str_replace(', ', ',', $str);
    $attr = str_replace("$field: ", '', $attr);
    return array_filter(explode(',', $attr));
}

function read_dom_depth(&$el, $fn, $exclude_fn)
{
    if ($el !== null) {
        if (count($el->childNodes) > 0) {
            foreach ($el->childNodes as $child_node) {
                if (!$exclude_fn($child_node) && !$exclude_fn($el)) {
                    read_dom_depth($child_node, function ($arg) use ($fn) {
                        return $fn($arg, true);
                    }, $exclude_fn);
                }
            }
        }
        $next = $fn($el, false);
        if (!$next) {
            return;
        }
        read_dom_depth($el->nextSibling, $fn, $exclude_fn);
    }
}

function any_parent_have_element_name($el, $name)
{
    if ($el !== null && $el->parentNode !== null) {
        $tag = $el->parentNode->tagName ?? null;
        return strpos($tag, $name) !== false || any_parent_have_element_name($el->parentNode, $name);
    }
    return false;
}

function find_last_parent_with_id($el, $exclude_fn)
{
    if ($el !== null) {
        $prev_node = $el->previousSibling === null ? $el->parentNode : $el->previousSibling;
        $attributes = $prev_node->attributes ?? null;
        $id =  $attributes && $attributes->getNamedItem('id') ? $attributes->getNamedItem('id')->textContent : null;
        return $id !== null && !$exclude_fn($prev_node, $el->previousSibling === null ? 'parent' : 'prev') ?
            $prev_node : find_last_parent_with_id($prev_node, $exclude_fn);
    }
    return false;
}

function build_title_path(&$el, $exclude_fn, $path = [], $first_el_parent_id = null, $max_level = null)
{
    $tag_name = $el->tagName ?? null;
    $last_parent_with_id = find_last_parent_with_id($el, $exclude_fn);
    $map_key = strlen($tag_name) === 2 ? intval($tag_name[1]) : ''; //header level as key
    $text_content = $el->textContent;
    $first_el_parent_id = $first_el_parent_id ?? $last_parent_with_id->attributes['id']->textContent;

    if ($max_level === null && is_int($map_key)) {
        $max_level = $map_key;
    }

    if (!array_key_exists($map_key, $path) && $tag_name !== null) {
        if (strpos($tag_name, 'details') !== false) {
            $map_key = $el->attributes['id']->textContent;
            foreach ($el->childNodes as $child_node) {
                $child_tag_name = $child_node->tagName ?? null;
                if (strpos($child_tag_name, 'summary') !== false) {
                    $text_content = $child_node->textContent;
                }
            }
        }
        $path[$map_key] = trim($text_content);
    }

    if (strpos($tag_name, 'h1') !== false) {
        $final_path = [];
        foreach (array_merge(range(1, $max_level), [$first_el_parent_id]) as $header_level) {
            if (array_key_exists($header_level, $path)) {
                array_push($final_path, $path[$header_level]);
            }
        }

        return implode('-->', $final_path);
    }
    return build_title_path($last_parent_with_id, $exclude_fn, $path, $first_el_parent_id, $max_level);
}

function basic_exclude_fn($el)
{
    $attributes = $el->attributes;
    $class_name = $attributes && $attributes->getNamedItem('class') ?
        $attributes->getNamedItem('class')->textContent : null;
    $id =  $attributes && $attributes->getNamedItem('id') ? $attributes->getNamedItem('id')->textContent : null;
    $href = $attributes['href']->textContent ?? null;
    $tag_name = $el->tagName ?? null;

    return (
        strpos($class_name, 'toc') !== false ||
        strpos($href, 'fnref') !== false ||
        strpos($id, 'fn') !== false ||
        strpos($tag_name, 'sup') !== false
    );
}

function extended_exclude_fn($el, $type)
{
    return basic_exclude_fn($el) || (strpos($type, 'prev') !== false && strpos($el->tagName, 'details') !== false);
}

function md_to_html_path($filename)
{
    if (strpos($filename, '_ogolne/') !== false) {
        return __DIR__ . '/../../build_local/index.html';
    }
    $html_path = explode("source/", $filename)[1];
    if ($html_path[0] === '_') {
        $html_path = substr($html_path, 1);
    }

    $html_path = str_replace('_', '-', $html_path);
    $html_path = str_replace('.md', '', $html_path);
    $html_path .= '/index.html';

    return __DIR__ . '/../../build_local/' . $html_path;
}

function file_path_to_url($filename)
{
    $url = str_replace('build_local/', '', strstr($filename, 'build_local/'));
    $url = str_replace('index.html', '', $url);
    return strlen($url) > 0 ? $url : '/';
}

function create_agolia_article_object($path, $section, $filename, $tags)
{
    return array(
        'path' => $path,
        'content' => '',
        'section' => $section,
        'redirect' => file_path_to_url($filename),
        'tags' => $tags,
        'objectID' => md5($filename . $section),
    );
}
