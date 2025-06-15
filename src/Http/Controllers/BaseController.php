<?php

declare(strict_types=1);

namespace StupidCMS\Http\Controllers;

use StupidCMS\Template\TemplateEngine;

abstract class BaseController
{
    public function __construct(
        protected ?TemplateEngine $templateEngine = null
    ) {
        $this->templateEngine = $templateEngine ?? new TemplateEngine();
    }

    protected function renderTemplate(string $template, array $data = []): string
    {
        return $this->templateEngine->render($template, $data);
    }

    protected function notFound(string $message = 'Not found'): string
    {
        http_response_code(404);
        return $this->renderTemplate('404', ['message' => $message]);
    }
}