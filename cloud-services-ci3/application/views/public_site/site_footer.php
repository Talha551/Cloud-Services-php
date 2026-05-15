<footer class="bg-slate-950 text-slate-200 mt-auto">
    <div class="max-w-7xl mx-auto px-5 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-10">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-sky-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <i data-lucide="cloud" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-bold text-white text-base">CloudPanel</span>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed max-w-sm mb-5">
                    High-performance cloud infrastructure with transparent pricing,
                    rapid provisioning, and full operational control.
                </p>
                <div class="space-y-1 text-sm text-slate-400">
                    <p class="inline-flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4"></i> support@cloudpanel.local</p>
                    <p class="inline-flex items-center gap-2"><i data-lucide="map-pin" class="w-4 h-4"></i> Global Regions: Frankfurt, London, Amsterdam, Singapore</p>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-white uppercase tracking-[0.18em] mb-3">Platform</p>
                <div class="space-y-2">
                    <a href="<?php echo site_url('features'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">Core Features</a>
                    <a href="<?php echo site_url('pricing'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">Pricing Overview</a>
                    <a href="<?php echo site_url('faq'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">FAQs</a>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-white uppercase tracking-[0.18em] mb-3">Company</p>
                <div class="space-y-2">
                    <a href="<?php echo site_url('about'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">About</a>
                    <a href="<?php echo site_url('contact'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">Contact</a>
                    <a href="<?php echo site_url('pricing'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">Plans</a>
                    <a href="<?php echo site_url('/'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">Home</a>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-white uppercase tracking-[0.18em] mb-3">Account</p>
                <div class="space-y-2">
                    <a href="<?php echo site_url('login'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">Sign In</a>
                    <a href="<?php echo site_url('register'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">Create Account</a>
                    <a href="<?php echo site_url('dashboard'); ?>" class="block text-sm text-slate-400 hover:text-slate-200 transition-colors">Dashboard</a>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-xs text-slate-500">&copy; <?php echo date('Y'); ?> CloudPanel. All rights reserved.</p>
            <p class="text-xs text-slate-500">Powered by SolusVM2 with secure VPS lifecycle automation.</p>
        </div>
    </div>
</footer>
