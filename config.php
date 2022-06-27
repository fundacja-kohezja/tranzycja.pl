<?php

use Illuminate\Support\Str;
use TightenCo\Jigsaw\Collection\CollectionItem;

Jenssegers\Date\Date::setLocale('pl_PL');

$parser = new Mni\FrontYAML\Parser();

$document = $parser->parse(file_get_contents(__DIR__ . '/source/_ogolne/konfiguracja.yml'), false);

$yaml_config = $document->getYAML();

function excerpt(CollectionItem $page, int $words) {


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

}

$pub_config = [
    'sort' => '-opublikowano',
    'title' => function ($page) {
        $tresc = $page->getContent();
        preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches);
        preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches2);
        return $matches[1] ?? (isset($matches2[1]) ? Str::of($matches2[1])->limit(30) : Str::of(strip_tags($tresc))->limit(30));
    },
    'excerpt' => function ($page) {
        return excerpt($page, 30);
    },
    'longerExcerpt' => function ($page) {
        return excerpt($page, 80);
    }
];

return (array)$yaml_config + [
    'baseUrl' => 'https://tranzycja.pl',

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
            },
            'extends' => '__source.layouts.page'
        ],
        'publikacje' => $pub_config + ['extends' => '__source.layouts.artl'],
        'publications' => $pub_config + ['extends' => '__source.layouts.aeng'],
        'krok_po_kroku' => [
            'sort' => 'kolejnosc',
            'title' => function ($page) {
                $tresc = $page->getContent();
                preg_match('|<h1[^>]*>(.*)</h1>|miU', $tresc, $matches);
                preg_match('|<p[^>]*>(.*)</p>|siU', $tresc, $matches2);
                return $matches[1] ?? (isset($matches2[1]) ? Str::of($matches2[1])->limit(30) : Str::of(strip_tags($tresc))->limit(30));
            },
            'excerpt' => function ($page) {
                return excerpt($page, 60);
            },
            'extends' => '__source.layouts.step'
        ],
        'aktualnosci' => [
            'sort' => '-opublikowano',
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
