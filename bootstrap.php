<?php

use App\Listeners\{
    FixListsSeparation,
    PrependFrontmatter,
    GenerateSitemap,
    RestoreSourceFiles,
    BuildTOC,
    ContentPostProcess,
    RedirectsFile,
    DynamicNavItems
};

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

$events->beforeBuild(FixListsSeparation::class);
$events->beforeBuild(PrependFrontmatter::class);

$events->afterCollections(DynamicNavItems::class);

$events->afterBuild(GenerateSitemap::class);
$events->afterBuild(RestoreSourceFiles::class);
$events->afterBuild(ContentPostProcess::class);
$events->afterBuild(BuildTOC::class);
$events->afterBuild(RedirectsFile::class);

