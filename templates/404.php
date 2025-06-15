<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="stylesheet" href="/css/main.css">
</head>

<body x-data="{ menuOpen: false }">

<!-- Content div -->
<div class="page error-404" :class="{ 'blur-sm': menuOpen }">

    <div class="ascii-art"><span aria-hidden="true"><pre> ▄         ▄  ▄▄▄▄▄▄▄▄▄▄▄ ▄         ▄ 
▐░▌       ▐░▌▐░░░░░░░░░░░▌▐░▌       ▐░▌
▐░▌       ▐░▌ ▀▀▀▀█░█▀▀▀▀ ▐░▌       ▐░▌
▐░▌       ▐░▌     ▐░▌     ▐░▌       ▐░▌
▐░█▄▄▄▄▄▄▄█░▌     ▐░▌     ▐░█▄▄▄▄▄▄▄█░▌
▐░░░░░░░░░░░░▌     ▐░▌     ▐░░░░░░░░░░░▌
▐░█▀▀▀▀▀▀▀█░▌     ▐░▌     ▐░█▀▀▀▀▀▀▀█░▌
▐░▌       ▐░▌     ▐░▌     ▐░▌       ▐░▌
▐░▌       ▐░▌ ▄▄▄▄█░█▄▄▄▄ ▐░▌       ▐░▌
▐░▌       ▐░▌▐░░░░░░░░░░░▌▐░▌       ▐░▌
 ▀         ▀  ▀▀▀▀▀▀▀▀▀▀▀  ▀         ▀ </pre></span><span class="sr-only">404</span></div>

    <div class="markdown">
        <h2>Page Not Found</h2>
        <p><?= htmlspecialchars($message ?? 'The requested page could not be found.') ?></p>
        <p><a href="/">← Back to Home</a></p>
    </div>

</div>

<!-- Simple menu -->
<div>
    <div x-show="!menuOpen" @click="menuOpen = !menuOpen" class="ascii-art menu-trigger">
        <span aria-hidden="true"><pre> ▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄ 
▐░░░░░░░░░░░░░░░░░░░░░░░░░░▌
 ▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀ 
                            
 ▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄ 
▐░░░░░░░░░░░░░░░░░░░░░░░░░░▌
 ▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀ 
                            
 ▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄ 
▐░░░░░░░░░░░░░░░░░░░░░░░░░░▌
 ▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀ </pre></span><span class="sr-only">Menu</span>
    </div>
    <div x-show="menuOpen" x-transition @click.away="menuOpen = false" @click="menuOpen = false" class="menu">
        <ul @click.stop class="menu-list">
            <li><a href="/">Home</a></li>
            <li><a href="/work">Work</a></li>
            <li><a href="/cv">CV</a></li>
        </ul>
    </div>
</div>

<script src="/js/htmx.min.js"></script>
<script src="/js/alpine.min.js" defer></script>
<script src="/js/ui.js" defer></script>
</body>
</html>