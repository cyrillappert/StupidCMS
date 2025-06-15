<?php

declare(strict_types=1);

namespace StupidCMS\Content;

use StupidCMS\Core\Config;
use StupidCMS\Util\FileLoader;

class ContentService
{
    private ContentBuilder $builder;
    private FileLoader $fileLoader;

    public function __construct(
        ?ContentBuilder $builder = null,
        ?FileLoader $fileLoader = null
    ) {
        $this->builder = $builder ?? new ContentBuilder(Config::getInstance()->get('content_dir'));
        $this->fileLoader = $fileLoader ?? new FileLoader();
    }

    public function getContentBySlug(string $slug): ?Content
    {
        return $this->builder->build($slug);
    }

    public function getChildren(string $slug): array
    {
        $contentDir = Config::getInstance()->get('content_dir');
        $directory = $slug === '' 
            ? $contentDir 
            : $contentDir . '/' . $slug;
            
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
        $contentDir = Config::getInstance()->get('content_dir');
        $directory = $slug === '' 
            ? $contentDir 
            : $contentDir . '/' . $slug;
            
        if (!is_dir($directory)) return [];

        $extensions = Config::getInstance()->get('image_extensions');
        $images = [];

        foreach ($extensions as $ext) {
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