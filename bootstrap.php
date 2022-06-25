<?php

use App\Listeners\{GenerateSitemap, BuildTOC, RedirectsFile};
use App\CustomMarkdownParser;
use Mni\FrontYAML\Markdown\MarkdownParser;

/** @var $container \Illuminate\Container\Container */
/** @var $events \TightenCo\Jigsaw\Events\EventBus */


/*
 * Replace the jigsaw's deafult markdown parser with our custom parser
 * which uses the php port of makdown-it (the library used by hackmd.io)
 * to parse markdown and does some extra processing as well.
 * 
 */
$container->bind(MarkdownParser::class, CustomMarkdownParser::class);


/*
 * Generate sitemap.xml for search engines
 */
$events->afterBuild(GenerateSitemap::class);

/*
 * Generate and attach Table of Contents to articles
 */
$events->afterBuild(BuildTOC::class);

/*
 * Copy redirects info from the file with human readable name
 * to the file readable by netlify
 */
$events->afterBuild(RedirectsFile::class);

