<?php

namespace App\Listeners;
use TightenCo\Jigsaw\Jigsaw;

class DynamicNavItems
{
    public function handle(Jigsaw $jigsaw)
    {
        $manNav = $jigsaw->getConfig('mainNav');
        $collection = $jigsaw->getCollection('wsparcie');
        try {
            $manNav[] = (object)[
                'title' => 'Wesprzyj nas!',
                'path' => '/wsparcie/' . array_keys($collection->all())[0],
                'accented' => true
            ];
        } catch (\Exception $e) {}

        $jigsaw->setConfig('mainNav', $manNav);
    }
}