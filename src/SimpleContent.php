<?php

declare(strict_types=1);

namespace StupidCMS;

use StupidCMS\Util\{FileLoader, MarkdownParser, ImageHandler};

class SimpleContent
{
    private string $contentDir;
    private string $templateDir;
    private FileLoader $fileLoader;
    private MarkdownParser $markdownParser;
    private ImageHandler $imageHandler;

    public function __construct(
        ?string $contentDir = null,
        ?string $templateDir = null
    ) {
        $this->contentDir = rtrim($contentDir ?? dirname(__DIR__) . '/content', '/');
        $this->templateDir = $templateDir ?? dirname(__DIR__) . '/templates';
        $this->fileLoader = new FileLoader();
        $this->imageHandler = new ImageHandler();
        $this->markdownParser = new MarkdownParser($this->imageHandler);
    }

    public function load(string $slug): ?SimpleContentWrapper
    {
        $markdownPath = $this->resolveMarkdownPath($slug);
        if (!$markdownPath) return null;

        $parsed = $this->fileLoader->loadMarkdownWithFrontmatter($markdownPath);
        $meta = $parsed['meta'];
        $directory = dirname($markdownPath);
        $body = $this->markdownParser->parse($parsed['content'], $directory);

        // Process image fields
        foreach ($meta as $key => $value) {
            if (str_ends_with($key, '_img') && is_string($value)) {
                $meta[$key] = $this->imageHandler->process($value, $directory);
            } elseif (is_array($value) && isset($value['type']) && $value['type'] === 'img' && isset($value['src'])) {
                $processed = $this->imageHandler->process($value['src'], $directory);
                if ($processed) $value['src'] = $processed;
                $meta[$key] = $value;
            }
        }

        // Add defaults
        $meta['title'] = $meta['title'] ?? ucfirst($slug ?: 'Home');

        $data = array_merge($meta, [
            'slug' => $slug,
            'body' => $body,
            'published' => $meta['published'] ?? false
        ]);

        return new SimpleContentWrapper($data, $this);
    }

    public function getChildren(string $slug): array
    {
        $directory = $slug === '' ? $this->contentDir : $this->contentDir . '/' . $slug;
        if (!is_dir($directory)) return [];

        $children = [];
        foreach ($this->fileLoader->findDirectories($directory) as $dir) {
            $childSlug = basename($dir);
            if (!$this->fileLoader->hasContent($dir)) continue;
            
            $fullSlug = $slug === '' ? $childSlug : "{$slug}/{$childSlug}";
            $child = $this->load($fullSlug);
            if (!$child || !$child->published) continue;
            
            $children[] = [
                'slug' => $fullSlug,
                'url' => '/' . $fullSlug,
                'title' => $child->title,
                'name' => $childSlug
            ];
        }

        return $children;
    }

    private function resolveMarkdownPath(string $slug): ?string
    {
        if ($slug === '') {
            $markdownFiles = $this->fileLoader->findMarkdownFiles($this->contentDir);
            return !empty($markdownFiles) ? $markdownFiles[0] : null;
        }

        $directPath = "{$this->contentDir}/{$slug}.md";
        if (file_exists($directPath)) return $directPath;
        
        $dir = "{$this->contentDir}/{$slug}";
        $markdownFiles = $this->fileLoader->findMarkdownFiles($dir);
        return !empty($markdownFiles) ? $markdownFiles[0] : null;
    }

}

class SimpleContentWrapper
{
    private array $data;
    private SimpleContent $simpleContent;

    public function __construct(array $data, SimpleContent $simpleContent)
    {
        $this->data = $data;
        $this->simpleContent = $simpleContent;
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

    public function root(): SimpleContentWrapper
    {
        return $this->simpleContent->load('');
    }

    public function children(): array
    {
        return $this->simpleContent->getChildren($this->data['slug'] ?? '');
    }

    public function render(): string
    {
        $templateEngine = new \StupidCMS\Template\TemplateEngine();
        $template = $this->determineTemplate();
        
        return $templateEngine->render($template, [
            'foo' => $this,
            'currentSlug' => $this->data['slug'] ?? ''
        ]);
    }

    private function determineTemplate(): string
    {
        $slug = $this->data['slug'] ?? '';
        $filename = basename($slug) ?: 'index';
        
        $templateEngine = new \StupidCMS\Template\TemplateEngine();
        
        // Try template matching the filename
        if ($templateEngine->exists($filename)) {
            return $filename;
        }
        
        // Fallback to default
        return 'default';
    }

    public function __call(string $method, array $args): ?SimpleContentWrapper
    {
        // Handle dynamic content loading like $foo->work()
        $currentSlug = $this->data['slug'] ?? '';
        $childSlug = $currentSlug ? "{$currentSlug}/{$method}" : $method;
        return $this->simpleContent->load($childSlug);
    }
}