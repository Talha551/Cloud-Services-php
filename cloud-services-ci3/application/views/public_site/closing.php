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
}());
</script>
</body>
</html>
