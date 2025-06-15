<?php

declare(strict_types=1);

namespace StupidCMS\Core;

use StupidCMS\Content\{ContentBuilder, ContentService};
use StupidCMS\Http\{Router};
use StupidCMS\Http\Controllers\PageController;
use StupidCMS\Template\TemplateEngine;
use StupidCMS\Util\{FileLoader, FieldProcessor, ImageHandler, MarkdownParser};

class Application
{
    private Router $router;
    
    public function __construct()
    {
        $this->initializeServices();
    }
    
    public function run(): void
    {
        try {
            $this->router->run();
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }
    
    private function initializeServices(): void
    {
        $contentDir = dirname(__DIR__, 2) . '/content';
        $templateDir = dirname(__DIR__, 2) . '/templates';
        
        // Utility services
        $fileLoader = new FileLoader();
        $fieldProcessor = new FieldProcessor();
        $imageHandler = new ImageHandler();
        $markdownParser = new MarkdownParser($imageHandler);
        
        // Content services first
        $contentBuilder = new ContentBuilder(
            $contentDir,
            $templateDir,
            $fileLoader,
            $fieldProcessor,
            $imageHandler,
            $markdownParser
        );
        $contentService = new ContentService($contentBuilder);
        
        // Template services with ContentService
        $templateEngine = new TemplateEngine($templateDir, $markdownParser, $contentService);
        
        // Controllers
        $pageController = new PageController($contentService, $templateEngine);
        
        // HTTP services
        $this->router = new Router($pageController);
    }
    
    private function handleError(\Throwable $e): void
    {
        http_response_code(500);
        echo 'Internal Server Error';
        
        error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    }
}