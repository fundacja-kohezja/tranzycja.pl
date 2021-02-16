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
        'publikacje' => [
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
                return Str::of(strip_tags($matches[1] ?? $tresc))->words(40) . ' <b class="inline-block">Czytaj dalej →</b>';
            },
            'longerExcerpt' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches);
                return Str::of(strip_tags($matches[1] ?? $tresc))->words(120) . ' <b class="inline-block">Czytaj dalej →</b>';
            }
        ],
        'krok_po_kroku' => [
            'sort' => 'kolejnosc',
            'title' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches);
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches2);
                return $matches[1] ?? (isset($matches2[1]) ? Str::of($matches2[1])->limit(30) : Str::of(strip_tags($tresc))->limit(30));
            },
            'excerpt' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches);
                return Str::of(strip_tags($matches[1] ?? $tresc))->words(80) . ' <b class="inline-block">Czytaj dalej →</b>';
            }
        ],
        'aktualnosci' => [
            'sort' => '-data'
        ]
    ],

    'mainNav' => [
        (object)[
            'title' => 'Krok po kroku',
            'path' => '/krok-po-kroku',
        ],
        (object)[
            'title' => 'Publikacje',
            'path' => '/publikacje',
        ],
        (object)[
            'title' => 'Aktualności',
            'path' => '/aktualnosci',
        ],
        (object)[
            'title' => 'Pytania i odpowiedzi',
            'path' => '/#faq',
        ],
        /* (object)[
            'title' => 'Materiały',
            'path' => '/materialy',
        ] */
    ],

    // helpers

    'title' => fn() => null,
    'isActive' => function ($page, $path) {
        return Str::contains(trimPath($page->permalink), trimPath($path));
    },
    'url' => function ($page, $path) {
        return Str::startsWith($path, 'http') ? $path : '/' . trimPath($path);
    },
];
