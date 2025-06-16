<?php

declare(strict_types=1);

namespace StupidCMS;

use Symfony\Component\Yaml\Yaml;

class FileLoader
{

    public function loadMarkdownWithFrontmatter(string $path): array
    {
        if (!file_exists($path)) {
            return ['meta' => [], 'content' => ''];
        }

        $content = file_get_contents($path);
        
        if (!str_starts_with($content, '---')) {
            return ['meta' => [], 'content' => $content];
        }

        // Find the closing --- 
        $lines = explode("\n", $content);
        $frontmatterEnd = -1;
        
        for ($i = 1; $i < count($lines); $i++) {
            if (trim($lines[$i]) === '---') {
                $frontmatterEnd = $i;
                break;
            }
        }
        
        if ($frontmatterEnd === -1) {
            return ['meta' => [], 'content' => $content];
        }
        
        $frontmatterLines = array_slice($lines, 1, $frontmatterEnd - 1);
        $markdownLines = array_slice($lines, $frontmatterEnd + 1);
        
        $frontmatter = implode("\n", $frontmatterLines);
        $markdownContent = implode("\n", $markdownLines);
        
        $meta = [];
        if (!empty($frontmatter)) {
            try {
                $meta = Yaml::parse($frontmatter) ?? [];
            } catch (Exception $e) {
                // If YAML parsing fails, return the content as-is
                return ['meta' => [], 'content' => $content];
            }
        }

        return ['meta' => $meta, 'content' => $markdownContent];
    }

    public function findMarkdownFiles(string $dir): array
    {
        return glob("{$dir}/*.md") ?: [];
    }

    public function findDirectories(string $dir): array
    {
        return glob("{$dir}/*", GLOB_ONLYDIR) ?: [];
    }

    public function hasContent(string $dir): bool
    {
        return !empty($this->findMarkdownFiles($dir));
    }
}