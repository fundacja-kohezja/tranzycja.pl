<?php

namespace App\ContentHelpers;

use Illuminate\Container\Container;
use Illuminate\View\Factory;
use Jenssegers\Date\Date;

class InsertMeta
{
    protected const TEMPLATE_PATH = '__source.partials.meta';

    public static function process($content, $data)
    {
        $meta = $data->meta ?? [];
        if ($data->opublikowano ?? false) {
            $opublikowano = Date::create($data->opublikowano)->format('j M Y');
            $meta += ['Opublikowano' => $opublikowano];
        }
        if ($data->zaktualizowano ?? false) {
            $zaktualizowano = Date::create($data->zaktualizowano)->format('j M Y');
            if (!isset($opublikowano) || $opublikowano !== $zaktualizowano) {
                $meta += ['Ostatnia zmiana' => $zaktualizowano];
            }
        }
        if ($meta) {
            $viewFactory = Container::getInstance()[Factory::class];
            $metaBox = $viewFactory->make(self::TEMPLATE_PATH, compact('meta'));
            return preg_replace('/<\/h1>/iu', "</h1>\n$metaBox", $content, 1);
        }
        return $content;
    }
}