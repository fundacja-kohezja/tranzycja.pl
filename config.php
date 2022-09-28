<?php

use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

Jenssegers\Date\Date::setLocale('pl_PL');

$yaml_config = Yaml::parse(file_get_contents(__DIR__ . '/source/_ogolne/konfiguracja.yml'));

return $yaml_config + [
    'baseUrl' => 'https://tranzycja.pl',
    'collections' => [
        'strony',
        
        'wsparcie' => [
            'sort' => 'kolejnosc',
            'TOC' => [
                'label' => 'Wsparcie tranzycja.pl',
                'allPages' => true
            ]
        ],
        
        'publikacje' => [
            'sort' => '-opublikowano',
            'TOC' => [
                'label' => 'Spis treści'
            ],
            'footerBox' => 'stopka_artykulu',
            'showDetailsInMetabox' => true
        ],

        'krok_po_kroku' => [
            'sort' => 'kolejnosc',
            'TOC' => [
                'label' => 'Tranzycja krok po kroku',
                'allPages' => true
            ],
            'footerBox' => 'stopka_artykulu'
        ],

        'aktualnosci' => [
            'sort' => '-opublikowano'
        ],

        //English version of website:
        'sites' => [
            'extends' => '__source.single.en.pages',
            'path' => 'en/sites'
        ],

        'publications' => [
            'sort' => '-opublikowano',
            'TOC' => [
                'label' => 'Contents'
            ],
            'showDetailsInMetabox' => true,
            'extends' => '__source.single.en.publication',
            'path' => 'en/publications',
        ],

        'support' => [
            'sort' => 'kolejnosc',
            'TOC' => [
                'label' => 'Support tranzycja.pl',
                'allPages' => true
            ],
            'extends' => '__source.single.en.support',
            'path' => 'en/support',
        ],
    ],

    'mainNav' => [
        'en' => [
            'items' => [
                '/en#about-us' => 'About us',
                '/en/publications' => 'Publications',
                '/en/support' => '*Support us!'
            ],
        ], 
        'pl' => [
            'items' => [
                '/krok-po-kroku' => 'Krok po kroku',
                '/publikacje' => 'Publikacje',
                '/aktualnosci' => 'Aktualności',
                '/#faq' => 'FAQ',
                '/wsparcie' => '*Wesprzyj nas!'
            ],
        ],
        'isActive' => fn($page, $path) => Str::startsWith($page->getPath(), $path)
    ]
];
