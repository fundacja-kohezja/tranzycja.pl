<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;
use Illuminate\Support\Facades\Date;
use Mni\FrontYAML\Parser;

class PrependFrontmatter
{
    public function handle(Jigsaw $jigsaw)
    {
        $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_strony');
        foreach ($files as $file) {
            $new_content = 
'---
extends: __source.layouts.page
section: content
---
' . $jigsaw->getFilesystem()->get($file);
            
            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
        }
        


        $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_aktualnosci');
        foreach ($files as $file) {
            $parser = new Parser;

            $document = $parser->parse($jigsaw->getFilesystem()->get($file), false);

            $yaml = $document->getYAML();
            if (isset($yaml['data']) && strlen($yaml['data']) === 19) {

                $new_content = 
'---
data: \'' . $yaml['data'] . '\'
extends: __source.layouts.post
section: content
---
' . $document->getContent();

            } else {

                $new_content = 
'---
data: \'' . Date::now() . '\'
extends: __source.layouts.post
section: content
---
' . $document->getContent();

            }
            
            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
        }




        $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_publikacje');

        foreach ($files as $file) {

            $parser = new Parser;

            $document = $parser->parse($jigsaw->getFilesystem()->get($file), false);

            $yaml = $document->getYAML();
            if (isset($yaml['data']) && strlen($yaml['data']) === 19) {

                $new_content = 
'---
data: \'' . $yaml['data'] . '\'
extends: __source.layouts.artl
section: content
---
' . $document->getContent();

            } else {

                $new_content = 
'---
data: \'' . Date::now() . '\'
extends: __source.layouts.artl
section: content
---
' . $document->getContent();

            }

            
            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
        }




        $files = $jigsaw->getFilesystem()->files($jigsaw->getSourcePath().'/_krok_po_kroku');

        foreach ($files as $file) {

            $parser = new Parser;

            $document = $parser->parse($jigsaw->getFilesystem()->get($file), false);

            $yaml = $document->getYAML();
            if (isset($yaml['kolejnosc']) && strlen($yaml['kolejnosc']) <= 3) {

                $new_content = 
'---
kolejnosc: ' . str_pad($yaml['kolejnosc'], 3) . '
extends: __source.layouts.step
section: content
---
' . $document->getContent();

        } else {

            $new_content = 
'---
kolejnosc: 0  
extends: __source.layouts.step
section: content
---
' . $document->getContent();

            }

            
            $jigsaw->getFilesystem()->putWithDirectories($file, $new_content);
        }
    }
}