<button id="theme-toggle-public" type="button" class="theme-toggle-fab" aria-label="Toggle theme">
    <i data-lucide="moon" class="w-4 h-4"></i>
    <span id="theme-toggle-public-label">Dark</span>
</button>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
(function () {
    var root = document.documentElement;
    var themeButton = document.getElementById('theme-toggle-public');
    var themeLabel = document.getElementById('theme-toggle-public-label');

    function currentTheme() {
        return root.getAttribute('data-theme') || 'light';
    }

    function setTheme(theme) {
        root.setAttribute('data-theme', theme);
        try { localStorage.setItem('cp_theme', theme); } catch (e) { }
        if (themeLabel) {
            themeLabel.textContent = theme === 'dark' ? 'Light' : 'Dark';
        }
        if (themeButton) {
            var iconName = theme === 'dark' ? 'sun' : 'moon';
            var icon = themeButton.querySelector('i[data-lucide]');
            if (icon) {
                icon.setAttribute('data-lucide', iconName);
            }
        }
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    if (window.lucide) {
        window.lucide.createIcons();
    }

    setTheme(currentTheme());

    if (themeButton) {
        themeButton.addEventListener('click', function () {
            setTheme(currentTheme() === 'dark' ? 'light' : 'dark');
        });
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
