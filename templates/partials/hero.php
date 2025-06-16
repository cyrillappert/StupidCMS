<?php

use StupidCMS\AsciiArt;
?>


<div class="row-start-1 dark:invert row-span-1 col-start-1 col-span-1 self-end justify-self-end">
    <img class="w-full max-w-2xl mx-auto" src="<?= $foo->featured_img['src'] ?>" alt="<?= $foo->featured_img['alt'] ?? '' ?>">
</div>

<h1 class="leading-none text-[6px] sm:text-xs text-dark-gray text-shadow-yellow row-start-1 row-span-1 col-span-1 col-start-1">
    <?= AsciiArt::convertWithSpans($foo->title, 'Electronic'); ?>
</h1>

<h2 class="italic uppercase text-6xl text-dark-gray text-shadow-yellow row-start-1 row-span-1 col-start-1 col-span-1 self-end max-w-2/3">
    <?= $foo->hero_text; ?>
</h2>