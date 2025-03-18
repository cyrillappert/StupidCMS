<?php include __DIR__ . '/partials/head.php'; ?>

<!-- Background div -->
<div :class="{ 'blur-sm': menuOpen }" class="home-bg" style="background-image: url('<?= $foo->bg_img['src'] ?>'); background-size: cover; background-position: center;"></div>

<!-- Content div -->
<div :class="{ 'blur-sm': menuOpen }" class="page home">

    <div class="featured-img">
        <img src="<?= $foo->featured_img['src'] ?>" alt="<?= $foo->featured_img['alt'] ?? '' ?>">
    </div>

    <div class="hero-title">

        <?= ascii($foo->title); ?>
    </div>
    <div class="hero-text">
        <div class="markdown">
            <?= $foo->hero_text; ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/partials/menu.php'; ?>
<?php include __DIR__ . '/partials/foot.php'; ?>