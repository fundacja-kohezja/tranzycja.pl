<?php

namespace App\ContentHelpers;

use GuzzleHttp\Client;

/**
 * Replace tags like {%youtube video_id %} with html embeds
 */
class EmbedVideos
{
    public static function process($content) {
   
        $noembed_replacements = [
            'youtube' => 'https://www.youtube.com/watch?v=',
            'vimeo' => 'https://vimeo.com/'
        ];
    
        $callables = [];
    
        $http = new Client(['base_uri' => 'http://noembed.com']);
    
        foreach ($noembed_replacements as $service => $prefix) {
    
            $callables["/{%$service +(.*?) *%}/"] = function (&$matches) use ($prefix, $http) {
    
                /* make http request to noembed.com and recieve html video embed  */
                $resp = json_decode($http->get('/embed?url=' . urlencode($prefix . $matches[1]))
                    ->getBody()
                    ->getContents()
                );
    
                /*
                 * wrap the embed with a div and set the bottom padding
                 * it's the css hack to maintain proper aspect ratio of the embed no matter the width
                 */
                if (intval($resp->width ?? 0) && intval($resp->height ?? 0)) {
                    $ratio = $resp->height / $resp->width * 100;
                    return ('<div class="ratio-iframe" style="padding-bottom: ' . $ratio . '%">') . ($resp->html ?? '') . '</div>';
                }
    
                return $resp->html ?? '';
            };
        }
        
        return preg_replace_callback_array(
            $callables,
            $content
        );
    }
}