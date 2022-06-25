<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Date;
use Symfony\Component\Yaml\Yaml;
use TightenCo\Jigsaw\File\Filesystem;
use TightenCo\Jigsaw\Parsers\FrontMatterParser;


/* 
 * This file is meant to be called by GitHub Actions.
 * 
 * Its purpose is to automatically fill missing dates of publication in articles
 * so authors don't need to do it manually each time they write new article.
 * 
 */

$foldersToProcess = ['_aktualnosci', '_publikacje', '_publications'];

$c = new Container;
$parser = $c[FrontMatterParser::class];

$fs = new Filesystem;

$files = [];
foreach ($foldersToProcess as $folder) {
    $files = array_merge($files, $fs->files(__DIR__ . '/source/' . $folder));
}

foreach ($files as $file) {

    $file_contents = $fs->get($file);

    $yaml = $parser->getFrontMatter($file_contents);

    $rest = $parser->getContent($file_contents);

    if (!isset($yaml['date']) || !$yaml['date']) {

        $yaml['date'] = (string)Date::now();

        $new_content = '---' . PHP_EOL . Yaml::dump($yaml) . '---' . PHP_EOL . $rest;
        $fs->putWithDirectories($file, $new_content);
    }
    
}
