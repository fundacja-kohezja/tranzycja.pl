<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class ContentPostProcess
{
    public function handle(Jigsaw $jigsaw)
    {
        $files = array_merge(
            $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath().'/strony'),
            $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath().'/publikacje'),
            $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath().'/aktualnosci'),
            $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath().'/krok-po-kroku')
        );
        $files[] = $jigsaw->getFilesystem()->getFile($jigsaw->getDestinationPath(), 'index.html');

        $orphans = new Orphans;
        $extraMd = new ExtraMarkdownTags;
    
        foreach ($files as $file) {
    
            $content = $jigsaw->getFilesystem()->get($file);
            
            $content = $orphans->process($content); // apply extra markdown tags
            $content = $extraMd->process($content); // remove orphans
    
            $jigsaw->getFilesystem()->putWithDirectories($file, $content);
        }
    }
}