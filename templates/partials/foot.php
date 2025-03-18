    <script src="/js/htmx.min.js"></script>
    <script>
        // Debug HTMX events
        htmx.on('htmx:beforeRequest', function(e) {
            console.log('HTMX request starting:', e.detail.pathInfo.requestPath);
        });
        htmx.on('htmx:afterRequest', function(e) {
            console.log('HTMX request completed:', e.detail.xhr.status);
        });
    </script>
    <script src="/js/alpine.min.js" defer></script>
    <script src="/js/ui.js" defer></script>
</body>
</html>