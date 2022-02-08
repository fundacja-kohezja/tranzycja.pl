<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class RestoreSourceFiles
{
    protected $templateNameLength = 21;

    public function handle(Jigsaw $jigsaw)
    {
        $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_strony');
        foreach ($files as $file) {

            $new_content = substr($jigsaw->getFilesystem()->get($file), $this->templateNameLength + 35);
            
            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
        }
        
        $files = array_merge(
            $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_publikacje'),
            $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_publications'),
            $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_aktualnosci')
        );
        foreach ($files as $file) {
            $file_content = $jigsaw->getFilesystem()->get($file);
            $new_content = substr($file_content, 0, 32) . substr($file_content, $this->templateNameLength + 59);
            
            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
        }
        $files = array_merge(
            $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_krok_po_kroku'),
            $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_wsparcie')
        );
        foreach ($files as $file) {
            $file_content = $jigsaw->getFilesystem()->get($file);
            $new_content = substr($file_content, 0, 19) . substr($file_content, $this->templateNameLength + 46);
            
            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
        }
    }
}