<?php

declare(strict_types=1);

namespace StupidCMS\Core;

class Container
{
    private array $services = [];
    private array $singletons = [];
    private array $instances = [];
    
    public function register(string $name, callable $factory): void
    {
        $this->services[$name] = $factory;
    }
    
    public function registerSingleton(string $name, callable $factory): void
    {
        $this->services[$name] = $factory;
        $this->singletons[$name] = true;
    }
    
    public function get(string $name): mixed
    {
        if (isset($this->singletons[$name])) {
            if (!isset($this->instances[$name])) {
                $this->instances[$name] = $this->create($name);
            }
            return $this->instances[$name];
        }
        
        return $this->create($name);
    }
    
    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }
    
    private function create(string $name): mixed
    {
        if (!isset($this->services[$name])) {
            throw new \InvalidArgumentException("Service '{$name}' not found");
        }
        
        return $this->services[$name]($this);
    }
}