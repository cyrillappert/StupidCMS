<?php

declare(strict_types=1);

namespace StupidCMS\Http\Controllers;

use StupidCMS\SimpleContent;

class PageController extends BaseController
{
    private SimpleContent $simpleContent;

    public function __construct()
    {
        parent::__construct();
        $this->simpleContent = new SimpleContent();
    }

    public function show(string $slug): string
    {
        $content = $this->simpleContent->load($slug);

        if (!$content || !$content->published) {
            return $this->notFound();
        }

        return $content->render();
    }
}