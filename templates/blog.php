<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/page_title.php'; ?>


<?php foreach ($foo->children() as $index => $child): ?>
    <a href="/<?= $child->path ?>" class="container markdown grid items-center grid-cols-2 gap-8 <?= $index % 2 === 1 ? '[&>*:first-child]:order-2' : '' ?>" id="entry-<?= $child->slug ?>">
        <div>
            <!-- <p class="font-bold text-xs"><?= $child->date ? date('F j, Y', is_numeric($child->date) ? $child->date : strtotime($child->date)) : 'No date' ?></p> -->
            <h2 class="text-shadow-yellow"><?= htmlspecialchars($child->title) ?></h2>
            <?php if ($child->tags): ?>
                <div class="font-bold flex flex-wrap gap-2 text-xs sm:text-sm uppercase italic">
                    <?php foreach ($child->tags as $tag): ?>
                        <span class="bg-yellow"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <?php if ($child->featured_img): ?>
                <img src="<?= $child->featured_img['src'] ?>" alt="<?= htmlspecialchars($child->featured_img['alt']) ?>">
            <?php endif; ?>
        </div>
    </a>
<?php endforeach; ?>
<?php include __DIR__ . '/partials/footer.php'; ?>