<?php

declare(strict_types=1);

namespace StupidCMS\Util;

use ParsedownExtra;
use StupidCMS\Util\AsciiArt;

class MarkdownParser
{
    private ParsedownExtra $parser;
    private ImageHandler $imageHandler;

    public function __construct(ImageHandler $imageHandler)
    {
        $this->parser = new ParsedownExtra();
        $this->parser->setSafeMode(true);
        $this->imageHandler = $imageHandler;
    }

    public function parse(string $markdown, string $directory, ?array $asciiConfig = null): string
    {
        $processed = $this->imageHandler->processInMarkdown($markdown, $directory);
        $html = $this->parser->text($processed);
        $html = $this->imageHandler->addSizeClasses($html);
        
        if ($asciiConfig) {
            $html = $this->applyAsciiArt($html, $asciiConfig);
        }
        
        return $html;
    }

    private function applyAsciiArt(string $html, array $asciiConfig): string
    {
        $config = $asciiConfig === true ? [
            'h1' => 'Electronic',
            'h2' => 'DiamFont', 
            'h3' => 'Miniwi'
        ] : $asciiConfig;

        foreach ($config as $heading => $font) {
            $html = preg_replace_callback(
                "/<{$heading}>(.*?)<\/{$heading}>/i",
                fn($matches) => "<{$heading} class=\"ascii-art\">" . AsciiArt::convertWithSpans(strip_tags($matches[1]), $font) . "</{$heading}>",
                $html
            );
        }

        return $html;
    }
}