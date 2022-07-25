<?php

namespace App;

use App\Markdown\{Alert, Attributes, Footnote, Spoiler};
use GuzzleHttp\Client;
use Kaoken\MarkdownIt\MarkdownIt;
use Kaoken\MarkdownIt\Plugins\{MarkdownItMark as Mark, MarkdownItEmoji as Emoji, MarkdownItSup as Superscript};
use Mni\FrontYAML\Markdown\MarkdownParser;

class CustomMdParser implements MarkdownParser
{

    protected $content;

    
    /**
     * Process our markdown file to turn it into the final html content
     * 
     * @param  string $markdown
     * @return  string
     */
    public function parse($markdown)
    {
        $this->content = $markdown;

        $this->embedVideos()
             ->renderMarkdown()
             ->wrapTables()
             ->removeOrphans();

        return $this->content;
    }


    /**
     * Parse markdown and render it as html
     */
    protected function renderMarkdown()
    {
        $parser = (new MarkdownIt([

            /* preserve raw html (like embedded videos) in markdown when parsing */
            'html' => true,

            /* automatically convert plain urls to links */
            'linkify' => true,

        ]))
            ->plugin(new Alert, 'success')      // :::success
            ->plugin(new Alert, 'info')         // :::info
            ->plugin(new Alert, 'warning')      // :::warning
            ->plugin(new Alert, 'danger')       // :::danger
            ->plugin(new Spoiler, 'spoiler')    // :::spoiler
            ->plugin(new Footnote)              // [^1]
            ->plugin(new Attributes)            // {.class}
            ->plugin(new Mark)                  // ==highlight==
            ->plugin(new Emoji)                 // :emoji:
            ->plugin(new Superscript)           // ^sup^
        ;

        $parser->linkify->set([
            /* autoconvert only full urls (with http) */
            'fuzzyLink'  => false
        ]);

        /* fix {{'@'}} in mailto links */
        $parser->renderer->rules->link_open = function($tokens, $idx, $options, $env, $slf) {
            if ($url = $tokens[$idx]->attrGet('href')) {
                $tokens[$idx]->attrSet('href', str_replace("%7B%7B'@'%7D%7D", '@', $url));
            }
            return $slf->renderToken($tokens, $idx, $options, $env, $slf);
        };

        $this->content = $parser->render($this->content);
        
        return $this;
    }


    /**
     * Replace tags like {%youtube video_id %} with html embeds
     */
    protected function embedVideos()
    {
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
        
        $this->content = preg_replace_callback_array(
            $callables,
            $this->content
        );
        return $this;
    }


    /**
     * Wrap tables with a div so they can be scrollable horizontally
     * if too wide to fit
     */
    protected function wrapTables()
    {
        $this->content = preg_replace('/<table>.*<\/table>/suU', '<div class="table_container">$0</div>', $this->content);
        return $this;
    }


    /**
     * Remove orphans (short words hanging at the end of the line)
     * by replacing spaces before them with the non-breaking ones
     */
    protected function removeOrphans()
    {
        $content = $this->content;

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

        $this->content = $content;
        return $this;

    }

}