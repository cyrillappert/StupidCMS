<?php

declare(strict_types=1);

namespace StupidCMS\Http;

use StupidCMS\Http\Controllers\{PageController, ProjectController};

class Router
{
    private array $routes = [];

    public function __construct(
        private PageController $pageController,
        private ProjectController $projectController
    ) {
        $this->registerRoutes();
    }

    public function get(string $path, callable $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = trim(parse_url($requestUri, PHP_URL_PATH) ?: '', '/');
        $path = '/' . $uri;

        // Check for exact route matches first
        if (isset($this->routes[$method][$path])) {
            echo $this->routes[$method][$path]();
            return;
        }

        // Fall back to content-based routing
        echo $this->pageController->show($uri === '' ? 'index' : $uri);
    }

    private function registerRoutes(): void
    {
        $this->get('/work/project', [$this->projectController, 'show']);
    }
}