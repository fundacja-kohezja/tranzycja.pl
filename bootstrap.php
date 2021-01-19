<?php

use App\Listeners\GenerateSitemap;
use Illuminate\Support\Str;

/** @var $container \Illuminate\Container\Container */
/** @var $events \TightenCo\Jigsaw\Events\EventBus */

/**
 * You can run custom code at different stages of the build process by
 * listening to the 'beforeBuild', 'afterCollections', and 'afterBuild' events.
 *
 * For example:
 *
 * $events->beforeBuild(function (Jigsaw $jigsaw) {
 *     // Your code here
 * });
 * 
 */

$events->beforeBuild(function($jigsaw){
    $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_strony');
    foreach ($files as $file) {
        $new_content = 
'---
extends: page
section: content
---
' . $jigsaw->getFilesystem()->get($file);
        
        $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
    }
});


$events->afterCollections(function($jigsaw) {
    $strony = $jigsaw->getCollection('strony');
    foreach($strony as $strona) {
        $tresc = $jigsaw->getFilesystem()->get($strona->_meta['source'] . '/' . $strona->_meta['filename'] . '.' . $strona->_meta['extension']);
        preg_match('/(?<=^# ).+/m', $tresc, $matches);
        $strona->title = $matches[0] ?: Str::of($tresc)->substr(39)->limit(30);
    }
});


$events->afterBuild(GenerateSitemap::class);
$events->afterBuild(function($jigsaw){
    $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_strony');
    foreach ($files as $file) {
        $new_content = substr($jigsaw->getFilesystem()->get($file), 39);
        
        $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
    }
});

