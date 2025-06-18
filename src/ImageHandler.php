<?php

declare(strict_types=1);

namespace StupidCMS;

class ImageHandler
{
    private string $mediaDir;

    public function __construct()
    {
        $this->mediaDir = dirname(__DIR__) . '/public/media';
        if (!is_dir($this->mediaDir)) {
            mkdir($this->mediaDir, 0755, true);
        }
    }

    public function processInMarkdown(string $markdown, string $contentDir): string
    {
        return preg_replace_callback(
            '/!\[([^\]]*)\]\(([^)\s]+)(?:\s+"[^"]*")?\)/',
            fn($matches) => $this->handleImageMatch($matches, $contentDir),
            $markdown
        );
    }

    public function addSizeClasses(string $html): string
    {
        // Extract size from alt attribute and add class directly
        $html = preg_replace_callback(
            '/<img([^>]+)alt="(large|medium|small):([^"]*)"([^>]*)>/i',
            fn($matches) => $this->addSizeClassFromAlt($matches),
            $html
        );

        // Remove paragraph wrappers around images
        $html = preg_replace('/<p>(<img[^>]*>)<\/p>/', '$1', $html);

        // Wrap consecutive images
        $html = preg_replace('/(<img[^>]*>\s*)+/', '<div class="image-group">$0</div>', $html);

        return $html;
    }

    public function process(string $imagePath, string $contentDir): ?string
    {
        $sourcePath = $contentDir . '/' . $imagePath;
        if (!file_exists($sourcePath)) return null;

        $filename = basename($imagePath);
        $destPath = $this->mediaDir . '/' . $filename;

        if (!file_exists($destPath) || filemtime($sourcePath) > filemtime($destPath)) {
            copy($sourcePath, $destPath);
        }

        return "/media/$filename";
    }

    private function handleImageMatch(array $matches, string $contentDir): string
    {
        [$full, $alt, $path] = $matches;

        $sourcePath = $contentDir . '/' . $path;
        if (!file_exists($sourcePath)) return $full;

        $filename = basename($path);
        $destPath = $this->mediaDir . '/' . $filename;

        if (!file_exists($destPath) || filemtime($sourcePath) > filemtime($destPath)) {
            copy($sourcePath, $destPath);
        }

        return "![$alt](/media/$filename)";
    }

    private function addSizeClassFromAlt(array $matches): string
    {
        [$full, $before, $size, $cleanAlt, $after] = $matches;

        $class = ' class="img-' . $size . '"';
        return '<img' . $before . 'alt="' . $cleanAlt . '"' . $class . $after . '>';
    }
}
