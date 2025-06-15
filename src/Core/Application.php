<?php

declare(strict_types=1);

namespace StupidCMS\Core;

use StupidCMS\Content\{ContentBuilder, ContentService};
use StupidCMS\Http\{Router};
use StupidCMS\Http\Controllers\{PageController, ProjectController};
use StupidCMS\Template\TemplateEngine;
use StupidCMS\Util\{FileLoader, FieldProcessor, ImageHandler, MarkdownParser};

class Application
{
    private Container $container;
    private Config $config;
    
    public function __construct()
    {
        $this->config = Config::getInstance();
        $this->container = new Container();
        $this->registerServices();
    }
    
    public function run(): void
    {
        try {
            $router = $this->container->get('router');
            $router->run();
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }
    
    public function getContainer(): Container
    {
        return $this->container;
    }
    
    private function registerServices(): void
    {
        // Core services
        $this->container->registerSingleton('config', fn() => $this->config);
        
        // Utility services
        $this->container->registerSingleton('file_loader', fn() => new FileLoader());
        $this->container->registerSingleton('field_processor', fn() => new FieldProcessor());
        $this->container->registerSingleton('image_handler', fn() => new ImageHandler());
        $this->container->registerSingleton('markdown_parser', fn($c) => 
            new MarkdownParser($c->get('image_handler'))
        );
        
        // Template services
        $this->container->registerSingleton('template_engine', fn($c) => 
            new TemplateEngine($c->get('config'), $c->get('markdown_parser'))
        );
        
        // Content services
        $this->container->registerSingleton('content_builder', fn($c) => 
            new ContentBuilder($c->get('config')->get('content_dir'))
        );
        $this->container->registerSingleton('content_service', fn($c) => 
            new ContentService($c->get('content_builder'))
        );
        
        // Controllers
        $this->container->registerSingleton('page_controller', fn($c) => 
            new PageController(
                $c->get('content_service'),
                $c->get('template_engine')
            )
        );
        $this->container->registerSingleton('project_controller', fn($c) => 
            new ProjectController(
                $c->get('content_service'),
                $c->get('template_engine')
            )
        );
        
        // HTTP services
        $this->container->registerSingleton('router', fn($c) => 
            new Router(
                $c->get('page_controller'),
                $c->get('project_controller')
            )
        );
    }
    
    private function handleError(\Throwable $e): void
    {
        if ($this->config->get('debug')) {
            echo '<pre>' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>';
        } else {
            http_response_code(500);
            echo 'Internal Server Error';
        }
        
        error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    }
}