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

        $template = $this->templateEngine->exists($content->template) ? $content->template : 'index';
        
        $templateData = [
            'foo' => $content, 
            'currentSlug' => $slug
        ];

        // For project templates, add the projects list for navigation
        if ($template === 'project') {
            $templateData['projects'] = $this->simpleContent->getChildren('work');
        }

        return $this->renderTemplate($template, $templateData);
    }
}