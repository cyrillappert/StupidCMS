<!-- Project navigation -->
<div class="project-nav">
    <?php foreach ($projects ?? [] as $project): ?>
        <a 
            class="nav-project <?= $project['slug'] === $currentSlug ? 'active' : '' ?>"
            href="/<?= $project['slug'] ?>">
            <?= htmlspecialchars($project['title']) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Project content -->
<article class="project">
    <h1><?= $escape($foo->title ?? 'Project') ?></h1>
    <div class="markdown">
        <?= $foo->body ?? '' ?>
    </div>
</article>