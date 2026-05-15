<script src="https://unpkg.com/lucide@latest"></script>
<script>
(function () {
    if (window.lucide) {
        window.lucide.createIcons();
    }
    var toggle = document.getElementById('mobile-nav-toggle');
    var panel = document.getElementById('mobile-nav-panel');
    if (toggle && panel) {
        toggle.addEventListener('click', function () {
            panel.classList.toggle('hidden');
        });
    }

    var links = document.querySelectorAll('a[href*="#"]');
    Array.prototype.forEach.call(links, function (link) {
        link.addEventListener('click', function () {
            if (panel && !panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
            }
        });
    });

    var reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && reveals.length > 0) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        Array.prototype.forEach.call(reveals, function (el) {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    }
}());
</script>
</body>
</html>
