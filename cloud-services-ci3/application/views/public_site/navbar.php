<nav class="fixed top-0 left-0 right-0 z-50 bg-[#0a0c12]/80 backdrop-blur-xl border-b border-white/5">
    <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between">
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i data-lucide="globe" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-bold text-white text-sm tracking-wide">CloudPanel</span>
        </a>

        <div class="hidden md:flex items-center gap-1">
            <a href="<?php echo site_url('/'); ?>" class="px-4 py-2 rounded-lg text-sm transition-colors <?php echo ($active_page === 'home') ? 'text-white font-medium' : 'text-slate-400 hover:text-slate-200'; ?>">Home</a>
            <a href="<?php echo site_url('features'); ?>" class="px-4 py-2 rounded-lg text-sm transition-colors <?php echo ($active_page === 'features') ? 'text-white font-medium' : 'text-slate-400 hover:text-slate-200'; ?>">Features</a>
            <a href="<?php echo site_url('pricing'); ?>" class="px-4 py-2 rounded-lg text-sm transition-colors <?php echo ($active_page === 'pricing') ? 'text-white font-medium' : 'text-slate-400 hover:text-slate-200'; ?>">Pricing</a>
        </div>

        <div class="hidden md:flex items-center gap-3">
            <a href="<?php echo site_url('login'); ?>" class="text-sm text-slate-400 hover:text-white transition-colors">Sign In</a>
            <a href="<?php echo site_url('register'); ?>" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">Get Started</a>
        </div>

        <button id="mobile-nav-toggle" class="md:hidden text-slate-400 hover:text-white">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
    </div>

    <div id="mobile-nav-panel" class="hidden md:hidden bg-[#0d0f18] border-t border-white/5 px-5 py-4 space-y-1">
        <a href="<?php echo site_url('/'); ?>" class="block py-2 text-sm text-slate-300 hover:text-white">Home</a>
        <a href="<?php echo site_url('features'); ?>" class="block py-2 text-sm text-slate-300 hover:text-white">Features</a>
        <a href="<?php echo site_url('pricing'); ?>" class="block py-2 text-sm text-slate-300 hover:text-white">Pricing</a>
        <div class="pt-3 flex gap-3">
            <a href="<?php echo site_url('login'); ?>" class="flex-1 text-center py-2 text-sm border border-white/10 rounded-lg text-slate-300 hover:text-white">Sign In</a>
            <a href="<?php echo site_url('register'); ?>" class="flex-1 text-center py-2 text-sm bg-indigo-600 rounded-lg text-white">Get Started</a>
        </div>
    </div>
</nav>
