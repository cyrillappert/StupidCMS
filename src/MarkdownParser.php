<?php

declare(strict_types=1);

namespace StupidCMS;

use ParsedownExtra;
use StupidCMS\ImageHandler;

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

    public function parse(string $markdown, string $directory): string
    {
        $processed = $this->imageHandler->processInMarkdown($markdown, $directory);
        $html = $this->parser->text($processed);
        $html = $this->imageHandler->addSizeClasses($html);
        
        return $html;
    }

}