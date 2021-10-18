<?php

use Illuminate\Support\Str;
use TightenCo\Jigsaw\Collection\CollectionItem;

Jenssegers\Date\Date::setLocale('pl_PL');

$parser = new Mni\FrontYAML\Parser();

$document = $parser->parse(file_get_contents(__DIR__ . '/source/_ogolne/konfiguracja.yml'), false);

$yaml_config = $document->getYAML();

function excerpt(CollectionItem $page, int $words) {


    $content = $page->getContent();

    /* Markdown spoiler */

    $content = preg_replace('/(<p>:::|:::)\s*spoiler (.*?)(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '', $content);
    $content = preg_replace('/(<p>:::|:::)\s*spoiler(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '', $content);


    /* Markdown blocks */

    $block_types = ['success', 'info', 'warning', 'danger'];
    foreach ($block_types as $type) {
        $content = preg_replace('/(<p>:::|:::)\s*' . $type . '(.*?)(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '', $content);
    }


    /* One ending for nested block */

    $content = preg_replace('/(\R|\R<p>)(:::<\/p>|:::)/u', '', $content);
    
    preg_match('|<p[^>]*>(.*)</p>|siU', $content, $matches);
    return Str::of(strip_tags($matches[1] ?? $content))->words($words) . ' <b class="inline-block">Czytaj dalej →</b>';

}

$pub_config = [
    'sort' => '-data',
    'title' => function ($page) {
        $tresc = $page->getContent();
        preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches);
        preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches2);
        return $matches[1] ?? (isset($matches2[1]) ? Str::of($matches2[1])->limit(30) : Str::of(strip_tags($tresc))->limit(30));
    },
    'excerpt' => function ($page) {
        return excerpt($page, 40);
    },
    'longerExcerpt' => function ($page) {
        return excerpt($page, 120);
    }
];

return (array)$yaml_config + [
    'baseUrl' => 'https://tranzycja.pl',
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
        'publikacje' => $pub_config,
        'publications' => $pub_config,
        'krok_po_kroku' => [
            'sort' => 'kolejnosc',
            'title' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches);
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches2);
                return $matches[1] ?? (isset($matches2[1]) ? Str::of($matches2[1])->limit(30) : Str::of(strip_tags($tresc))->limit(30));
            },
            'excerpt' => function ($page) {
                return excerpt($page, 80);
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
