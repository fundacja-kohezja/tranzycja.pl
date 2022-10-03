<?php

namespace App;

use Closure;
use Illuminate\Support\{Collection, Str};
use TightenCo\Jigsaw\PageVariable;

/**
 * Helper class for building algolia records from properly sectioned content
 */
class SearchRecordsBuilder
{
    protected $lastHeadingLevel = 0;
    protected $openContainers = [];

    protected Collection $sections;
    protected Collection $records;
    protected array $addToEach;

    protected function __construct($page, $addToEachSection)
    {
        $this->sections = $page->getContent();
        $this->records = collect();
        $this->addToEach = $addToEachSection;
    }

    public static function build(PageVariable $page, $addToEachSection = [])
    {
        $builder = new static($page, $addToEachSection);
        $builder->buildRecords();
        
        return $builder->records
            ->reject(fn($r) => $r->get('flag') === 'remove')
            ->values() // reindex after removing flagged items
            ->each->forget('flag')
            ->toArray();
    }

    protected function buildRecords()
    {
        foreach ($this->sections as $index => $section) {

            switch ($section->level) {
                case 'open':
                    $this->openContainers[] = $index;

                    /* flag last record to continue after closing the container */
                    $this->records->last()['flag'] = 'continue';

                    /* containers should be in between headings in hierarchy to build paths properly */
                    $this->lastHeadingLevel = floor($this->lastHeadingLevel) + 0.5;
                    break;

                case 'close':
                    array_pop($this->openContainers);
                    break;

                default:
                    $this->lastHeadingLevel = $section->level;
            }

            if (isset($section->slug)) { // if section has a slug, it starts a new record
                $this->addRecord($section, $index);

            } else {
                /*
                 * if section has no slug, it doesn't start a new record
                 * so content of current section is going to get appended to previous record
                 */

                if ($recordToContinue = $this->records->last(fn($r) => $r->get('flag') === 'continue')) { // search for last record with 'continue' flag

                    /*
                     * Continued record must be last (otherwise further continuation might be appended to wrong record)
                     * so it needs to be removed from collection and brought back at the end.
                     * 
                     * Since we don't know the index of the record at this point we can't remove it now so we flag it
                     * to be removed later
                     */
                    $continuedRecord = clone $recordToContinue;
                    $recordToContinue['flag'] = 'remove';

                    /*
                     * append the content from currect section and push the record back to the collection
                     * so it lands at the end
                     */
                    $continuedRecord['content'] .= $section->content;
                    $this->records[] = $continuedRecord;

                } elseif ($lastRecord = $this->records->last()) { // no records with 'continue' flag, grab the last record
                    $lastRecord['content'] .= $section->content;

                } else { // no previous records at all, this can happen only if page starts with paragraph instead of heading
                    $this->addRecord($section, $index);
                }
            }
            
        }
    }

    protected function addRecord($section, $index)
    {
        $addtionalData = array_map(
            fn($item) => $item instanceof Closure ? $item($section) : $item,
            $this->addToEach
        );

        $this->records[] = collect([
            'path' => $this->buildTitlePath($section->title ?? Str::limit($section->content, 30), $index),
            'content' => $section->content,
            'section' => $section->slug ?? ''
        ] + $addtionalData);
    }

    protected function buildTitlePath($title, $currentIndex)
    {
        $level = $this->lastHeadingLevel;
        $prevSections = $this->sections->take($currentIndex);

        foreach ($prevSections->reverse() as $index => $section) {

            if (isset($section->title)) {

                $isParentHeading = is_numeric($section->level) && $section->level < $level;
                $isInside = in_array($index, $this->openContainers);

                if ($isParentHeading || $isInside) {
                    $title = "$section->title-->$title";
                }

                if ($isParentHeading) {
                    $level = $section->level;
                }
            }
        }

        return $title;
    }
}