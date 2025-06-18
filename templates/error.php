<?php

use StupidCMS\AsciiArt;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($foo->slug ?? '') === 'index' ? $foo->title : $foo->root()->title . ' - ' . $foo->title ?></title>
    <link rel="stylesheet" href="/css/cool.css">
</head>

<body class="bg-blue" x-data="{ menuOpen: false }">

    <h1 class="leading-none text-[5px] sm:text-[7px] lg:text-2xl mb-12 text-light-gray">
        <?= AsciiArt::convertWithSpans($foo->title, 'Electronic'); ?>
    </h1>

    <div class="error">
        <?= $foo->getBody() ?>
    </div>

    <div class="fixed bg-white"><?= $foo->button ?></div>





    <script src="/js/htmx.min.js"></script>
    <script src="/js/alpine.min.js" defer></script>
    <script src="/js/ui.js" defer></script>
</body>

</html>