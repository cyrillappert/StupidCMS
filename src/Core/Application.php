<?php

declare(strict_types=1);

namespace StupidCMS\Core;

use StupidCMS\Http\{Router};
use StupidCMS\Http\Controllers\PageController;

class Application
{
    public function run(): void
    {
        try {
            $pageController = new PageController();
            $router = new Router($pageController);
            $router->run();
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }
    
    private function handleError(\Throwable $e): void
    {
        http_response_code(500);
        echo 'Internal Server Error';
        
        error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    }
}