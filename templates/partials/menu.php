<!-- Menu outside the blurred content -->
<nav class="leading-none<?= ($foo->slug === 'index') ? ' text-dark-gray' : '' ?> text-[7px] sm:text-sm text-shadow-yellow">
    <span x-show="!menuOpen" @click="menuOpen = !menuOpen" class="z-1 text-[3px] sm:text-[5px] lg:text-[7px] m-2 fixed top-1 right-1 cursor-pointer">
        <?= StupidCMS\AsciiArt::symbolWithSpan('burger', 'Menu'); ?>
    </span>
    <div class="z-1 fixed top-0 flex text-center items-center justify-center left-0 h-full w-full" x-show="menuOpen" x-transition @click.away="menuOpen = false" @click="menuOpen = false">
        <?php
        // Use navigation from root
        $navigation = $foo->root()->navigation ?? [];
        $menuItems = [];
        foreach ($navigation as $item) {
            if (isset($item['name']) && !empty($item['name'])) {
                $title = $item['name'];
            } else {
                // Extract path from URL and get page title
                $path = trim($item['url'], '/') ?: 'index';
                if ($path === 'index') {
                    $title = $foo->root()->title ?? 'Home';
                } else {
                    $pageProxy = $foo->root()->child(basename($path));
                    $title = $pageProxy ? $pageProxy->title : ucfirst(basename($path));
                }
            }
            $menuItems[] = [
                'url' => $item['url'],
                'title' => $title
            ];
        }
        ?>
        <?php if (!empty($menuItems)): ?>
            <ul @click.stop class="menu-list">
                <?php foreach ($menuItems as $item): ?>
                    <?php
                    // Get current page URL for comparison
                    $currentUrl = '/' . ($foo->path ?? '');
                    if ($currentUrl === '/') {
                        $currentUrl = '/';
                    }

                    // Skip the current page in the menu
                    if ($item['url'] !== $currentUrl): ?>
                        <li>
                            <a href="<?= $item['url'] ?>">
                                <?= StupidCMS\AsciiArt::convertWithSpans($item['title'], 'Electronic') ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</nav>