<?php

declare(strict_types=1);

namespace StupidCMS;

use StupidCMS\{Router, PageController};

class Application
{
    public function run(): void
    {
        $pageController = new PageController();
        $router = new Router($pageController);
        $router->run();
    }
}
