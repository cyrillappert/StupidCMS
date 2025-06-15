<?php

declare(strict_types=1);

namespace StupidCMS\Content;

use StupidCMS\Util\FileLoader;

class ContentService
{
    private ContentBuilder $builder;
    private FileLoader $fileLoader;
    private string $contentDir;
    private array $imageExtensions;

    public function __construct(
        ?ContentBuilder $builder = null,
        ?FileLoader $fileLoader = null
    ) {
        $this->contentDir = dirname(__DIR__, 2) . '/content';
        $this->imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $this->builder = $builder ?? new ContentBuilder($this->contentDir);
        $this->fileLoader = $fileLoader ?? new FileLoader();
    }

    public function getContentBySlug(string $slug): ?Content
    {
        return $this->builder->build($slug);
    }

    public function getChildren(string $slug): array
    {
        $directory = $slug === '' 
            ? $this->contentDir 
            : $this->contentDir . '/' . $slug;
            
        if (!is_dir($directory)) return [];

        $children = [];
        foreach ($this->fileLoader->findDirectories($directory) as $dir) {
            $childSlug = basename($dir);
            if (!$this->fileLoader->hasContent($dir)) continue;
            
            $fullSlug = $slug === '' ? $childSlug : "{$slug}/{$childSlug}";
            $child = $this->getContentBySlug($fullSlug);
            $title = $child->title ?? ucfirst($childSlug);
            
            $children[] = [
                'slug' => $fullSlug,
                'url' => '/' . $fullSlug,
                'title' => $title,
                'name' => $childSlug
            ];
        }

        return $children;
    }

    public function getImages(string $slug): array
    {
        $directory = $slug === '' 
            ? $this->contentDir 
            : $this->contentDir . '/' . $slug;
            
        if (!is_dir($directory)) return [];

        $images = [];

        foreach ($this->imageExtensions as $ext) {
            $files = glob("{$directory}/*.{$ext}", GLOB_BRACE | GLOB_NOCHECK);
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $images[] = "/media/" . basename($file);
                }
            }
        }

        return $images;
    }
}