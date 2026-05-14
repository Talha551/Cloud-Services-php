<footer class="bg-[#0a0c12] border-t border-white/5 py-12 mt-auto">
    <div class="max-w-6xl mx-auto px-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 bg-indigo-600 rounded-md flex items-center justify-center">
                        <i data-lucide="globe" class="w-3.5 h-3.5 text-white"></i>
                    </div>
                    <span class="font-bold text-white text-sm">CloudPanel</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">High-performance cloud infrastructure powered by SolusVM2.</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Product</p>
                <div class="space-y-2">
                    <a href="<?php echo site_url('features'); ?>" class="block text-xs text-slate-500 hover:text-slate-300 transition-colors">Features</a>
                    <a href="<?php echo site_url('pricing'); ?>" class="block text-xs text-slate-500 hover:text-slate-300 transition-colors">Pricing</a>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Account</p>
                <div class="space-y-2">
                    <a href="<?php echo site_url('login'); ?>" class="block text-xs text-slate-500 hover:text-slate-300 transition-colors">Sign In</a>
                    <a href="<?php echo site_url('dashboard'); ?>" class="block text-xs text-slate-500 hover:text-slate-300 transition-colors">Dashboard</a>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Support</p>
                <div class="space-y-2">
                    <a href="#" class="block text-xs text-slate-500 hover:text-slate-300 transition-colors">Documentation</a>
                    <a href="#" class="block text-xs text-slate-500 hover:text-slate-300 transition-colors">Contact Us</a>
                </div>
            </div>
        </div>
        <div class="border-t border-white/5 pt-6 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-xs text-slate-600">&copy; <?php echo date('Y'); ?> CloudPanel. All rights reserved.</p>
            <p class="text-xs text-slate-600">Powered by SolusVM2</p>
        </div>
    </div>
</footer>
