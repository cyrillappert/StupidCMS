<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/page_title.php'; ?>

<?php if ($foo->parent()): ?>
    <div class='text-shadow-yellow text-lg'>
        <a href="/<?= $foo->parent()->path ?>#entry-<?= $foo->slug ?>" class="hover:underline">← Back to Blog</a>
    </div>
<?php endif; ?>

<div class="container dark:text-white project markdown">
    <?= $foo->getBody() ?>
</div>

<div class="flex justify-between items-center text-lg mt-8 text-shadow-yellow">
    <div>
        <?php if ($foo->previousSibling()): ?>
            <a href="/<?= $foo->previousSibling()->path ?>" class="hover:underline">
                ← Previous: <?= htmlspecialchars($foo->previousSibling()->title) ?>
            </a>
        <?php endif; ?>
    </div>
    <div>
        <?php if ($foo->nextSibling()): ?>
            <a href="/<?= $foo->nextSibling()->path ?>" class="hover:underline">
                Next: <?= htmlspecialchars($foo->nextSibling()->title) ?> →
            </a>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>