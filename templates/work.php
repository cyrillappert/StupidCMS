<?php include __DIR__ . '/partials/head.php'; ?>

<div :class="{ 'blur-sm': menuOpen }" class="page work-page">
    <div class="ascii-art">
        <?= \StupidCMS\Util\AsciiArt::convertWithSpans('WORK', 'Electronic') ?>
    </div>

    <?php
    $children = $foo->children();
    $totalProjects = count($children);
    ?>

    <?php if ($totalProjects > 0): ?>
        <?php
        $firstProject = $children[0];
        $projectContent = $foo->{$firstProject['name']}();
        ?>
        <?php if ($projectContent): ?>
            <?= $projectContent->render() ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/partials/menu.php'; ?>
<?php include __DIR__ . '/partials/foot.php'; ?>