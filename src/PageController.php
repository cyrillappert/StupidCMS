<?php

declare(strict_types=1);

namespace StupidCMS;

use StupidCMS\{ContentService, ContentModel, TemplateEngine};

class PageController
{
    private ContentService $contentService;
    private TemplateEngine $templateEngine;

    public function __construct()
    {
        $this->contentService = new ContentService();
        $this->templateEngine = new TemplateEngine();
    }

    public function show(string $path): string
    {
        $content = $this->contentService->load($path);

        if (!$content || !$content->published) {
            return $this->notFound();
        }

        return $this->renderContent($content);
    }

    private function renderContent(ContentModel $content): string
    {
        $template = $this->determineTemplate($content);

        return $this->templateEngine->render($template, [
            'foo' => $content
        ]);
    }

    private function determineTemplate(ContentModel $content): string
    {
        $filename = $content->markdown_filename ?: 'index';

        // Try template matching the markdown filename
        if ($this->templateEngine->exists($filename)) {
            return $filename;
        }

        // Fallback to default
        return 'default';
    }

    private function notFound(): string
    {
        http_response_code(404);

        // Create a minimal content object for 404 page
        $notFoundContent = new ContentModel([
            'title' => '404',
        ]);

        // Set the content service so root() method works
        $notFoundContent->setContentService($this->contentService);

        return $this->templateEngine->render('404', [
            'foo' => $notFoundContent,
            'message' => 'Not found'
        ]);
    }
}
