<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($foo->slug ?? '') === 'index' ? $foo->title : $foo->root()->title . ' - ' . $foo->title ?></title>
    <link rel="stylesheet" href="/css/main.css">
</head>

<body class="<?= ($foo->slug == 'cv') ? 'bg-yellow' : 'bg-light-gray' ?> dark:bg-full-black" x-data="{ menuOpen: false }">



    <?php include __DIR__ . '/menu.php'; ?>

    <!-- Background div -->
    <div :class="{ 'blur-sm': menuOpen }" class="absolute inset-0 -z-10 dark:invert" style="background-image: url('<?= $foo->bg_img['src'] ?>'); background-size: cover; background-position: center;"></div>

    <!-- Content div -->
    <div :class="{ 'blur-sm': menuOpen }" class="min-h-screen px-4 py-2 grid grid-cols-1">