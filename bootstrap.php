<?php

use App\Listeners\{GenerateSitemap, RedirectsFile, TemplateNames, GenerateSearchCaches};
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
 * Initial binding of page data, needed by our custom markdown parser.
 * 
 * It usually receives page data from the custom handler, but it needs
 * some initial value in case parser gets called first time before the
 * handler does.
 * 
 */
$container->bind('page', fn() => null);


/*
 * Automatically set template names for collections so they don't need
 * to be specified in the config file.
 */
$events->beforeBuild(TemplateNames::class);

/*
 * Generate sitemap.xml for search engines
 */
$events->afterBuild(GenerateSitemap::class);

/*
 * Copy redirects info from the file with human readable name to the file
 * readable by netlify and add dynamically generated items.
 */
$events->afterBuild(RedirectsFile::class);


/*
 * Generate JSON caches used by search input
 */
$events->afterBuild(GenerateSearchCaches::class);