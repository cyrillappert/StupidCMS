<?php

declare(strict_types=1);

namespace StupidCMS\Core;

use StupidCMS\Http\{Router};
use StupidCMS\Http\Controllers\PageController;

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
        // Controllers
        $pageController = new PageController();
        
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