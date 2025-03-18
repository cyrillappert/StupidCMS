<?php

declare(strict_types=1);

namespace StupidCMS\Content;

use StupidCMS\Util\{FileLoader, FieldProcessor, ImageHandler, MarkdownParser};

class ContentBuilder
{
    private string $contentDir;
    private FileLoader $fileLoader;
    private FieldProcessor $fieldProcessor;
    private ImageHandler $imageHandler;
    private MarkdownParser $markdownParser;

    public function __construct(
        string $contentDir,
        ?FileLoader $fileLoader = null,
        ?FieldProcessor $fieldProcessor = null,
        ?ImageHandler $imageHandler = null,
        ?MarkdownParser $markdownParser = null
    ) {
        $this->contentDir = rtrim($contentDir, '/');
        $this->fileLoader = $fileLoader ?? new FileLoader();
        $this->fieldProcessor = $fieldProcessor ?? new FieldProcessor();
        $this->imageHandler = $imageHandler ?? new ImageHandler();
        $this->markdownParser = $markdownParser ?? new MarkdownParser($this->imageHandler);
    }

    public function build(string $slug): ?Content
    {
        $paths = $this->resolvePaths($slug);
        if (!$paths) return null;

        [$yamlPath, $directory] = $paths;
        $meta = $this->fileLoader->loadYaml($yamlPath);
        $markdownContent = $this->loadMarkdownFiles($directory);
        
        $body = $markdownContent['content'] ?? $markdownContent['index'] ?? reset($markdownContent) ?: '';
        $template = basename($yamlPath, '.yaml');
        
        $fields = $this->buildFields($meta, $slug, $directory, $markdownContent);
        
        return new Content(
            body: $body,
            slug: $slug,
            published: $meta['published'] ?? false,
            template: $template,
            customFields: $fields
        );
    }

    private function resolvePaths(string $slug): ?array
    {
        if ($slug === 'index') {
            $yamlPath = "{$this->contentDir}/index.yaml";
            if (file_exists($yamlPath)) return [$yamlPath, $this->contentDir];
            
            $dir = "{$this->contentDir}/index";
            $yamlFiles = $this->fileLoader->findYamlFiles($dir);
            return !empty($yamlFiles) ? [$yamlFiles[0], $dir] : null;
        }

        $yamlPath = "{$this->contentDir}/{$slug}.yaml";
        if (file_exists($yamlPath)) return [$yamlPath, $this->contentDir];
        
        $dir = "{$this->contentDir}/{$slug}";
        $yamlFiles = $this->fileLoader->findYamlFiles($dir);
        return !empty($yamlFiles) ? [$yamlFiles[0], $dir] : null;
    }

    private function loadMarkdownFiles(string $directory): array
    {
        $files = $this->fileLoader->findMarkdownFiles($directory);
        $content = [];
        
        foreach ($files as $file) {
            $name = basename($file, '.md');
            $markdown = $this->fileLoader->loadMarkdown($file);
            $content[$name] = $this->markdownParser->parse($markdown, $directory);
        }
        
        return $content;
    }

    private function buildFields(array $meta, string $slug, string $directory, array $markdownContent): array
    {
        $knownFields = ['published', 'template'];
        $fields = $this->fieldProcessor->extractKnown($meta, $knownFields);
        $fields = $this->fieldProcessor->sanitize($fields);
        $fields = $this->fieldProcessor->addDefaults($fields, $slug);
        $fields = $this->processSpecialFields($fields, $directory);
        
        return array_merge($fields, $markdownContent);
    }

    private function processSpecialFields(array $fields, string $directory): array
    {
        foreach ($fields as $key => $value) {
            if (is_array($value) && isset($value['type'])) {
                $fields[$key] = match($value['type']) {
                    'img' => $this->processImageField($value, $directory),
                    'md' => $this->processMarkdownField($value, $directory, $key),
                    default => $value
                };
            } elseif (str_ends_with($key, '_img') && is_string($value)) {
                $fields[$key] = $this->imageHandler->process($value, $directory);
            }
        }
        return $fields;
    }

    private function processImageField(array $field, string $directory): array
    {
        if (isset($field['src'])) {
            $processed = $this->imageHandler->process($field['src'], $directory);
            if ($processed) $field['src'] = $processed;
        }
        return $field;
    }

    private function processMarkdownField(array $field, string $directory, string $fieldKey = ''): string
    {
        $srcFile = null;
        
        if (isset($field['src'])) {
            $srcFile = $field['src'];
        } else {
            $srcFile = $this->findMarkdownFile($directory, $fieldKey);
        }
        
        if (!$srcFile) return '';
        
        $markdown = $this->fileLoader->loadMarkdown($directory . '/' . $srcFile);
        return $this->markdownParser->parse($markdown, $directory);
    }

    private function findMarkdownFile(string $directory, string $fieldKey): ?string
    {
        $candidates = [];
        
        if (!empty($fieldKey)) {
            $candidates[] = $fieldKey . '.md';
        }
        
        $candidates = array_merge($candidates, ['content.md', 'text.md', 'index.md']);
        
        foreach ($candidates as $candidate) {
            if (file_exists($directory . '/' . $candidate)) {
                return $candidate;
            }
        }
        
        return null;
    }
}