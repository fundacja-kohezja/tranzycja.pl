<?php

require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Str;

/**
 * This file is meant to be called by GitHub Actions.
 * 
 * Its purpose is to automatically fix file names to slugs
 * 
 */
foreach(glob(__DIR__ . '/source/**/*.md') as $path) {
    $path_info = pathinfo($path);
    $filename = $path_info['filename'];
    $slug_name = Str::slug($filename);
    if(strcmp($slug_name, $filename) !== 0)  {
        $dirname = $path_info['dirname'];
        $ext = $path_info['extension'];
        rename($path, "$dirname/$slug_name.$ext");
    }
}