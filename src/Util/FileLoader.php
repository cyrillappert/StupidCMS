<?php

declare(strict_types=1);

namespace StupidCMS\Util;

use Symfony\Component\Yaml\Yaml;

class FileLoader
{
    public function loadYaml(string $path): array
    {
        if (!file_exists($path)) return [];
        $content = file_get_contents($path);
        return Yaml::parse($content) ?? [];
    }

    public function loadMarkdown(string $path): string
    {
        return file_exists($path) ? file_get_contents($path) : '';
    }

    public function findYamlFiles(string $dir): array
    {
        return glob("{$dir}/*.yaml") ?: [];
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
        return !empty($this->findYamlFiles($dir)) || !empty($this->findMarkdownFiles($dir));
    }
}