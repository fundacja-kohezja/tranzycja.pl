<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class ContentPostProcess
{
    protected $folders = [
        '/strony',
        '/publikacje',
        '/publications',
        '/aktualnosci',
        '/krok-po-kroku',
        '/wsparcie'
    ];

    public function handle(Jigsaw $jigsaw)
    {
        $files = [];
        foreach ($this->folders as $folder) {
            $files = array_merge($files, $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath() . $folder));
        }

        $files[] = $jigsaw->getFilesystem()->getFile($jigsaw->getDestinationPath(), 'index.html');

        $orphans = new Orphans;
        $extraMd = new ExtraMarkdownTags;
    
        foreach ($files as $file) {
    
            $content = $jigsaw->getFilesystem()->get($file);
            
            $content = $orphans->process($content); // remove orphans
            $content = $extraMd->process($content); // apply extra markdown tags
    
            $jigsaw->getFilesystem()->putWithDirectories($file, $content);
        }
    }
}