<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class FixListsSeparation
{
    public function handle(Jigsaw $jigsaw)
    {
        $folders = ['_strony', '_aktualnosci', '_publikacje', '_krok_po_kroku', '_ogolne'];
        $files = [];

        foreach ($folders as $folder) {
            $files = array_merge($files, $jigsaw->getFilesystem()->files($jigsaw->getSourcePath()."/$folder"));
        }

        foreach ($files as $file) {

            $new_content = $jigsaw->getFilesystem()->get($file);

            $new_content = preg_replace('/([^\n])\n(1\.)/uU', '$1' . PHP_EOL . PHP_EOL . '$2', $new_content);
            $new_content = preg_replace('/^([^\n*-].*)\n([*-] )/muU', '$1' . PHP_EOL . PHP_EOL . '$2', $new_content);

            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);

        }
    }
}