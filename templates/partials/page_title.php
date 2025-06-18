<?php

use StupidCMS\AsciiArt;

?>

<h2 class="leading-none text-[5px] sm:text-[7px] <?= ($foo->slug == 'cv') ? 'dark:text-dark-gray' : '' ?> lg:text-xs mb-12 text-shadow-yellow">
    <?= AsciiArt::convertWithSpans($foo->title, 'Electronic'); ?>
</h2>