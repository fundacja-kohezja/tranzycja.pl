<?php

namespace App;

use Illuminate\Container\Container;
use TightenCo\Jigsaw\{
    Handlers\MarkdownHandler,
    PageData
};

class CustomMdHandler extends MarkdownHandler
{
    public function handleCollectionItem($file, PageData $pageData)
    {
        $this->putPageDataInContainer($pageData);

        return parent::handleCollectionItem($file, $pageData);
    }

    public function handle($file, $pageData)
    {
        $this->putPageDataInContainer($pageData);

        return parent::handle($file, $pageData);
    }

    protected function putPageDataInContainer($pageData)
    {
        Container::getInstance()->bind('page', fn() => $pageData->page);
    }
}
