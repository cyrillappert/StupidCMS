<?php include __DIR__ . '/partials/head.php'; ?>


<!-- Content div -->
<div class="page cv" :class="{ 'blur-sm': menuOpen }">

    <?= ascii($foo->title); ?>

    <div class="markdown">
        <?= $foo->getBody(); ?>
    </div>

</div>

<?php include __DIR__ . '/partials/menu.php'; ?>
<?php include __DIR__ . '/partials/foot.php'; ?>