<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class TemplateNames
{
    public function handle(Jigsaw $jigsaw)
    {
        $collectionNames = $jigsaw->getCollections();

        foreach ($collectionNames as $name) {
            $jigsaw->setConfig("collections.$name.extends", "__source.single.$name");
        }
    }
}