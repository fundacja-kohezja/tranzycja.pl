<?php

use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;
use TightenCo\Jigsaw\Collection\CollectionItem;


Jenssegers\Date\Date::setLocale('pl_PL');


$yaml_config = Yaml::parse(file_get_contents(__DIR__ . '/source/_ogolne/konfiguracja.yml'));

/*
 * get the title from the first h1 (or first paragraph if no h1)
 * so authors don't need to specify it in frontmatter
 */
$title = function ($page) {

    $tresc = $page->getContent();

    if (preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches)) {
        return strip_tags(html_entity_decode($matches[1]));
    }

    preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches);

    return Str::of(html_entity_decode($matches[1] ?? $tresc))
            ->stripTags()
            ->limit(30);
};

/*
 * get the beginning of the page content as an excerpt
 */
$excerpt = function(CollectionItem $page, int $words) {

    $content = $page->getContent();
    
    $content = preg_replace('|<sup[^>]*>(.*)</sup>|siU', '', $content);
    $found = preg_match('|<p[^>]*>(.*)</p>|siU', $content, $match);
    if ($found) {
        if (mb_strlen(strip_tags($match[1])) > $words * 2.5) {
            /* 
             * We want the excerpt to end nicely at the end of a sentence
             * so we get only the fist paragraph...
             */
            $content = $match[1];
        } else {
            /*
             * ...unless the first paragraph is very short.
             * In that case we get the whole text (we'll limit amount of words later anyway)
             */
            preg_match_all('|<p[^>]*>(.*)</p>|siU', $content, $matches);
            $content = implode('<br><br>', $matches[1]);
        }
    }

    return Str::of($content)
            ->stripTags('<br>')
            ->replace('<br><br><br><br>', '<br><br>')
            ->words($words)
            . ' <b class="inline-block">Czytaj dalej →</b>';

};


$pub_config = [
    'sort' => '-opublikowano',
    'excerpt' => fn($page) => $excerpt($page, 30),
    'longerExcerpt' => fn($page) => $excerpt($page, 80),
    'title' => $title
];


return (array)$yaml_config + [
    'baseUrl' => 'https://tranzycja.pl',

    // Algolia DocSearch credentials
    'docsearchApiKey' => '',
    'docsearchIndexName' => '',

    'collections' => [
        'strony' => [
            'title' => $title,
            'extends' => '__source.layouts.page'
        ],
        'publikacje' => $pub_config + ['extends' => '__source.layouts.artl'],
        'publications' => $pub_config + ['extends' => '__source.layouts.aeng'],
        'krok_po_kroku' => [
            'sort' => 'kolejnosc',
            'title' => $title,
            'excerpt' => fn($page) => $excerpt($page, 60),
            'extends' => '__source.layouts.step'
        ],
        'aktualnosci' => [
            'sort' => '-opublikowano',
            'title' => $title,
            'extends' => '__source.layouts.post'
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
        ]
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
