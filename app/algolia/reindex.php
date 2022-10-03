<?php

require_once(__DIR__ . '/../../vendor/tightenco/jigsaw/jigsaw-core.php');

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\Exceptions\BadRequestException;
use App\{SearchRecordsBuilder, SectionSplitMdParser};
use Illuminate\Support\Str;
use Mni\FrontYAML\Markdown\MarkdownParser;
use Symfony\Component\Console\Output\OutputInterface;
use TightenCo\Jigsaw\Jigsaw;


$client = SearchClient::create('C8U4P0CC81', getenv('ADMIN_API_KEY'));
$articles_index = $client->initIndex('articles');
$tags_index = $client->initIndex('tags');


const DONT_INDEX_COLLECTIONS = [
    'strony', 'aktualnosci',
];


/**
 * Setup required for site builder to launch
 */
$container->buildPath = [
    'source' => $container->buildPath['source'],
    'views' => $container->buildPath['source']
];
$container->consoleOutput->setup(OutputInterface::VERBOSITY_QUIET);

/**
 * Instead of html-generating markdown parser use the one which returns
 * content divided into sections of plain text with proper metadata
 * to build search records from
 */
$container->bind(MarkdownParser::class, SectionSplitMdParser::class);

/**
 * Add method allowing to build the site without writing output files
 * (as they are unnecessary when preparing records for algolia)
 */
Jigsaw::macro('initCollections', function () {
    $this->siteData = $this->dataLoader->loadSiteData($this->app->config);

    return $this->buildCollections()
                ->cleanup();
});

$jigsaw = $container->make(Jigsaw::class);
$collections = $jigsaw->initCollections() // <-- macro defined above
                      ->getCollections();

array_shift($argv);
$use_files_from_args = count($argv) > 0;

if (!$use_files_from_args) {
    $articles_index->clearObjects();
    $tags_index->clearObjects();
}

$all_used_tags = [];

foreach ($collections as $name => $pages) {
    if (in_array($name, DONT_INDEX_COLLECTIONS)) continue;

    foreach ($pages as $page) {
        if ($page->lang) {
            // for now we don't index non-polish pages
            continue;
        }

        $tags = $page->getTags();

        $collection = Str::slug($page->getCollection());
        $filename = $page->getFilename();
        $redirect = "$collection/$filename/";

        $objectID = fn($section) => md5($redirect . $section->slug);

        if ($use_files_from_args) {
            $articles_index->deleteBy([
                'filters' => "redirect:'$redirect'"
            ]);
        }
        // TODO: obługa ścieżek plików z komendy, indeksowanie FAQ
        $records = SearchRecordsBuilder::build($page, compact('redirect', 'tags', 'objectID'));

        try {
            $articles_index->saveObjects($records, [
                'autoGenerateObjectIDIfNotExist' => true
            ]);
        } catch (BadRequestException $e) {
            var_dump($collected_data);
        }

        $all_used_tags = array_merge($tags, $all_used_tags);
    }
};

$tags_objects = [];
foreach (array_unique($all_used_tags) as $tag) {
    $tags_objects[] = [
        'name' => $tag
    ];
}

$tags_index->saveObjects($tags_objects, [
    'objectIDKey' => 'name'
]);
