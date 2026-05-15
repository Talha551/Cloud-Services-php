        </main>
    </div>
</div>
<button id="theme-toggle-dashboard" type="button" class="theme-toggle-fab" aria-label="Toggle theme">
    <i data-lucide="moon" class="w-4 h-4"></i>
    <span id="theme-toggle-dashboard-label">Dark</span>
</button>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
(function () {
    var root = document.documentElement;
    var themeButton = document.getElementById('theme-toggle-dashboard');
    var themeLabel = document.getElementById('theme-toggle-dashboard-label');

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

    setTheme(currentTheme());

    if (themeButton) {
        themeButton.addEventListener('click', function () {
            setTheme(currentTheme() === 'dark' ? 'light' : 'dark');
        });
    }
}());
</script>
</body>
</html>
