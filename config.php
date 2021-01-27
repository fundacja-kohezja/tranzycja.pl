<?php

use Illuminate\Support\Str;

Jenssegers\Date\Date::setLocale('pl_PL');

$parser = new Mni\FrontYAML\Parser();

$document = $parser->parse(file_get_contents(__DIR__ . '/source/_ogolne/konfiguracja.yml'), false);

$yaml_config = $document->getYAML();

return (array)$yaml_config + [
    'baseUrl' => '',
    'production' => false,

    // Algolia DocSearch credentials
    'docsearchApiKey' => '',
    'docsearchIndexName' => '',

    'collections' => [
        'strony' => [
            'title' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches);
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches2);
                return $matches[1] ?? (isset($matches2[1]) ? Str::of($matches2[1])->limit(30) : Str::of(strip_tags($tresc))->limit(30));
            }
        ],
        'poradniki' => [
            'sort' => '-data',
            'title' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches);
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches2);
                return $matches[1] ?? (isset($matches2[1]) ? Str::of($matches2[1])->limit(30) : Str::of(strip_tags($tresc))->limit(30));
            },
            'excerpt' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches);
                return isset($matches[1]) ? Str::of($matches[1])->words(40, '… <b>Czytaj dalej</b>') : Str::of(strip_tags($tresc))->words(40, '… <b>Czytaj dalej</b>');
            },
            'longerExcerpt' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches);
                return isset($matches[1]) ? Str::of($matches[1])->words(120, '… <b>Czytaj dalej</b>') : Str::of(strip_tags($tresc))->words(120, '… <b>Czytaj dalej</b>');
            }
        ],
        'aktualnosci' => [
            'sort' => '-data'
        ]
    ],

    'mainNav' => [
        (object)[
            'title' => 'Poradniki',
            'path' => '/poradniki',
        ],
        (object)[
            'title' => 'Aktualności',
            'path' => '/aktualnosci',
        ],
        (object)[
            'title' => 'Pytania i odpowiedzi',
            'path' => '/#faq',
        ],
        (object)[
            'title' => 'Materiały',
            'path' => '/materialy',
        ]
    ],

    // helpers

    'title' => fn() => null,
    'isActive' => function ($page, $path) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($path));
    },
    'isActiveParent' => function ($page, $menuItem) {
        if (is_object($menuItem) && $menuItem->children) {
            return $menuItem->children->contains(function ($child) use ($page) {
                return trimPath($page->getPath()) == trimPath($child);
            });
        }
    },
    'url' => function ($page, $path) {
        return Str::startsWith($path, 'http') ? $path : '/' . trimPath($path);
    },
];
