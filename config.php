<?php

use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

include_once(__DIR__ . '/content-helpers.php');

Jenssegers\Date\Date::setLocale('pl_PL');

$yaml_config = Yaml::parse(file_get_contents(__DIR__ . '/source/_ogolne/konfiguracja.yml'));

$pub_config = [
    'sort' => '-opublikowano',
    'excerpt' => fn($page) => $excerpt($page, 30),
    'longerExcerpt' => fn($page) => $excerpt($page, 80)
];


return $yaml_config + [
    'baseUrl' => 'https://tranzycja.pl',
    'title' => $title,
    'collections' => [

        'strony' => [
            'extends' => '__source.layouts.page'
        ],

        'publikacje' => $pub_config + [
            'extends' => '__source.layouts.artl'
        ],

        'publications' => $pub_config + [
            'extends' => '__source.layouts.aeng'
        ],

        'krok_po_kroku' => [
            'sort' => 'kolejnosc',
            'excerpt' => fn($page) => $excerpt($page, 60),
            'extends' => '__source.layouts.step'
        ],

        'aktualnosci' => [
            'sort' => '-opublikowano',
            'excerpt' => fn($page) => $beginning($page, 1000),
            'longerExcerpt' => fn($page) => $beginning($page, 1600),
            'extends' => '__source.layouts.post'
        ]
    ],

    'mainNav' => [
        'items' => [
            '/krok-po-kroku' => 'Krok po kroku',
            '/publikacje' => 'Publikacje',
            '/aktualnosci' => 'Aktualności',
            '/#faq' => 'Pytania i odpowiedzi',
        ],
        'isActive' => fn($page, $path) => Str::startsWith($page->getPath(), $path)
    ],

    // Algolia DocSearch credentials
    'docsearchApiKey' => '',
    'docsearchIndexName' => ''
];
