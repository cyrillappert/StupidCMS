<?php

declare(strict_types=1);

namespace StupidCMS\Content;

class Content
{
    public function __construct(
        private string $body,
        private string $slug,
        private bool $published = false,
        private string $template = 'default',
        private array $customFields = []
    ) {}

    public function getBody(): string { return $this->body; }
    public function getSlug(): string { return $this->slug; }
    public function isPublished(): bool { return $this->published; }
    public function getTemplate(): string { return $this->template; }

    public function __get(string $name)
    {
        return match($name) {
            'body' => $this->body,
            'slug' => $this->slug,
            'published' => $this->published,
            'template' => $this->template,
            default => $this->customFields[$name] ?? null
        };
    }

    public function __isset(string $name): bool
    {
        return in_array($name, ['body', 'slug', 'published', 'template']) 
            || isset($this->customFields[$name]);
    }
}