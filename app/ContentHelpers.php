<?php

namespace App;

use GuzzleHttp\Client;

class ContentHelpers
{
    /**
    * Replace tags like {%youtube video_id %} with html embeds
    */
    public static function embedVideos($content) {
   
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

    /**
     * Remove orphans (short words hanging at the end of the line)
     * by replacing spaces before them with the non-breaking ones
     */
    public static function removeOrphans($content) {

        $single_letters = 'aiouwz';
        $terms = ['al.','albo','ale','ależ','b.','bez','bm.','bp','br.','by','bym','byś','bł.','cyt.','cz.','czy','czyt.','dn.','do','doc.','dr','ds.','dyr.','dz.','fot.','gdy','gdyby','gdybym','gdybyś','gdyż','godz.','im.','inż.','jw.','kol.','komu','ks.','która','którego','której','któremu','który','których','którym','którzy','lecz','lic.','m.in.','max','mgr','min','moich','moje','mojego','mojej','mojemu','mych','mój','na','nad','nie','niech','np.','nr','nr.','nrach','nrami','nrem','nrom','nrowi','nru','nry','nrze','nrze','nrów','nt.','nw.','od','oraz','os.','p.','pl.','pn.','po','pod','pot.','prof.','przed','przez','pt.','pw.','pw.','tak','tamtej','tamto','tej','tel.','tj.','to','twoich','twoje','twojego','twojej','twych','twój','tylko','ul.','we','wg','woj.','więc','za','ze','śp.','św.','że','żeby','żebyś','—'];

        /* numbers */
        preg_match_all( '/(>[^<]+<)/', $content, $parts );
        if ( $parts && is_array( $parts ) && ! empty( $parts ) ) {
            $parts = array_shift( $parts );
            foreach ( $parts as $part ) {
                $to_change = $part;
                while ( preg_match( '/(\d+) ([\da-z]+)/i', $to_change, $matches ) ) {
                    $to_change = preg_replace( '/(\d+) ([\da-z]+)/i', '$1&nbsp;$2', $to_change );
                }
                if ( $part != $to_change ) {
                    $content = str_replace( $part, $to_change, $content );
                }
            }
        }

        /* orphans */
        $re      = '/^([' . $single_letters . ']|' . preg_replace( '/\./', '\.', implode( '|', $terms ) ) . ') +/i';
        $content = preg_replace( $re, '$1$2&nbsp;', $content );

        /**
         * single letters
         */
        $re = '/([ >\(]+|&nbsp;|&#8222;|&quot;)([' . $single_letters . ']|' . preg_replace( '/\./', '\.', implode( '|', $terms ) ) . ') +/i';

        /**
         * double call to handle orphan after orphan after orphan
         */
        $content = preg_replace( $re, '$1$2&nbsp;', $content );
        $content = preg_replace( $re, '$1$2&nbsp;', $content );

        /**
         * single letter after previous orphan
         */
        $re      = '/(&nbsp;)([' . $single_letters . ']) +/i';
        $content = preg_replace( $re, '$1$2&nbsp;', $content );

        return $content;
    }
   
}