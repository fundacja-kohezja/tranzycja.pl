<?php

namespace App\Listeners;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class ExtraMarkdownTags
{
    protected $noembed_replacements = [
        'youtube' => 'https://www.youtube.com/watch?v=',
        'vimeo' => 'https://vimeo.com/'
    ];
    protected $emoji_replacements;
    protected $block_types = ['success', 'info', 'warning', 'danger'];


    public function __construct()
    {
        $this->emoji_replacements = json_decode(file_get_contents(__DIR__ . '/emojis.json'), true);
    }

    public function process(string $content): string
    {
        /* Markdown embeds */

        $callables = [];
        $http = new Client(['base_uri' => 'http://noembed.com']);

        foreach ($this->noembed_replacements as $service => $prefix) {
            $callables["/{%$service +(.*?) *%}/"] = function (&$matches) use ($prefix, $http) {
                $resp = json_decode($http->get('/embed?url=' . urlencode($prefix . $matches[1]))
                    ->getBody()
                    ->getContents()
                );

                if (intval($resp->width ?? 0) && intval($resp->height ?? 0)) {
                    $ratio = $resp->height / $resp->width * 100;
                    return ('<div class="ratio-iframe" style="padding-bottom: ' . $ratio . '%">') . ($resp->html ?? '') . '</div>';
                }

                return $resp->html ?? '';
            };
        }
        
        $content = preg_replace_callback_array(
            $callables,
            $content
        );


        /* Augment tables */

        $content = preg_replace('/<table>.*<\/table>/suU', '<div class="table_container">$0</div>', $content);


        /* Markdown highlight */

        $content = preg_replace('/==(\S.*\S)==/uU', '<mark>$1</mark>', $content);
        $content = preg_replace('/==(\S)==/uU', '<mark>$1</mark>', $content);


        /* Checkboxes */

        $content = preg_replace_callback('/<li>\[ \] +(\S.*)<\/li>/uU', fn($matches) => '<li><label><input onchange="if(this.checked){localStorage[this.value] = 1}else{localStorage.removeItem(this.value)}" value="' . Str::uuid() . '" type="checkbox"> ' . $matches[1] . '</label></li>', $content);
        $content = preg_replace_callback('/<li>\[x\] +(\S.*)<\/li>/uU', fn($matches) => '<li><label><input onchange="if(this.checked){localStorage[this.value] = 1}else{localStorage.removeItem(this.value)}" value="' . Str::uuid() . '" type="checkbox"> ' . $matches[1] . '</label></li>', $content);


        /* Markdown emojis */

        foreach ($this->emoji_replacements as $code => $emoji) {
            $content = str_replace(":$code:", $emoji, $content);
        }


        /* Markdown spoiler */

        $content = preg_replace('/(<p>:::|:::)\s*spoiler (.*?)(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '<details><summary>$2</summary><div class="mt-4">$4</div></details>$5$6', $content);
        $content = preg_replace('/(<p>:::|:::)\s*spoiler(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '<details><div class="mt-4">$3</div></details>$4$5', $content);


        /* Markdown blocks */

        foreach ($this->block_types as $type) {
            $content = preg_replace('/(<p>:::|:::)\s*' . $type . '(.*?)(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '<aside class="alert alert-' . $type  . '">$4</aside>$5$6', $content);
        }


        /* One ending for nested block */

        $content = preg_replace('/(\R|\R<p>)(:::<\/p>|:::)/u', '', $content);

        return $content;
    }
}