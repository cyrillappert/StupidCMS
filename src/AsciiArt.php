<?php

/**
 * ASCII art text converter with multiple font styles
 * Provides text-to-ASCII conversion and symbol rendering
 */

declare(strict_types=1);

namespace StupidCMS;

class AsciiArt
{
    private static array $fonts = [
        'Electronic' => [
            'A' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                " ▀         ▀ ",
            ],
            'B' => [
                " ▄▄▄▄▄▄▄▄▄▄  ",
                "▐░░░░░░░░░░▌ ",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░▌ ",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░▌ ",
                " ▀▀▀▀▀▀▀▀▀▀  ",
            ],
            'C' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            'D' => [
                " ▄▄▄▄▄▄▄▄▄▄  ",
                "▐░░░░░░░░░░▌ ",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░▌ ",
                " ▀▀▀▀▀▀▀▀▀▀  ",
            ],
            'E' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            'F' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                " ▀           ",
            ],
            'G' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░▌ ▄▄▄▄▄▄▄▄ ",
                "▐░▌▐░░░░░░░░▌",
                "▐░▌ ▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            'H' => [
                " ▄         ▄ ",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                " ▀         ▀ ",
            ],
            'I' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀█░█▀▀▀▀ ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                " ▄▄▄▄█░█▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            'J' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀█░█▀▀▀ ",
                "      ▐░▌    ",
                "      ▐░▌    ",
                "      ▐░▌    ",
                "      ▐░▌    ",
                "      ▐░▌    ",
                " ▄▄▄▄▄█░▌    ",
                "▐░░░░░░░▌    ",
                " ▀▀▀▀▀▀▀     ",
            ],
            'K' => [
                " ▄    ▄ ",
                "▐░▌  ▐░▌",
                "▐░▌ ▐░▌ ",
                "▐░▌▐░▌  ",
                "▐░▌░▌   ",
                "▐░░▌    ",
                "▐░▌░▌   ",
                "▐░▌▐░▌  ",
                "▐░▌ ▐░▌ ",
                "▐░▌  ▐░▌",
                " ▀    ▀ ",
            ],
            'L' => [
                " ▄           ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            'M' => [
                " ▄▄       ▄▄ ",
                "▐░░▌     ▐░░▌",
                "▐░▌░▌   ▐░▐░▌",
                "▐░▌▐░▌ ▐░▌▐░▌",
                "▐░▌ ▐░▐░▌ ▐░▌",
                "▐░▌  ▐░▌  ▐░▌",
                "▐░▌   ▀   ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                " ▀         ▀ ",
            ],
            'N' => [
                " ▄▄        ▄ ",
                "▐░░▌      ▐░▌",
                "▐░▌░▌     ▐░▌",
                "▐░▌▐░▌    ▐░▌",
                "▐░▌ ▐░▌   ▐░▌",
                "▐░▌  ▐░▌  ▐░▌",
                "▐░▌   ▐░▌ ▐░▌",
                "▐░▌    ▐░▌▐░▌",
                "▐░▌     ▐░▐░▌",
                "▐░▌      ▐░░▌",
                " ▀        ▀▀ ",
            ],
            'O' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            'P' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░▌          ",
                "▐░▌          ",
                " ▀           ",
            ],
            'Q' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀█░█▀▀ ",
                "        ▐░▌  ",
                "         ▀   ",
            ],
            'R' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀█░█▀▀ ",
                "▐░▌     ▐░▌  ",
                "▐░▌      ▐░▌ ",
                "▐░▌       ▐░▌",
                " ▀         ▀ ",
            ],
            'S' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "          ▐░▌",
                " ▄▄▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            'T' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀█░█▀▀▀▀ ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "      ▀      ",
            ],
            'U' => [
                " ▄         ▄ ",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            'V' => [
                " ▄               ▄ ",
                "▐░▌             ▐░▌",
                " ▐░▌           ▐░▌ ",
                "  ▐░▌         ▐░▌  ",
                "   ▐░▌       ▐░▌   ",
                "    ▐░▌     ▐░▌    ",
                "     ▐░▌   ▐░▌     ",
                "      ▐░▌ ▐░▌      ",
                "       ▐░▐░▌       ",
                "        ▐░▌        ",
                "         ▀         ",
            ],
            'W' => [
                " ▄         ▄ ",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌   ▄   ▐░▌",
                "▐░▌  ▐░▌  ▐░▌",
                "▐░▌ ▐░▌░▌ ▐░▌",
                "▐░▌▐░▌ ▐░▌▐░▌",
                "▐░▌░▌   ▐░▐░▌",
                "▐░░▌     ▐░░▌",
                " ▀▀       ▀▀ ",
            ],
            'X' => [
                " ▄       ▄ ",
                "▐░▌     ▐░▌",
                " ▐░▌   ▐░▌ ",
                "  ▐░▌ ▐░▌  ",
                "   ▐░▐░▌   ",
                "    ▐░▌    ",
                "   ▐░▌░▌   ",
                "  ▐░▌ ▐░▌  ",
                " ▐░▌   ▐░▌ ",
                "▐░▌     ▐░▌",
                " ▀       ▀ ",
            ],
            'Y' => [
                " ▄         ▄ ",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀█░█▀▀▀▀ ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "     ▐░▌     ",
                "      ▀      ",
            ],
            'Z' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "          ▐░▌",
                " ▄▄▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            '1' => [
                "    ▄▄▄▄     ",
                "  ▄█░░░░▌    ",
                " ▐░░▌▐░░▌    ",
                "  ▀▀ ▐░░▌    ",
                "     ▐░░▌    ",
                "     ▐░░▌    ",
                "     ▐░░▌    ",
                "     ▐░░▌    ",
                " ▄▄▄▄█░░█▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            '2' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "          ▐░▌",
                "          ▐░▌",
                " ▄▄▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            '3' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "          ▐░▌",
                " ▄▄▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "          ▐░▌",
                " ▄▄▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            '4' => [
                " ▄         ▄ ",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "          ▐░▌",
                "          ▐░▌",
                "          ▐░▌",
                "           ▀ ",
            ],
            '5' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "          ▐░▌",
                "          ▐░▌",
                " ▄▄▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            '6' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀▀▀ ",
                "▐░▌          ",
                "▐░█▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            '7' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "         ▐░▌ ",
                "        ▐░▌  ",
                "       ▐░▌   ",
                "      ▐░▌    ",
                "     ▐░▌     ",
                "    ▐░▌      ",
                "   ▐░▌       ",
                "    ▀        ",
            ],
            '8' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                " ▐░░░░░░░░░▌ ",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            '9' => [
                " ▄▄▄▄▄▄▄▄▄▄▄ ",
                "▐░░░░░░░░░░░▌",
                "▐░█▀▀▀▀▀▀▀█░▌",
                "▐░▌       ▐░▌",
                "▐░█▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀█░▌",
                "          ▐░▌",
                " ▄▄▄▄▄▄▄▄▄█░▌",
                "▐░░░░░░░░░░░▌",
                " ▀▀▀▀▀▀▀▀▀▀▀ ",
            ],
            '0' => [
                "  ▄▄▄▄▄▄▄▄▄  ",
                " ▐░░░░░░░░░▌ ",
                "▐░█░█▀▀▀▀▀█░▌",
                "▐░▌▐░▌    ▐░▌",
                "▐░▌ ▐░▌   ▐░▌",
                "▐░▌  ▐░▌  ▐░▌",
                "▐░▌   ▐░▌ ▐░▌",
                "▐░▌    ▐░▌▐░▌",
                "▐░█▄▄▄▄▄█░█░▌",
                " ▐░░░░░░░░░▌ ",
                "  ▀▀▀▀▀▀▀▀▀  ",
            ],
            '!' => [
                " ▄ ",
                "▐░▌",
                "▐░▌",
                "▐░▌",
                "▐░▌",
                "▐░▌",
                "▐░▌",
                " ▀ ",
                " ▄ ",
                "▐░▌",
                " ▀ ",
            ],
            '.' => [
                "   ",
                "   ",
                "   ",
                "   ",
                "   ",
                "   ",
                "   ",
                "   ",
                " ▄ ",
                "▐░▌",
                " ▀ ",
            ],
            ' ' => [
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
            ]
        ],

        'DiamFont' => [
            'A' => [
                " ▗▄▖ ",
                "▐▌ ▐▌",
                "▐▛▀▜▌",
                "▐▌ ▐▌",
                "     ",
                "     ",
            ],
            'B' => [
                "▗▄▄▖ ",
                "▐▌ ▐▌",
                "▐▛▀▚▖",
                "▐▙▄▞▘",
                "     ",
                "     ",
            ],
            'C' => [
                " ▗▄▄▖",
                "▐▌   ",
                "▐▌   ",
                "▝▚▄▄▖",
                "     ",
                "     ",
            ],
            'D' => [
                "▗▄▄▄  ",
                "▐▌  █ ",
                "▐▌  █ ",
                "▐▙▄▄▀ ",
                "      ",
                "      ",
            ],
            'E' => [
                "▗▄▄▄▖",
                "▐▌   ",
                "▐▛▀▀▘",
                "▐▙▄▄▖",
                "     ",
                "     ",
            ],
            'F' => [
                "▗▄▄▄▖",
                "▐▌   ",
                "▐▛▀▀▘",
                "▐▌   ",
                "     ",
                "     ",
            ],
            'G' => [
                " ▗▄▄▖",
                "▐▌   ",
                "▐▌▝▜▌",
                "▝▚▄▞▘",
                "     ",
                "     ",
            ],
            'H' => [
                "▗▖ ▗▖",
                "▐▌ ▐▌",
                "▐▛▀▜▌",
                "▐▌ ▐▌",
                "     ",
                "     ",
            ],
            'I' => [
                "▗▄▄▄▖",
                "  █  ",
                "  █  ",
                "▗▄█▄▖",
                "     ",
                "     ",
            ],
            'J' => [
                "   ▗▖",
                "   ▐▌",
                "   ▐▌",
                "▗▄▄▞▘",
                "     ",
                "     ",
            ],
            'K' => [
                "▗▖ ▗▖",
                "▐▌▗▞▘",
                "▐▛▚▖ ",
                "▐▌ ▐▌",
                "     ",
                "     ",
            ],
            'L' => [
                "▗▖   ",
                "▐▌   ",
                "▐▌   ",
                "▐▙▄▄▖",
                "     ",
                "     ",
            ],
            'M' => [
                "▗▖  ▗▖",
                "▐▛▚▞▜▌",
                "▐▌  ▐▌",
                "▐▌  ▐▌",
                "      ",
                "      ",
            ],
            'N' => [
                "▗▖  ▗▖",
                "▐▛▚▖▐▌",
                "▐▌ ▝▜▌",
                "▐▌  ▐▌",
                "      ",
                "      ",
            ],
            'O' => [
                " ▗▄▖ ",
                "▐▌ ▐▌",
                "▐▌ ▐▌",
                "▝▚▄▞▘",
                "     ",
                "     ",
            ],
            'P' => [
                "▗▄▄▖ ",
                "▐▌ ▐▌",
                "▐▛▀▘ ",
                "▐▌   ",
                "     ",
                "     ",
            ],
            'Q' => [
                "▗▄▄▄▖ ",
                "▐▌ ▐▌ ",
                "▐▌ ▐▌ ",
                "▐▙▄▟▙▖",
                "      ",
                "      ",
            ],
            'R' => [
                "▗▄▄▖ ",
                "▐▌ ▐▌",
                "▐▛▀▚▖",
                "▐▌ ▐▌",
                "     ",
                "     ",
            ],
            'S' => [
                " ▗▄▄▖",
                "▐▌   ",
                " ▝▀▚▖",
                "▗▄▄▞▘",
                "     ",
                "     ",
            ],
            'T' => [
                "▗▄▄▄▖",
                "  █  ",
                "  █  ",
                "  █  ",
                "     ",
                "     ",
            ],
            'U' => [
                "▗▖ ▗▖",
                "▐▌ ▐▌",
                "▐▌ ▐▌",
                "▝▚▄▞▘",
                "     ",
                "     ",
            ],
            'V' => [
                "▗▖  ▗▖",
                "▐▌  ▐▌",
                "▐▌  ▐▌",
                " ▝▚▞▘ ",
                "      ",
                "      ",
            ],
            'W' => [
                "▗▖ ▗▖",
                "▐▌ ▐▌",
                "▐▌ ▐▌",
                "▐▙█▟▌",
                "     ",
                "     ",
            ],
            'X' => [
                "▗▖  ▗▖",
                " ▝▚▞▘ ",
                "  ▐▌  ",
                "▗▞▘▝▚▖",
                "      ",
                "      ",
            ],
            'Y' => [
                "▗▖  ▗▖",
                " ▝▚▞▘ ",
                "  ▐▌  ",
                "  ▐▌  ",
                "      ",
                "      ",
            ],
            'Z' => [
                "▗▄▄▄▄▖",
                "   ▗▞▘",
                " ▗▞▘  ",
                "▐▙▄▄▄▖",
                "      ",
                "      ",
            ],
            'a' => [
                "▗▞▀▜▌",
                "▝▚▄▟▌",
                "     ",
                "     ",
                "     ",
                "     ",
            ],
            'b' => [
                "▗▖   ",
                "▐▌   ",
                "▐▛▀▚▖",
                "▐▙▄▞▘",
                "     ",
                "     ",
            ],
            'c' => [
                "▗▞▀▘",
                "▝▚▄▖",
                "    ",
                "    ",
                "    ",
                "    ",
            ],
            'd' => [
                "   ▐▌",
                "   ▐▌",
                "▗▞▀▜▌",
                "▝▚▄▟▌",
                "     ",
                "     ",
            ],
            'e' => [
                "▗▞▀▚▖",
                "▐▛▀▀▘",
                "▝▚▄▄▖",
                "     ",
                "     ",
                "     ",
            ],
            'f' => [
                "▗▞▀▀▘",
                "▐▌   ",
                "▐▛▀▘ ",
                "▐▌   ",
                "     ",
                "     ",
            ],
            'g' => [
                "     ",
                " ▗▄▖ ",
                "▐▌ ▐▌",
                " ▝▀▜▌",
                "▐▙▄▞▘",
                "     ",
            ],
            'h' => [
                "▐▌   ",
                "▐▌   ",
                "▐▛▀▚▖",
                "▐▌ ▐▌",
                "     ",
                "     ",
            ],
            'i' => [
                "▄ ",
                "▄ ",
                "█ ",
                "█ ",
                "  ",
                "  ",
            ],
            'j' => [
                "   ▗▖",
                "   ▗▖",
                "▄  ▐▌",
                "▀▄▄▞▘",
                "     ",
                "     ",
            ],
            'k' => [
                "█  ▄ ",
                "█▄▀  ",
                "█ ▀▄ ",
                "█  █ ",
                "     ",
                "     ",
            ],
            'l' => [
                "█ ",
                "█ ",
                "█ ",
                "█ ",
                "  ",
                "  ",
            ],
            'm' => [
                "▄▄▄▄  ",
                "█ █ █ ",
                "█   █ ",
                "      ",
                "      ",
                "      ",
            ],
            'n' => [
                "▄▄▄▄  ",
                "█   █ ",
                "█   █ ",
                "      ",
                "      ",
                "      ",
            ],
            'o' => [
                " ▄▄▄  ",
                "█   █ ",
                "▀▄▄▄▀ ",
                "      ",
                "      ",
                "      ",
            ],
            'p' => [
                "▄▄▄▄  ",
                "█   █ ",
                "█▄▄▄▀ ",
                "█     ",
                "▀     ",
                "      ",
            ],
            'q' => [
                " ▄▄▄▄ ",
                "█   █ ",
                "▀▄▄▄█ ",
                "    █ ",
                "    ▀ ",
                "      ",
            ],
            'r' => [
                " ▄▄▄ ",
                "█    ",
                "█    ",
                "     ",
                "     ",
                "     ",
            ],
            's' => [
                " ▄▄▄ ",
                "▀▄▄  ",
                "▄▄▄▀ ",
                "     ",
                "     ",
                "     ",
            ],
            't' => [
                "   ■  ",
                "▗▄▟▙▄▖",
                "  ▐▌  ",
                "  ▐▌  ",
                "  ▐▌  ",
                "      ",
            ],
            'u' => [
                "█  ▐▌",
                "▀▄▄▞▘",
                "     ",
                "     ",
                "     ",
                "     ",
            ],
            'v' => [
                "▄   ▄ ",
                "█   █ ",
                " ▀▄▀  ",
                "      ",
                "      ",
                "      ",
            ],
            'w' => [
                "▄   ▄ ",
                "█ ▄ █ ",
                "█▄█▄█ ",
                "      ",
                "      ",
                "      ",
            ],
            'x' => [
                "▄   ▄ ",
                " ▀▄▀  ",
                "▄▀ ▀▄ ",
                "      ",
                "      ",
                "      ",
            ],
            'y' => [
                "▄   ▄ ",
                "█   █ ",
                " ▀▀▀█ ",
                "▄   █ ",
                " ▀▀▀  ",
                "      ",
            ],
            'z' => [
                "▄▄▄▄▄ ",
                " ▄▄▄▀ ",
                "█▄▄▄▄ ",
                "      ",
                "      ",
                "      ",
            ],
            '1' => [
                "█ ",
                "█ ",
                "█ ",
                "█ ",
                "  ",
                "  ",
            ],
            '2' => [
                "▄▄▄▄ ",
                "   █ ",
                "█▀▀▀ ",
                "█▄▄▄ ",
                "     ",
                "     ",
            ],
            '3' => [
                "▄▄▄▄ ",
                "   █ ",
                "▀▀▀█ ",
                "▄▄▄█ ",
                "     ",
                "     ",
            ],
            '4' => [
                "▄  ▗▖",
                "█  ▐▌",
                "▀▀▀▜▌",
                "   ▐▌",
                "     ",
                "     ",
            ],
            '5' => [
                "▄▄▄▄ ",
                "█    ",
                "▀▀▀█ ",
                "▄▄▄█ ",
                "     ",
                "     ",
            ],
            '6' => [
                "▄▄▄▄ ",
                "█    ",
                "█▀▀█ ",
                "█▄▄█ ",
                "     ",
                "     ",
            ],
            '7' => [
                "▗▄▄▄▖",
                "   ▐▌",
                "   ▐▌",
                "   ▐▌",
                "     ",
                "     ",
            ],
            '8' => [
                "▄▄▄▄ ",
                "█  █ ",
                "█▀▀█ ",
                "█▄▄█ ",
                "     ",
                "     ",
            ],
            '9' => [
                "▄▄▄▄ ",
                "█  █ ",
                "▀▀▀█ ",
                "▄▄▄█ ",
                "     ",
                "     ",
            ],
            '0' => [
                "▄▀▀▚▖",
                "█  ▐▌",
                "█  ▐▌",
                "▀▄▄▞▘",
                "     ",
                "     ",
            ],
            '-' => [
                "      ",
                "      ",
                " ▄▄▄  ",
                "      ",
                "      ",
                "      ",
            ],
            '.' => [
                "  ",
                "  ",
                "  ",
                "  ",
                "▄ ",
                "  ",
            ],
            ',' => [
                "  ",
                "  ",
                "  ",
                "▄ ",
                "▞ ",
                "  ",
            ],
            ' ' => [
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
                "     ",
            ],

        ],
    ];

    private static array $symbols = [

        'burger' => [
            " ▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄ ",
            "▐░░░░░░░░░░░░░░░░░░░░░░░░░░▌",
            " ▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀ ",
            "                            ",
            " ▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄ ",
            "▐░░░░░░░░░░░░░░░░░░░░░░░░░░▌",
            " ▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀ ",
            "                            ",
            " ▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄ ",
            "▐░░░░░░░░░░░░░░░░░░░░░░░░░░▌",
            " ▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀ ",
        ],

    ];


    public static function convert(string $text, string $fontName = ''): string
    {
        $firstFont = array_key_first(self::$fonts);
        $font = self::$fonts[$fontName] ?? self::$fonts[$firstFont];

        // Check if font supports lowercase by testing if 'a' exists
        $supportsLowercase = isset($font['a']) && !empty($font['a']);

        // Only convert to uppercase if font doesn't support lowercase
        if (!$supportsLowercase) {
            $text = strtoupper($text);
        }

        // Determine font height by checking the first available character
        $fontHeight = count($font[' ']);
        $lines = array_fill(0, $fontHeight, "");

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $pattern = $font[$char] ?? $font[' '];

            for ($line = 0; $line < $fontHeight; $line++) {
                $lines[$line] .= $pattern[$line];
            }
        }

        return implode("\n", $lines);
    }

    public static function convertWithSpans(string $text, string $fontName = ''): string
    {
        $words = explode(' ', $text);
        $spans = [];

        foreach ($words as $word) {
            $asciiWord = self::convert($word, $fontName);
            $spans[] = '<pre>' . htmlspecialchars($asciiWord) . '</pre>';
        }

        return '<span aria-hidden="true">' . implode('', $spans) . '</span><span class="sr-only">' . htmlspecialchars($text) . '</span>';
    }

    public static function getAvailableFonts(): array
    {
        return array_keys(self::$fonts);
    }

    public static function ascii(string $symbolName): string
    {
        return isset(self::$symbols[$symbolName])
            ? implode("\n", self::$symbols[$symbolName])
            : '';
    }

    public static function symbolWithSpan(string $symbolName, string $altText = ''): string
    {
        $symbol = self::ascii($symbolName);
        if (empty($symbol)) {
            return '';
        }

        $altText = $altText ?: ucfirst($symbolName);
        return '<span aria-hidden="true"><pre>' . htmlspecialchars($symbol) . '</pre></span><span class="sr-only">' . htmlspecialchars($altText) . '</span>';
    }

    public static function getAvailableSymbols(): array
    {
        return array_keys(self::$symbols);
    }
}
