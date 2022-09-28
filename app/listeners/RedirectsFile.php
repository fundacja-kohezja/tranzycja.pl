<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class RedirectsFile
{
    public function handle(Jigsaw $jigsaw)
    {
        $content = $jigsaw->getFilesystem()->get($jigsaw->getDestinationPath().'/przekierowania');

        $itemSlug = array_keys($jigsaw->getCollection('wsparcie')->all())[0];
        $content .= "\n/wsparcie /wsparcie/$itemSlug\n";

        $itemSlugEn = array_keys($jigsaw->getCollection('support')->all())[0];
        $content .= "\n/support /support/$itemSlugEn\n";

        $jigsaw->getFilesystem()->putWithDirectories($jigsaw->getDestinationPath().'/_redirects', $content);
    }
}