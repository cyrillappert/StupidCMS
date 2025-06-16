<?php

declare(strict_types=1);

namespace StupidCMS;

class ContentModel
{
    private array $data = [];
    private ?ContentService $contentService = null;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function setContentService(ContentService $contentService): void
    {
        $this->contentService = $contentService;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function getBody(): string
    {
        return $this->data['body'] ?? '';
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function root(): ?ContentModel
    {
        return $this->contentService?->getRoot();
    }

    public function children(): array
    {
        return $this->contentService?->getChildren($this->data['path'] ?? '') ?? [];
    }

    public function child(string $name): ?ContentModel
    {
        $parentPath = $this->data['path'] ?? '';
        return $this->contentService?->getChild($parentPath, $name);
    }

    public function parent(): ?ContentModel
    {
        $path = $this->data['path'] ?? '';
        return $this->contentService?->getParent($path);
    }

    public function siblings(): array
    {
        $path = $this->data['path'] ?? '';
        return $this->contentService?->getSiblings($path) ?? [];
    }

    public function previousSibling(): ?ContentModel
    {
        $siblings = $this->siblings();
        $currentPath = $this->data['path'] ?? '';
        
        $currentIndex = null;
        foreach ($siblings as $index => $sibling) {
            if ($sibling->path === $currentPath) {
                $currentIndex = $index;
                break;
            }
        }
        
        if ($currentIndex === null || $currentIndex === 0) {
            return null; // No previous sibling
        }
        
        return $siblings[$currentIndex - 1];
    }

    public function nextSibling(): ?ContentModel
    {
        $siblings = $this->siblings();
        $currentPath = $this->data['path'] ?? '';
        
        $currentIndex = null;
        foreach ($siblings as $index => $sibling) {
            if ($sibling->path === $currentPath) {
                $currentIndex = $index;
                break;
            }
        }
        
        if ($currentIndex === null || $currentIndex === count($siblings) - 1) {
            return null; // No next sibling
        }
        
        return $siblings[$currentIndex + 1];
    }
    
    public function render(): void
    {
        $templateEngine = new \StupidCMS\TemplateEngine();
        $template = $this->data['template'] ?? $this->data['markdown_filename'] ?? 'default';
        
        // Fall back to default if specific template doesn't exist
        if (!$templateEngine->exists($template)) {
            $template = 'default';
        }
        
        echo $templateEngine->render($template, ['foo' => $this]);
    }
}