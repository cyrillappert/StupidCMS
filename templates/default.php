<?php include __DIR__ . '/partials/head.php'; ?>

<div class="page text-white default">
    <div class="content">
        <h1><?= $escape($foo->title ?? 'Page') ?></h1>
        <div class="markdown">
            <?= $foo->body ?? '' ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partials/menu.php'; ?>
<?php include __DIR__ . '/partials/foot.php'; ?>