<nav class="fixed top-0 left-0 right-0 z-50 bg-white/85 backdrop-blur-xl border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-5 h-16 flex items-center justify-between">
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-2.5">
            <div class="w-9 h-9 bg-gradient-to-br from-sky-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i data-lucide="cloud" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-bold text-slate-900 text-sm tracking-wide">CloudPanel</span>
        </a>

        <div class="hidden md:flex items-center gap-1">
            <a href="<?php echo site_url('/'); ?>" class="px-4 py-2 rounded-lg text-sm transition-colors <?php echo ($active_page === 'home') ? 'text-blue-700 font-semibold bg-blue-50' : 'text-slate-600 hover:text-slate-900'; ?>">Home</a>
            <a href="<?php echo site_url('/'); ?>#features" class="px-4 py-2 rounded-lg text-sm transition-colors text-slate-600 hover:text-slate-900">Features</a>
            <a href="<?php echo site_url('/'); ?>#pricing" class="px-4 py-2 rounded-lg text-sm transition-colors text-slate-600 hover:text-slate-900">Pricing</a>
            <a href="<?php echo site_url('/'); ?>#faqs" class="px-4 py-2 rounded-lg text-sm transition-colors text-slate-600 hover:text-slate-900">FAQs</a>
            <a href="<?php echo site_url('features'); ?>" class="px-4 py-2 rounded-lg text-sm transition-colors <?php echo ($active_page === 'features') ? 'text-blue-700 font-semibold bg-blue-50' : 'text-slate-600 hover:text-slate-900'; ?>">All Features</a>
            <a href="<?php echo site_url('pricing'); ?>" class="px-4 py-2 rounded-lg text-sm transition-colors <?php echo ($active_page === 'pricing') ? 'text-blue-700 font-semibold bg-blue-50' : 'text-slate-600 hover:text-slate-900'; ?>">Pricing Page</a>
        </div>

        <div class="hidden md:flex items-center gap-3">
            <a href="<?php echo site_url('login'); ?>" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Sign In</a>
            <a href="<?php echo site_url('register'); ?>" class="px-4 py-2 bg-orange-500 hover:bg-orange-400 text-white text-sm font-semibold rounded-lg transition-colors shadow-lg shadow-orange-500/25">Get Started</a>
        </div>

        <button id="mobile-nav-toggle" class="md:hidden text-slate-500 hover:text-slate-900">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
    </div>

    <div id="mobile-nav-panel" class="hidden md:hidden bg-white border-t border-slate-200 px-5 py-4 space-y-1">
        <a href="<?php echo site_url('/'); ?>" class="block py-2 text-sm text-slate-700 hover:text-blue-700">Home</a>
        <a href="<?php echo site_url('/'); ?>#features" class="block py-2 text-sm text-slate-700 hover:text-blue-700">Features</a>
        <a href="<?php echo site_url('/'); ?>#pricing" class="block py-2 text-sm text-slate-700 hover:text-blue-700">Pricing</a>
        <a href="<?php echo site_url('/'); ?>#faqs" class="block py-2 text-sm text-slate-700 hover:text-blue-700">FAQs</a>
        <div class="pt-3 flex gap-3">
            <a href="<?php echo site_url('login'); ?>" class="flex-1 text-center py-2 text-sm border border-slate-300 rounded-lg text-slate-700 hover:text-slate-900">Sign In</a>
            <a href="<?php echo site_url('register'); ?>" class="flex-1 text-center py-2 text-sm bg-orange-500 rounded-lg text-white">Get Started</a>
        </div>
    </div>
</nav>
