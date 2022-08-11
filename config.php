<?php

use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

Jenssegers\Date\Date::setLocale('pl_PL');

$yaml_config = Yaml::parse(file_get_contents(__DIR__ . '/source/_ogolne/konfiguracja.yml'));


return $yaml_config + [
    'baseUrl' => 'https://tranzycja.pl',
    'collections' => [

        'strony' => [
            'extends' => '__source.layouts.page'
        ],

        'publikacje' => [
            'sort' => '-opublikowano',
            'extends' => '__source.layouts.artl',
            'TOC' => [
                'label' => 'Spis treści'
            ],
            'footerBox' => 'stopka_artykulu'
        ],

        'publications' => [
            'sort' => '-opublikowano',
            'extends' => '__source.layouts.aeng',
            'TOC' => [
                'label' => 'Contents'
            ]
        ],

        'krok_po_kroku' => [
            'sort' => 'kolejnosc',
            'extends' => '__source.layouts.step',
            'TOC' => [
                'label' => 'Tranzycja krok po kroku',
                'allPages' => true
            ],
            'footerBox' => 'stopka_artykulu'
        ],

        'aktualnosci' => [
            'sort' => '-opublikowano',
            'extends' => '__source.layouts.post'
        ],

        'wsparcie' => [
            'sort' => 'kolejnosc',
            'extends' => '__source.layouts.sprt',
            'TOC' => [
                'label' => 'Wsparcie projektu tranzycja.pl',
                'allPages' => true
            ]
        ],
    ],

    'mainNav' => [
        'items' => [
            '/krok-po-kroku' => 'Krok po kroku',
            '/publikacje' => 'Publikacje',
            '/aktualnosci' => 'Aktualności',
            '/#faq' => 'FAQ',
            '/wsparcie' => '*Wesprzyj nas!'
        ],
        'isActive' => fn($page, $path) => Str::startsWith($page->getPath(), $path)
    ],

    // Algolia DocSearch credentials
    'docsearchApiKey' => '',
    'docsearchIndexName' => ''
];
