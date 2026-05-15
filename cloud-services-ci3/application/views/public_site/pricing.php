<div class="min-h-screen text-slate-800 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'pricing')); ?>

    <section class="relative pt-28 pb-12 overflow-hidden">
        <div class="absolute rounded-full blur-3xl opacity-70 pointer-events-none w-[500px] h-[340px] bg-sky-200 -top-20 left-1/2 -translate-x-1/2"></div>
        <div class="relative max-w-6xl mx-auto px-5 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full text-xs text-emerald-700 mb-5">
                <i data-lucide="zap" class="w-3 h-3"></i> Simple, transparent pricing
            </div>
            <h1 class="text-4xl md:text-6xl text-slate-900 mb-4 tracking-tight">Plans for every growth stage</h1>
            <p class="text-slate-600 max-w-xl mx-auto">No hidden fees and no setup charges. Start small, then scale resources instantly as demand grows.</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <?php foreach ($plans as $plan): ?>
                <div class="relative rounded-2xl border p-6 flex flex-col <?php echo !empty($plan['highlight']) ? 'bg-sky-600 text-white border-sky-600 shadow-xl shadow-sky-600/25' : 'bg-white border-slate-200'; ?>">
                    <?php if (!empty($plan['badge'])): ?>
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-slate-900 rounded-full text-xs font-semibold text-white whitespace-nowrap"><?php echo html_escape($plan['badge']); ?></div>
                    <?php endif; ?>
                    <div class="mb-5">
                        <p class="text-sm font-semibold <?php echo !empty($plan['highlight']) ? 'text-sky-100' : 'text-slate-900'; ?> mb-1"><?php echo html_escape($plan['name']); ?></p>
                        <div class="flex items-end gap-1 mb-2">
                            <span class="text-3xl font-bold <?php echo !empty($plan['highlight']) ? 'text-white' : 'text-slate-900'; ?>"><?php echo html_escape($plan['price']); ?></span>
                            <span class="text-sm <?php echo !empty($plan['highlight']) ? 'text-sky-100' : 'text-slate-500'; ?> mb-1"><?php echo html_escape($plan['period']); ?></span>
                        </div>
                        <p class="text-xs <?php echo !empty($plan['highlight']) ? 'text-sky-100' : 'text-slate-600'; ?>"><?php echo html_escape($plan['desc']); ?></p>
                    </div>
                    <ul class="space-y-2.5 flex-1 mb-6">
                        <?php foreach ($plan['features'] as $feature): ?>
                            <li class="flex items-center gap-2 text-xs <?php echo !empty($plan['highlight']) ? 'text-white' : 'text-slate-700'; ?>"><i data-lucide="check-circle-2" class="w-3 h-3 <?php echo !empty($plan['highlight']) ? 'text-sky-100' : 'text-emerald-600'; ?>"></i><?php echo html_escape($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo site_url('register'); ?>" class="text-center py-2.5 rounded-xl text-sm font-semibold transition-colors <?php echo !empty($plan['highlight']) ? 'bg-white text-sky-700 hover:bg-sky-50' : 'bg-slate-900 text-white hover:bg-slate-700'; ?>">Get Started</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12 bg-white border border-slate-200 rounded-2xl p-8 grid grid-cols-1 md:grid-cols-3 gap-6 shadow-sm">
            <div><p class="text-sm font-semibold text-slate-900 mb-2">Can I upgrade anytime?</p><p class="text-xs text-slate-600 leading-relaxed">Yes. Resize your server to a larger plan at any time directly from the dashboard with minimal operational effort.</p></div>
            <div><p class="text-sm font-semibold text-slate-900 mb-2">What payment methods?</p><p class="text-xs text-slate-600 leading-relaxed">Major cards and online payment options are supported with monthly recurring billing.</p></div>
            <div><p class="text-sm font-semibold text-slate-900 mb-2">Is trial available?</p><p class="text-xs text-slate-600 leading-relaxed">New customers can begin with starter resources and scale up once validated.</p></div>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
