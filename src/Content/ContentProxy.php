<?php

declare(strict_types=1);

namespace StupidCMS\Content;

class ContentProxy
{
    private ContentService $contentService;
    private array $children = [];
    private array $images = [];
    private bool $childrenLoaded = false;
    private bool $imagesLoaded = false;

    public function __construct(
        private Content $content,
        ContentService $contentService
    ) {
        $this->contentService = $contentService;
    }

    public function __get(string $name)
    {
        return match($name) {
            'children' => $this->getChildren(),
            'images' => $this->getImages(),
            'contentService' => $this->contentService,
            default => $this->content->__get($name)
        };
    }

    public function __isset(string $name): bool
    {
        return in_array($name, ['children', 'images', 'contentService']) || $this->content->__isset($name);
    }

    public function __call(string $method, array $args)
    {
        if ($method === 'root') {
            return $this->getRoot();
        }
        
        // Handle special method calls
        if ($method === 'children') {
            return $this->getChildren();
        }
        
        // Handle dynamic child content access
        $childContent = $this->contentService->getContentBySlug($method);
        if ($childContent) {
            return new ContentProxy($childContent, $this->contentService);
        }
        
        // Try with current slug as prefix
        $fullSlug = $this->content->getSlug() . '/' . $method;
        $childContent = $this->contentService->getContentBySlug($fullSlug);
        if ($childContent) {
            return new ContentProxy($childContent, $this->contentService);
        }
        
        return $this->content->$method(...$args);
    }

    public function root(): ContentProxy
    {
        return $this->getRoot();
    }

    private function getChildren(): array
    {
        if (!$this->childrenLoaded) {
            $this->children = $this->contentService->getChildren($this->content->getSlug());
            $this->childrenLoaded = true;
        }
        return $this->children;
    }

    private function getImages(): array
    {
        if (!$this->imagesLoaded) {
            $this->images = $this->contentService->getImages($this->content->getSlug());
            $this->imagesLoaded = true;
        }
        return $this->images;
    }

    private function getRoot(): ContentProxy
    {
        $rootContent = $this->contentService->getContentBySlug('index');
        return new ContentProxy($rootContent, $this->contentService);
    }
}