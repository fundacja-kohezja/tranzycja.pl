<?php

require_once(__DIR__ . '/../../vendor/tightenco/jigsaw/jigsaw-core.php');

use Symfony\Component\DomCrawler\Crawler;
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

$all_used_tags = [];

foreach ($collections as $name => $pages) {
    if (in_array($name, DONT_INDEX_COLLECTIONS)) continue;

    foreach ($pages as $page) {
        $tags = $page->getTags();
        $lang = $page->lang;
        $content = $page->getContent();

        if ($use_files_from_args) {
            $redirect_url = file_path_to_url($filename);
            $articles_index->deleteBy([
                'filters' => "redirect:'$redirect_url'"
            ]);
        }

        $all_used_tags = array_merge($tags, $all_used_tags);
    }
};

$header = $crawler->filter('h1[id]')->getNode(0);
$last_parent_id = $header->attributes['id']->textContent;
$collected_data = array();
$collected_data[$last_parent_id] = create_agolia_article_object(
    $header->textContent,
    $last_parent_id,
    $filename,
    $tags
);
$parsed_lines = [];

read_dom_depth(
    $header,
    function (
        $el,
        $is_child
    ) use (
        $last_parent_id,
        &$collected_data,
        $filename,
        $tags,
        &$parsed_lines,
        $extended_exclude_fn
    ) {
        $attributes = $el->attributes;
        $id =  $attributes && $attributes->getNamedItem('id') ?
            $attributes->getNamedItem('id')->textContent : null;
        $last_parent_with_id = find_last_parent_with_id($el, $extended_exclude_fn);
        $last_parent_id = $last_parent_with_id->attributes['id']->textContent ?? $last_parent_id;

        $unique_id = $el->getLineNo() . md5($el->textContent);
        $parsed = in_array($unique_id, $parsed_lines);
        if ($parsed || any_parent_have_element_name($el, 'summary')) {
            return true;
        }

        if (!array_key_exists($last_parent_id, $collected_data)) {
            $collected_data[$last_parent_id] = create_agolia_article_object(
                build_title_path($el, $extended_exclude_fn),
                $last_parent_id,
                $filename,
                $tags
            );
        }

        if (any_parent_have_element_name($el, 'details')) {
            $tag = $el->tagName ?? null;
            if (strpos($tag, 'summary') !== false) {
                $parsed_lines[] = $unique_id;
                return true;
            }
        }

        if ($id !== null) {
            $last_collected_data = $collected_data[$last_parent_id] ?? [];
            if ($last_collected_data && $last_collected_data['content']) {
                $last_collected_data['content'] = preg_replace(
                    '/\s+/',
                    ' ',
                    $last_collected_data['content']
                );
            }
        }
        $parent_node = $el->parentNode ?? null;
        $parent_node_attributes = $parent_node->attributes ?? null;
        $parent_node_id = $parent_node_attributes && $parent_node_attributes->getNamedItem('id')  ?
            $parent_node_attributes->getNamedItem('id')->textContent : null;

        if ($id === null && $is_child && strlen(trim($el->textContent)) > 0 && $parent_node_id === null) {
            $collected_data[$last_parent_id]['content'] .= $el->textContent;
            $parsed_lines[] = $unique_id;
        }
        return true;
    },
    $exclude_fn
);

$non_empty_data = array_filter(array_values($collected_data), function ($obj) {
    return strlen($obj['content']) !== 0;
});

try {
    $articles_index->saveObjects($non_empty_data, [
        'autoGenerateObjectIDIfNotExist' => true
    ]);
} catch (BadRequestException $e) {
    var_dump($collected_data);
}

$tags_objects = array();
foreach (array_unique($all_used_tags) as $tag) {
    $tags_objects[] = array(
        'name' => $tag
    );
}

$tags_index->saveObjects($tags_objects, [
    'objectIDKey' => 'name'
]);
