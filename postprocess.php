<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Date;
use Symfony\Component\Yaml\Yaml;
use TightenCo\Jigsaw\File\Filesystem;
use TightenCo\Jigsaw\Parsers\FrontMatterParser;


/**
 * This file is meant to be called by GitHub Actions.
 * 
 * Its purpose is to automatically fill missing dates in articles
 * so authors don't need to do it manually each time.
 * 
 * folders to process are specified in $folders in the following manner:
 * [
 *     folder_name => [field1, field2...],
 *     ...
 * ]
 * each of the specified fields will be filled with current date if missing or empty.
 * 
 */

$folders = [
    '_krok_po_kroku' => ['zaktualizowano'],
    '_aktualnosci' => ['opublikowano'],
    '_publikacje' => ['opublikowano', 'zaktualizowano'],
    '_publications' => ['opublikowano', 'zaktualizowano']
];

$c = new Container;
$parser = $c[FrontMatterParser::class];

$fs = new Filesystem;

foreach ($folders as $folder => $fields) {

    $files = $fs->files(__DIR__ . '/source/' . $folder);

    foreach ($files as $file) {

        $file_contents = $fs->get($file);
        $yaml = $parser->getFrontMatter($file_contents);
        $rest = $parser->getContent($file_contents);
        $changed = false;

        foreach ($fields as $field) {
            
            if (!isset($yaml[$field]) || !$yaml[$field]) {
                $yaml[$field] = (string)Date::now();
                $changed = true;
            }
        }
    
        if ($changed) {
            $new_content = '---' . PHP_EOL . Yaml::dump($yaml) . '---' . PHP_EOL . $rest;
            $fs->putWithDirectories($file, $new_content);
        }
    }
}
