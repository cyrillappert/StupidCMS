<?php

declare(strict_types=1);

namespace StupidCMS\Http\Controllers;

use StupidCMS\Content\{Content, ContentProxy};

class PageController extends BaseController
{
    public function show(string $slug): string
    {
        $content = $this->contentService->getContentBySlug($slug);

        if (!$content || !$content->isPublished()) {
            return $this->notFound();
        }

        $template = $this->getTemplate($content);
        $foo = new ContentProxy($content, $this->contentService);

        $templateData = [
            'foo' => $foo, 
            'currentSlug' => $slug
        ];

        // For project templates, add the projects list for navigation
        if ($template === 'project') {
            $workContent = $this->contentService->getContentBySlug('work');
            $templateData['projects'] = $workContent ? $this->contentService->getChildren('work') : [];
        }

        return $this->renderTemplate($template, $templateData);
    }

    private function getTemplate(Content $content): string
    {
        $template = $content->getTemplate();
        return $this->templateEngine->exists($template) ? $template : 'index';
    }
}