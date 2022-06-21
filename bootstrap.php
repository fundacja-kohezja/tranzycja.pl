<?php

use App\Listeners\PrependFrontmatter;
use App\Listeners\GenerateSitemap;
use App\Listeners\RestoreSourceFiles;
use App\Listeners\BuildTOC;
use App\Listeners\RedirectsFile;
use App\CustomMarkdownParser;
use Mni\FrontYAML\Markdown\MarkdownParser;

/** @var $container \Illuminate\Container\Container */
/** @var $events \TightenCo\Jigsaw\Events\EventBus */

/**
 * You can run custom code at different stages of the build process by
 * listening to the 'beforeBuild', 'afterCollections', and 'afterBuild' events.
 *
 * For example:
 *
 * $events->beforeBuild(function (Jigsaw $jigsaw) {
 *     // Your code here
 * });
 * 
 */
$container->bind(MarkdownParser::class, CustomMarkdownParser::class);

$events->beforeBuild(PrependFrontmatter::class);

$events->afterBuild(GenerateSitemap::class);
$events->afterBuild(RestoreSourceFiles::class);
$events->afterBuild(BuildTOC::class);
$events->afterBuild(RedirectsFile::class);

