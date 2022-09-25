<?php

require_once(__DIR__ . '/../../vendor/tightenco/jigsaw/jigsaw-core.php');
require_once(__DIR__ . '/helpers.php');

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\Exceptions\BadRequestException;
use App\SectionSplitMdParser;
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
 * Use markdown parser which returns content divided into sections
 * ready to be sent to algolia as index records
 */
$container->bind(MarkdownParser::class, SectionSplitMdParser::class);

/**
 * Add method which allows to build the site without writing output files
 * (as this is unnecessary when preparing indices for algolia)
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

/* if (!$use_files_from_args) { pamiętać, by odkomentować
    $articles_index->clearObjects();
    $tags_index->clearObjects();
} */

$records = [];
$all_used_tags = [];

foreach ($collections as $name => $pages) {
    if (in_array($name, DONT_INDEX_COLLECTIONS)) continue;

    foreach ($pages as $page) {
        $tags = $page->getTags();
        $lang = $page->lang; // coś z tym langiem chyba trzeba zrobić
        $sections = $page->getContent();
        $meta = $page->_meta;
        $path = "$meta->collection/$meta->filename";

        /* if ($use_files_from_args) {
            $redirect_url = file_path_to_url($filename);
            $articles_index->deleteBy([
                'filters' => "redirect:'$redirect_url'"
            ]);
        }
 */
        foreach ($sections as $index => $section) {
            if (isset($section->level)) {
                $records[] = [
                    'path' => build_title_path(
                        $section->title,
                        $section->level,
                        $sections->take($index) // get only previous sections to look for parents
                    ),
                    'content' => $section->content,
                    'section' => $section->slug,
                    'redirect' => $path,
                    'tags' => $tags,
                    'objectID' => md5($path . $section->slug)
                ];
            } else {

            }
            
        }

        $all_used_tags = array_merge($tags, $all_used_tags);
    }
};

try {
    $articles_index->saveObjects($records, [
        'autoGenerateObjectIDIfNotExist' => true
    ]);
} catch (BadRequestException $e) {
    var_dump($collected_data);
}

$tags_objects = [];
foreach (array_unique($all_used_tags) as $tag) {
    $tags_objects[] = [
        'name' => $tag
    ];
}

$tags_index->saveObjects($tags_objects, [
    'objectIDKey' => 'name'
]);
