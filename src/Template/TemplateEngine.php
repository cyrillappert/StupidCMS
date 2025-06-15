<?php

declare(strict_types=1);

namespace StupidCMS\Template;

class TemplateEngine
{
    private string $templateDir;
    
    public function __construct(?string $templateDir = null)
    {
        $this->templateDir = $templateDir ?? dirname(__DIR__, 2) . '/templates';
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
        extract($data, EXTR_SKIP);
        
        // Helper functions available in templates
        $escape = fn($value) => $this->escape($value);
        
        ob_start();
        try {
            require $templatePath;
            return ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_clean();
            throw new \RuntimeException(
                "Error rendering template '{$templatePath}': " . $e->getMessage(),
                0,
                $e
            );
        }
    }
}