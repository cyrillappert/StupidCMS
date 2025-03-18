<?php

declare(strict_types=1);

namespace StupidCMS\Core;

class Config
{
    private static ?Config $instance = null;
    private array $config = [];
    
    private function __construct()
    {
        $this->loadDefaults();
        $this->loadEnvironment();
    }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
    
    public function set(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }
    
    public function has(string $key): bool
    {
        return isset($this->config[$key]);
    }
    
    private function loadDefaults(): void
    {
        $rootDir = dirname(__DIR__, 2);
        
        $this->config = [
            'root_dir' => $rootDir,
            'content_dir' => $rootDir . '/content',
            'template_dir' => $rootDir . '/templates',
            'public_dir' => $rootDir . '/public',
            'media_dir' => $rootDir . '/public/media',
            'cache_enabled' => false,
            'debug' => false,
            'default_template' => 'index',
            'image_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
            'markdown_extensions' => ['md', 'markdown', 'txt'],
        ];
    }
    
    private function loadEnvironment(): void
    {
        $envMappings = [
            'STUPID_DEBUG' => 'debug',
            'STUPID_CACHE' => 'cache_enabled',
            'STUPID_CONTENT_DIR' => 'content_dir',
            'STUPID_TEMPLATE_DIR' => 'template_dir',
        ];
        
        foreach ($envMappings as $envKey => $configKey) {
            $value = $_ENV[$envKey] ?? null;
            if ($value !== null) {
                $this->config[$configKey] = match($configKey) {
                    'debug', 'cache_enabled' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                    default => $value
                };
            }
        }
    }
}