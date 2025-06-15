<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($GLOBALS['currentSlug'] ?? 'index') === 'index' ? $foo->title : $foo->root()->title . ' - ' . $foo->title ?></title>
    <link rel="stylesheet" href="/css/main.css">
</head>

<body x-data="{ menuOpen: false }">