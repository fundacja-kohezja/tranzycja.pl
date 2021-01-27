<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Date;
use TightenCo\Jigsaw\File\Filesystem;

$files = array_merge(
    (new Filesystem)->files(__DIR__ . '/source/_aktualnosci'),
    (new Filesystem)->files(__DIR__ . '/source/_poradniki')
);

foreach ($files as $file) {
    $parser = new Mni\FrontYAML\Parser();

    $document = $parser->parse((new Filesystem)->get($file), false);

    $yaml = $document->getYAML();
    if (!isset($yaml['data']) || strlen($yaml['data']) !== 19) {

        $new_content = 
'---
data: \'' . Date::now() . '\'
---
' . $document->getContent();

        (new Filesystem)->putWithDirectories($file, $new_content);
    }
    
}
