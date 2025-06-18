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
            $this->resizeAndCopy($sourcePath, $destPath);
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
            $this->resizeAndCopy($sourcePath, $destPath);
        }

        return "![$alt](/media/$filename)";
    }

    private function addSizeClassFromAlt(array $matches): string
    {
        [$full, $before, $size, $cleanAlt, $after] = $matches;

        $class = ' class="img-' . $size . '"';
        return '<img' . $before . 'alt="' . $cleanAlt . '"' . $class . $after . '>';
    }

    private function resizeAndCopy(string $sourcePath, string $destPath): void
    {
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            copy($sourcePath, $destPath);
            return;
        }

        [$width, $height, $type] = $imageInfo;
        $maxDimension = 1080;

        // Skip resize if already within limits
        if ($width <= $maxDimension && $height <= $maxDimension) {
            copy($sourcePath, $destPath);
            return;
        }

        // Calculate new dimensions
        if ($width > $height) {
            $newWidth = $maxDimension;
            $newHeight = intval($height * ($maxDimension / $width));
        } else {
            $newHeight = $maxDimension;
            $newWidth = intval($width * ($maxDimension / $height));
        }

        // Create source image
        $sourceImage = match($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
            IMAGETYPE_GIF => imagecreatefromgif($sourcePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($sourcePath),
            default => null
        };

        if (!$sourceImage) {
            copy($sourcePath, $destPath);
            return;
        }

        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefill($newImage, 0, 0, $transparent);
        }

        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save resized image
        $success = match($type) {
            IMAGETYPE_JPEG => imagejpeg($newImage, $destPath, 85),
            IMAGETYPE_PNG => imagepng($newImage, $destPath, 6),
            IMAGETYPE_GIF => imagegif($newImage, $destPath),
            IMAGETYPE_WEBP => imagewebp($newImage, $destPath, 85),
            default => false
        };

        imagedestroy($sourceImage);
        imagedestroy($newImage);

        if (!$success) {
            copy($sourcePath, $destPath);
        }
    }
}
