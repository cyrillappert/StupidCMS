<!-- Menu outside the blurred content -->
<div>
    <div x-show="!menuOpen" @click="menuOpen = !menuOpen" class="ascii-art menu-trigger">
        <?= \StupidCMS\Util\AsciiArt::symbolWithSpan('burger', 'Menu'); ?>
    </div>
    <div x-show="menuOpen" x-transition @click.away="menuOpen = false" @click="menuOpen = false" class="menu">
        <!-- <ul @click.stop class="ascii-art">
            <?php foreach ($foo->root()->navigation ?? [] as $item): ?>
                <?php
                // Use custom name if provided, otherwise get page title
                if (isset($item['name']) && !empty($item['name'])) {
                    $title = $item['name'];
                } else {
                    $slug = trim($item['url'], '/') ?: 'index';
                    if ($slug === 'index') {
                        $title = $foo->root()->title ?? 'Home';
                    } else {
                        $pageProxy = $foo->root()->{$slug}();
                        $title = $pageProxy ? $pageProxy->title : ucfirst($slug);
                    }
                }
                ?>
                <li><a href="<?= $item['url'] ?>"><?= ascii($title) ?></a></li>
            <?php endforeach; ?>
        </ul> -->

        <?php
        // Check if root has navigation defined, otherwise use children
        $navigation = $foo->root()->navigation ?? null;
        if ($navigation && is_array($navigation)) {
            // Use navigation from YAML
            $menuItems = [];
            foreach ($navigation as $item) {
                if (isset($item['name']) && !empty($item['name'])) {
                    $title = $item['name'];
                } else {
                    // Extract slug from URL and get page title
                    $slug = trim($item['url'], '/') ?: 'index';
                    if ($slug === 'index') {
                        $title = $foo->root()->title ?? 'Home';
                    } else {
                        $pageProxy = $foo->root()->{$slug}();
                        $title = $pageProxy ? $pageProxy->title : ucfirst($slug);
                    }
                }
                $menuItems[] = [
                    'url' => $item['url'],
                    'title' => $title
                ];
            }
        } else {
            // Fallback to alphabetical children
            $children = $foo->root()->children();
            $menuItems = [];
            foreach ($children as $child) {
                $menuItems[] = [
                    'url' => $child['url'],
                    'title' => $child['title']
                ];
            }
        }
        ?>
        <?php if (!empty($menuItems)): ?>
            <ul @click.stop class="menu-list">
                <?php foreach ($menuItems as $item): ?>
                    <?php
                    // Get current page URL for comparison
                    $currentUrl = '/' . ($GLOBALS['currentSlug'] ?? 'index');
                    if ($currentUrl === '/index') {
                        $currentUrl = '/';
                    }

                    // Skip the current page in the menu
                    if ($item['url'] !== $currentUrl): ?>
                        <li><a href="<?= $item['url'] ?>"><?= ascii($item['title']) ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>