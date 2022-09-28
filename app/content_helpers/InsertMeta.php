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
        $showDetails = $data->showDetailsInMetabox ?? false;
        $meta = $data->meta ?? [];
        $i18nMaps = [
            'Autorzy' => 'metaBox.authors',
            'Opublikowano' => 'metaBox.published',
            'Ostatnia zmiana' => 'metaBox.updated',
            'Korekta' => 'metaBox.correction',
            'Grafika' => 'metaBox.graphic',
            'Redakcja' => 'metaBox.redaction',
            'Konsultacja merytoryczna' => 'metaBox.consultation',
        ];

        if ($data->opublikowano ?? false) {
            $opublikowano = Date::create($data->opublikowano)->format('j M Y');
            $meta['Opublikowano'] = $opublikowano;
        }
        if ($data->zaktualizowano ?? false) {
            $zaktualizowano = Date::create($data->zaktualizowano)->format('j M Y');
            if (!isset($opublikowano) || $opublikowano !== $zaktualizowano) {
                $meta['Ostatnia zmiana'] = $zaktualizowano;
            }
        }
        if ($meta) {
            $viewFactory = Container::getInstance()[Factory::class];
            if (!$showDetails) {
                $meta = array_filter($meta, function ($e) {
                    return strpos($e, 'Ostatnia zmiana') !== FALSE;
                }, ARRAY_FILTER_USE_KEY);
            }
            foreach($i18nMaps as $oldKey => $newKey) {
                if(isset($meta[$oldKey])) {
                    $meta[$newKey] = $meta[$oldKey];
                    unset($meta[$oldKey]);
                }
            }
            $metaBox = $viewFactory->make(self::TEMPLATE_PATH, compact('meta'));
            return preg_replace('/<\/h1>/iu', "</h1>\n$metaBox", $content, 1);
        }
        return $content;
    }
}
