<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;
use Illuminate\Support\Str;

class BuildTOC
{
    public function handle(Jigsaw $jigsaw)
    {

        $this->processFolder($jigsaw, '/publikacje', 'Spis treści');
        $this->processFolder($jigsaw, '/publications', 'Contents');
        
        $this->processFolderWithAll($jigsaw, '/krok-po-kroku', 'krok_po_kroku', 'Tranzycja krok po kroku');
        $this->processFolderWithAll($jigsaw, '/wsparcie', 'wsparcie', 'Wsparcie projektu tranzycja.pl');
    }

    protected function processFolder(Jigsaw $jigsaw, string $path, string $toc_label)
    {
        $files = $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath() . $path);

        foreach ($files as $file) {
            $new_content = $jigsaw->getFilesystem()->get($file);


            preg_match_all( '|<h([^>]+)>(.*)</h[^>]+>|iU', $new_content, $headings );


            $processed_headings = [];

            $new_content = preg_replace_callback('|<h([^>]+)>(.*)</h([^>]+)>|iU', function (&$matches) use (&$processed_headings) {
                    if (in_array($matches[1][0], ['1', '2', '3'])) {
                        $processed_headings[] = [
                            'level' => $matches[1][0],
                            'text' => $matches[2],
                            'slug' => $slug = Str::slug($matches[2])
                        ];
                        return "<h$matches[1] id=\"$slug\">$matches[2]</h$matches[3]>";
                    }
                    return $matches[0];
                },
                $new_content
            );

            if ($processed_headings) {
 
                $toc = '<aside class="toc-container"><details id="toc" class="bg-gray-100 dark:bg-gray-800 rounded-lg px-4 py-1 lg:py-0 toc lg:my-4"><summary>' . $toc_label . '</summary><nav><ul class="list-none pl-0 mt-0">';

                foreach ($processed_headings as $h) {
                    if ($h['level'] == 1){
                        $toc .= '<li><a onclick="if(window.matchMedia(\'(max-width: 1023px)\').matches)this.parentElement.parentElement.parentElement.parentElement.open = false" class="block leading-tight font-bold border-b-0 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" href="#' . $h['slug'] . '">' . $h['text'] . '</a></li>';
                    } else {
                        $toc .= '<li' . ($h['level'] > 2 ? ' class="foldable" ' : '') . '><a onclick="if(window.matchMedia(\'(max-width: 1023px)\').matches)this.parentElement.parentElement.parentElement.parentElement.open = false" class="block leading-tight font-light sm:text-xs border-b-0 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" style="padding-left: ' . ($h['level'] - 1) . 'rem" href="#' . $h['slug'] . '">' . $h['text'] . '</a></li>';

                    }
                }
        
                $toc .= '</ul></nav></details></aside>';
        
                $new_content = preg_replace('/<\/h1>/iu', "</h1>\n$toc", $new_content, 1);
        
                $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
            }
            
        }
    }

    protected  function processFolderWithAll(Jigsaw $jigsaw, string $path, string $collection_name, string $toc_label)
    {
        $files = $jigsaw->getFilesystem()->files($jigsaw->getDestinationPath(). $path);

        foreach ($files as $file) {

            if (Str::endsWith($file->getPath(), $path)) continue;

            $new_content = $jigsaw->getFilesystem()->get($file);


            preg_match_all( '|<h([^>]+)>(.*)</h[^>]+>|iU', $new_content, $headings );


            $processed_headings = [];

            $new_content = preg_replace_callback('|<h([^>]+)>(.*)</h([^>]+)>|iU', function (&$matches) use (&$processed_headings) {
                    if (in_array($matches[1][0], ['1', '2', '3'])) {
                        $processed_headings[] = [
                            'level' => $matches[1][0],
                            'text' => $matches[2],
                            'slug' => $slug = Str::slug($matches[2])
                        ];
                        return "<h$matches[1] id=\"$slug\">$matches[2]</h$matches[3]>";
                    }
                    return $matches[0];
                },
                $new_content
            );
            $toc = '<aside class="toc-container"><details id="toc" class="bg-gray-100 dark:bg-gray-800 rounded-lg px-4 py-1 lg:py-0 toc lg:my-4"><summary>' . $toc_label . '</summary><nav><ol class="pl-0 mt-0">';


            foreach($jigsaw->getCollection($collection_name) as $entry) {

                if (str_contains($file->getPath(), Str::slug($entry->getFilename())) && $processed_headings) {
                    $toc .= '<li><ul class="list-none pl-0 mt-0">';
                    foreach ($processed_headings as $h) {
                        if ($h['level'] == 1){
                            $toc .= '<li><a onclick="if(window.matchMedia(\'(max-width: 1023px)\').matches)this.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.open = false" class="block leading-tight font-extrabold border-b-0 text-pink-700 dark:text-purple-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" href="#' . $h['slug'] . '">' . $h['text'] . '</a></li>';
                        } else {
                            $toc .= '<li' . ($h['level'] > 2 ? ' class="foldable" ' : '') . '><a onclick="if(window.matchMedia(\'(max-width: 1023px)\').matches)this.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.open = false" class="block leading-tight font-light sm:text-xs border-b-0 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" style="padding-left: ' . $h['level'] . 'rem" href="#' . $h['slug'] . '">' . $h['text'] . '</a></li>';
        
                        }
                    }
                    $toc .= '</ul></li>';
                }

                else {
                    $toc .= '<li><a class="block leading-tight font-bold border-b-0 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md" href="' . $entry->getPath() . '">' . $entry->title() . '</a></li>';
                }

            } 

            $toc .= '</ol></nav></details></aside>';
        
            $new_content = preg_replace('/<article([^>]*)>/iuU', "<article$1>\n$toc", $new_content, 1);

            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
            
        }
    }
}