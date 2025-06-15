<?php

declare(strict_types=1);

namespace StupidCMS\Content;

use StupidCMS\Core\Config;
use StupidCMS\Util\{FileLoader, FieldProcessor, ImageHandler, MarkdownParser};

class ContentBuilder
{
    private string $contentDir;
    private Config $config;
    private FileLoader $fileLoader;
    private FieldProcessor $fieldProcessor;
    private ImageHandler $imageHandler;
    private MarkdownParser $markdownParser;

    public function __construct(
        string $contentDir,
        ?Config $config = null,
        ?FileLoader $fileLoader = null,
        ?FieldProcessor $fieldProcessor = null,
        ?ImageHandler $imageHandler = null,
        ?MarkdownParser $markdownParser = null
    ) {
        $this->contentDir = rtrim($contentDir, '/');
        $this->config = $config ?? Config::getInstance();
        $this->fileLoader = $fileLoader ?? new FileLoader();
        $this->fieldProcessor = $fieldProcessor ?? new FieldProcessor();
        $this->imageHandler = $imageHandler ?? new ImageHandler();
        $this->markdownParser = $markdownParser ?? new MarkdownParser($this->imageHandler);
    }

    public function build(string $slug): ?Content
    {
        $markdownPath = $this->resolveMarkdownPath($slug);
        if (!$markdownPath) return null;

        $parsed = $this->fileLoader->loadMarkdownWithFrontmatter($markdownPath);
        $meta = $parsed['meta'];
        $directory = dirname($markdownPath);
        $body = $this->markdownParser->parse($parsed['content'], $directory);
        $template = $this->determineTemplate($markdownPath);
        
        // For homepage (empty slug), use the filename as the effective slug for field processing
        $effectiveSlug = $slug === '' ? basename($markdownPath, '.md') : $slug;
        $fields = $this->buildFields($meta, $effectiveSlug, $directory, $body);
        
        return new Content(
            body: $body,
            slug: $slug, // Keep original slug for URL routing
            published: $meta['published'] ?? false,
            template: $template,
            customFields: $fields
        );
    }

    private function resolveMarkdownPath(string $slug): ?string
    {
        if ($slug === '') {
            // For root/homepage, look directly in content directory
            $markdownFiles = $this->fileLoader->findMarkdownFiles($this->contentDir);
            return !empty($markdownFiles) ? $markdownFiles[0] : null;
        }

        $directPath = "{$this->contentDir}/{$slug}.md";
        if (file_exists($directPath)) return $directPath;
        
        $dir = "{$this->contentDir}/{$slug}";
        $markdownFiles = $this->fileLoader->findMarkdownFiles($dir);
        return !empty($markdownFiles) ? $markdownFiles[0] : null;
    }

    private function determineTemplate(string $markdownPath): string
    {
        $filename = basename($markdownPath, '.md');
        
        // Check if template with filename exists
        $templateDir = $this->config->get('template_dir');
        $preferredTemplate = $templateDir . '/' . $filename . '.php';
        
        if (file_exists($preferredTemplate)) {
            return $filename;
        }
        
        // Fallback to default template if it exists
        $defaultTemplate = $templateDir . '/default.php';
        if (file_exists($defaultTemplate)) {
            return 'default';
        }
        
        // If no fallback available, return the original filename
        // (TemplateEngine will throw an exception if template doesn't exist)
        return $filename;
    }

    private function buildFields(array $meta, string $slug, string $directory, string $bodyContent): array
    {
        $knownFields = ['published'];
        $fields = array_diff_key($meta, array_flip($knownFields));
        $fields = $this->fieldProcessor->sanitize($fields);
        $fields = $this->fieldProcessor->addDefaults($fields, $slug);
        $fields = $this->processSpecialFields($fields, $directory, $bodyContent);
        
        return $fields;
    }

    private function processSpecialFields(array $fields, string $directory, string $bodyContent = ''): array
    {
        foreach ($fields as $key => $value) {
            if (is_array($value) && isset($value['type'])) {
                $fields[$key] = match($value['type']) {
                    'img' => $this->processImageField($value, $directory),
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

}