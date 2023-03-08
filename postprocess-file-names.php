<?php

require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Str;

/**
 * This file is meant to be called by GitHub Actions.
 * 
 * Its purpose is to automatically fix file names to slugs
 * 
 */
$source_directory = new RecursiveDirectoryIterator(__DIR__ . '/source');
$iterator = new RecursiveIteratorIterator($source_directory);
$regex = new RegexIterator($iterator, '/^.+\.md$/i');

foreach($regex as $file_info) {
    $path = $file_info->getPathname();
    $path_info = pathinfo($path);
    $filename = $path_info['filename'];
    $slug_name = Str::slug($filename);

    if(strcmp($slug_name, $filename) !== 0)  {
        $dirname = $path_info['dirname'];
        $ext = $path_info['extension'];
        rename($path, "$dirname/$slug_name.$ext");
    }
}