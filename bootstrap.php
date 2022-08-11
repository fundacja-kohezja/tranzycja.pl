<?php

use App\Listeners\{GenerateSitemap, RedirectsFile};
use App\{CustomMdParser, CustomMdHandler};
use Mni\FrontYAML\Markdown\MarkdownParser;
use TightenCo\Jigsaw\Handlers\MarkdownHandler;

/** @var $container \Illuminate\Container\Container */
/** @var $events \TightenCo\Jigsaw\Events\EventBus */


/*
 * Replace the jigsaw's deafult markdown parser with our custom parser
 * which uses the php port of makdown-it (the library used by hackmd.io)
 * to parse markdown and does some extra processing as well.
 * 
 */
$container->bind(MarkdownParser::class, CustomMdParser::class);


/*
 * Replace the jigsaw's default markdown handler with our custom handler
 * which puts current page data in the container so it can be retrievied
 * by our custom parser.
 * 
 * This allows content processing to be manipulated per collection
 * in config file or per page in frontmatter.
 * 
 */
$container->bind(MarkdownHandler::class, CustomMdHandler::class);


/*
 * Generate sitemap.xml for search engines
 */
$events->afterBuild(GenerateSitemap::class);

/*
 * Copy redirects info from the file with human readable name
 * to the file readable by netlify
 */
$events->afterBuild(RedirectsFile::class);

