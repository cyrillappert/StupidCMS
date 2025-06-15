<?php

declare(strict_types=1);

namespace StupidCMS\Http\Controllers;

use StupidCMS\{SimpleContent, Template\TemplateEngine};

class PageController
{
    private SimpleContent $simpleContent;
    private TemplateEngine $templateEngine;

    public function __construct()
    {
        $this->simpleContent = new SimpleContent();
        $this->templateEngine = new TemplateEngine();
    }

    public function show(string $slug): string
    {
        $content = $this->simpleContent->load($slug);

        if (!$content || !$content->published) {
            return $this->notFound();
        }

        return $content->render();
    }

    private function notFound(): string
    {
        http_response_code(404);
        return $this->templateEngine->render('404', ['message' => 'Not found']);
    }
}