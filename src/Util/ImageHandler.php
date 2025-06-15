<?php

declare(strict_types=1);

namespace StupidCMS\Util;

class ImageHandler
{
    private array $sizes = [];
    private string $mediaDir;

    public function __construct()
    {
        $this->mediaDir = dirname(__DIR__, 2) . '/public/media';
        if (!is_dir($this->mediaDir)) {
            mkdir($this->mediaDir, 0755, true);
        }
    }

    public function processInMarkdown(string $markdown, string $contentDir): string
    {
        return preg_replace_callback('/!\[([^\]]*)\]\(([^)\s]+)(?:\s+"[^"]*")?\)/', 
            fn($matches) => $this->handleImageMatch($matches, $contentDir), $markdown);
    }

    public function addSizeClasses(string $html): string
    {
        $html = preg_replace_callback('/<img([^>]+)src="\/media\/([^"]+)"([^>]*)>/i',
            fn($matches) => $this->addSizeClass($matches), $html);
        
        // Add grid classes to paragraphs containing images
        $html = preg_replace_callback('/<p>(<img[^>]*class="img-([^"]*)"[^>]*>)<\/p>/',
            fn($matches) => $this->addParagraphClass($matches), $html);
        
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
        
        if (preg_match('/^(full|large|medium|small):(.*)$/', $alt, $sizeMatches)) {
            [$_, $size, $alt] = $sizeMatches;
            $this->sizes[basename($path)] = $size;
        }

        $sourcePath = $contentDir . '/' . $path;
        if (!file_exists($sourcePath)) return $full;

        $filename = basename($path);
        $destPath = $this->mediaDir . '/' . $filename;

        if (!file_exists($destPath) || filemtime($sourcePath) > filemtime($destPath)) {
            copy($sourcePath, $destPath);
        }

        return "![$alt](/media/$filename)";
    }

    private function addSizeClass(array $matches): string
    {
        [$full, $before, $filename, $after] = $matches;
        
        if (isset($this->sizes[$filename])) {
            $class = ' class="img-' . $this->sizes[$filename] . '"';
            return '<img' . $before . 'src="/media/' . $filename . '"' . $class . $after . '>';
        }
        
        return $full;
    }

    private function addParagraphClass(array $matches): string
    {
        [$full, $imgTag, $size] = $matches;
        
        return "<p class=\"img-$size\">$imgTag</p>";
    }
}