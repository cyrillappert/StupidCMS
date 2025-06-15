<?php

declare(strict_types=1);

namespace StupidCMS\Template;

use StupidCMS\Util\{ImageHandler, MarkdownParser};
use StupidCMS\Content\{ContentService, ContentProxy};

class TemplateEngine
{
    private string $templateDir;
    private MarkdownParser $markdownParser;
    private ?ContentService $contentService = null;
    
    public function __construct(?string $templateDir = null, ?MarkdownParser $markdownParser = null, ?ContentService $contentService = null)
    {
        $this->templateDir = $templateDir ?? dirname(__DIR__, 2) . '/templates';
        $this->markdownParser = $markdownParser ?? new MarkdownParser(new ImageHandler());
        $this->contentService = $contentService;
    }
    
    public function render(string $template, array $data = []): string
    {
        $templatePath = $this->resolveTemplatePath($template);
        
        if (!$templatePath) {
            throw new \RuntimeException("Template '{$template}' not found");
        }
        
        return $this->renderFile($templatePath, $data);
    }
    
    public function exists(string $template): bool
    {
        return $this->resolveTemplatePath($template) !== null;
    }
    
    public function renderContent(string $slug): string
    {
        if (!$this->contentService) {
            throw new \RuntimeException("ContentService not available - cannot render content by slug");
        }
        
        $content = $this->contentService->getContentBySlug($slug);
        if (!$content || !$content->isPublished()) {
            throw new \RuntimeException("Content not found or not published for slug: {$slug}");
        }
        
        $template = $content->getTemplate();
        $templatePath = $this->resolveTemplatePath($template);
        
        if (!$templatePath) {
            $templatePath = $this->resolveTemplatePath('default');
            if (!$templatePath) {
                throw new \RuntimeException("No template found for content: {$slug}");
            }
        }
        
        $contentProxy = new ContentProxy($content, $this->contentService);
        
        return $this->renderFile($templatePath, [
            'foo' => $contentProxy,
            'currentSlug' => $slug
        ]);
    }
    
    public function escape(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    private function resolveTemplatePath(string $template): ?string
    {
        // Try the requested template first
        $path = $this->templateDir . '/' . $template . '.php';
        if (file_exists($path)) {
            return $path;
        }
        
        // Fallback to default template if it exists
        $defaultPath = $this->templateDir . '/default.php';
        if (file_exists($defaultPath)) {
            return $defaultPath;
        }
        
        // No template found
        return null;
    }
    
    private function renderFile(string $templatePath, array $data): string
    {
        // Create isolated scope for template execution
        $templateEngine = $this;
        $renderTemplate = function(string $__templatePath, array $__data) use ($templateEngine) {
            // Extract data to local scope
            extract($__data, EXTR_SKIP);
            
            // For backward compatibility, set global variables
            if (isset($__data['foo'])) {
                $GLOBALS['foo'] = $__data['foo'];
            }
            if (isset($__data['currentSlug'])) {
                $GLOBALS['currentSlug'] = $__data['currentSlug'];
            }
            
            // Helper functions available in templates
            $escape = fn($value) => $templateEngine->escape($value);
            $template = fn($name, $templateData = []) => $templateEngine->render($name, $templateData);
            $content = fn($slug) => $templateEngine->renderContent($slug);
            $markdown = fn($text, $directory = '') => $templateEngine->markdownParser->parse($text, $directory);
            
            ob_start();
            try {
                require $__templatePath;
                return ob_get_clean();
            } catch (\Throwable $e) {
                ob_end_clean();
                throw new \RuntimeException(
                    "Error rendering template '{$__templatePath}': " . $e->getMessage(),
                    0,
                    $e
                );
            }
        };
        
        return $renderTemplate($templatePath, $data);
    }
}