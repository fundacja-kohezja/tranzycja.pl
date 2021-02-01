<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class ProcessExtraMarkdownTags
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

    public function handle(Jigsaw $jigsaw)
    {
        $files = array_merge(
            $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath().'/strony'),
            $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath().'/publikacje'),
            $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath().'/aktualnosci'),
            $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath().'/krok-po-kroku')
        );
        $files[] = $jigsaw->getFilesystem()->getFile($jigsaw->getDestinationPath(), 'index.html');
    
        foreach ($files as $file) {
    
            $new_content = $jigsaw->getFilesystem()->get($file);
            
            
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
            
            $new_content = preg_replace_callback_array(
                $callables,
                $new_content
            );
    
    
            /* Augment tables */
    
            $new_content = preg_replace('/<table>.*<\/table>/suU', '<div class="table_container">$0</div>', $new_content);


            /* Markdown highlight */
    
            $new_content = preg_replace('/==(\S.*\S)==/uU', '<mark>$1</mark>', $new_content);
            $new_content = preg_replace('/==(\S)==/uU', '<mark>$1</mark>', $new_content);


            /* Checkboxes */
    
            $new_content = preg_replace_callback('/<li>\[ \] +(\S.*)<\/li>/uU', fn($matches) => '<li><label><input onchange="if(this.checked){localStorage[this.value] = 1}else{localStorage.removeItem(this.value)}" value="' . Str::uuid() . '" type="checkbox"> ' . $matches[1] . '</label></li>', $new_content);
            $new_content = preg_replace_callback('/<li>\[x\] +(\S.*)<\/li>/uU', fn($matches) => '<li><label><input onchange="if(this.checked){localStorage[this.value] = 1}else{localStorage.removeItem(this.value)}" value="' . Str::uuid() . '" type="checkbox"> ' . $matches[1] . '</label></li>', $new_content);

    
            /* Markdown emojis */
    
            foreach ($this->emoji_replacements as $code => $emoji) {
                $new_content = str_replace(":$code:", $emoji, $new_content);
            }
    
    
            /* Markdown blocks */
    
            foreach ($this->block_types as $type) {
                $new_content = preg_replace('/(<p>:::|:::)' . $type . '(.*?)(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '<aside class="alert alert-' . $type  . '">$4</aside>$5$6', $new_content);
            }
    
    
            /* Markdown spoiler */
    
            $new_content = preg_replace('/(<p>:::|:::)spoiler (.*?)(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '<details><summary>$2</summary><div class="mt-4">$4</div></details>$5$6', $new_content);
            $new_content = preg_replace('/(<p>:::|:::)spoiler(\R|<\/p>\R)([\s\S]*?)(\R|\R<p>)(:::<\/p>|:::)/u', '<details><div class="mt-4">$3</div></details>$4$5', $new_content);
    
            $new_content = preg_replace('/(\R|\R<p>)(:::<\/p>|:::)/u', '', $new_content);
    
            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
        }
    }
}