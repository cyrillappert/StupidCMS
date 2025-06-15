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

        return $this->renderTemplate($template, [
            'foo' => $foo, 
            'currentSlug' => $slug
        ]);
    }

    private function getTemplate(Content $content): string
    {
        $template = $content->getTemplate();
        return $this->templateEngine->exists($template) ? $template : 'index';
    }
}