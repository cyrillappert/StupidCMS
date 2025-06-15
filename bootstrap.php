<?php

declare(strict_types=1);

use StupidCMS\Util\AsciiArt;

/**
 * Global ASCII art helper function
 */
function ascii(string $text, string $font = ''): string {
    return '<div class="ascii-art">' . AsciiArt::convertWithSpans($text, $font) . '</div>';
}