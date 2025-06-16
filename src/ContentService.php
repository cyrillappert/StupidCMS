<?php

declare(strict_types=1);

namespace StupidCMS;

use StupidCMS\FileLoader;
use StupidCMS\MarkdownParser;
use StupidCMS\ImageHandler;
use StupidCMS\ContentModel;

class ContentService
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

    public function load(string $path): ?ContentModel
    {
        $markdownPath = $this->resolveMarkdownPath($path);
        if (!$markdownPath) return null;

        $parsed = $this->fileLoader->loadMarkdownWithFrontmatter($markdownPath);
        $meta = $parsed['meta'];
        $directory = dirname($markdownPath);
        $body = $this->markdownParser->parse($parsed['content'], $directory);
        
        // Add current date to file if missing
        if (!isset($meta['date'])) {
            $meta['date'] = date('Y-m-d');
            $this->addDateToMarkdownFile($markdownPath, $meta['date']);
        }

        // Process image fields
        foreach ($meta as $key => $value) {
            if (is_array($value) && isset($value['type']) && $value['type'] === 'img' && isset($value['src'])) {
                $processed = $this->imageHandler->process($value['src'], $directory);
                if ($processed) $value['src'] = $processed;
                $meta[$key] = $value;
            }
        }

        // Add defaults
        $meta['title'] = $meta['title'] ?? ucfirst(basename($path) ?: 'Home');

        $markdownFileName = pathinfo($markdownPath, PATHINFO_FILENAME);
        
        $data = array_merge($meta, [
            'path' => $path,
            'slug' => $path === '' ? 'index' : basename($path),
            'markdown_filename' => $markdownFileName,
            'body' => $body,
            'published' => $meta['published'] ?? false
        ]);

        $model = new ContentModel($data);
        $model->setContentService($this);
        return $model;
    }

    public function getChildren(string $path): array
    {
        $directory = $path === '' ? $this->contentDir : $this->contentDir . '/' . $path;
        if (!is_dir($directory)) return [];

        $children = [];
        foreach ($this->fileLoader->findDirectories($directory) as $dir) {
            $childSlug = basename($dir);
            if (!$this->fileLoader->hasContent($dir)) continue;

            $fullPath = $path === '' ? $childSlug : "{$path}/{$childSlug}";
            $child = $this->load($fullPath);
            if (!$child || !$child->published) continue;

            $children[] = $child;
        }

        // Sort by date (newest first)
        usort($children, function($a, $b) {
            $dateA = $a->date ?? 0;
            $dateB = $b->date ?? 0;
            
            // Convert to timestamp if string
            if (!is_numeric($dateA)) $dateA = strtotime($dateA) ?: 0;
            if (!is_numeric($dateB)) $dateB = strtotime($dateB) ?: 0;
            
            return $dateB - $dateA; // Newest first
        });

        return $children;
    }

    public function getRoot(): ?ContentModel
    {
        return $this->load('');
    }

    public function getChild(string $parentPath, string $childName): ?ContentModel
    {
        $childPath = $parentPath ? "{$parentPath}/{$childName}" : $childName;
        return $this->load($childPath);
    }

    public function getParent(string $path): ?ContentModel
    {
        if ($path === '' || !str_contains($path, '/')) {
            return null; // Root or top-level item has no parent
        }
        
        $parentPath = dirname($path);
        if ($parentPath === '.') {
            $parentPath = '';
        }
        
        return $this->load($parentPath);
    }

    public function getSiblings(string $path): array
    {
        if ($path === '') {
            return []; // Root has no siblings
        }
        
        $parentPath = str_contains($path, '/') ? dirname($path) : '';
        if ($parentPath === '.') {
            $parentPath = '';
        }
        
        return $this->getChildren($parentPath);
    }

    private function resolveMarkdownPath(string $path): ?string
    {
        if ($path === '') {
            $markdownFiles = $this->fileLoader->findMarkdownFiles($this->contentDir);
            return !empty($markdownFiles) ? $markdownFiles[0] : null;
        }

        $directPath = "{$this->contentDir}/{$path}.md";
        if (file_exists($directPath)) return $directPath;

        $dir = "{$this->contentDir}/{$path}";
        $markdownFiles = $this->fileLoader->findMarkdownFiles($dir);
        return !empty($markdownFiles) ? $markdownFiles[0] : null;
    }
    
    private function addDateToMarkdownFile(string $markdownPath, string $date): void
    {
        $content = file_get_contents($markdownPath);
        
        // Check if file has frontmatter
        if (str_starts_with($content, '---')) {
            // Find the end of frontmatter
            $lines = explode("\n", $content);
            $frontmatterEnd = -1;
            
            for ($i = 1; $i < count($lines); $i++) {
                if (trim($lines[$i]) === '---') {
                    $frontmatterEnd = $i;
                    break;
                }
            }
            
            if ($frontmatterEnd > 0) {
                // Insert date before the closing ---
                array_splice($lines, $frontmatterEnd, 0, "date: $date");
                file_put_contents($markdownPath, implode("\n", $lines));
            }
        } else {
            // Add frontmatter with date at the beginning
            $newContent = "---\ndate: $date\n---\n\n" . $content;
            file_put_contents($markdownPath, $newContent);
        }
    }
}