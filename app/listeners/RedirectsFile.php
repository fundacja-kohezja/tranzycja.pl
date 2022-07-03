<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class RedirectsFile
{
    public function handle(Jigsaw $jigsaw)
    {
        $content = $jigsaw->getFilesystem()->get($jigsaw->getDestinationPath().'/przekierowania');

        $jigsaw->getFilesystem()->putWithDirectories($jigsaw->getDestinationPath().'/_redirects', $content);
    }
}