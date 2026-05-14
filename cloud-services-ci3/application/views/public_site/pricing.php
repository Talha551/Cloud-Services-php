<div class="min-h-screen bg-[#0a0c12] text-slate-200 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'pricing')); ?>

    <section class="relative pt-32 pb-16 overflow-hidden">
        <div class="absolute rounded-full blur-3xl opacity-20 pointer-events-none w-[500px] h-[500px] bg-indigo-600 -top-20 left-1/2 -translate-x-1/2"></div>
        <div class="relative max-w-6xl mx-auto px-5 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-500/10 border border-green-500/20 rounded-full text-xs text-green-400 mb-5">
                <i data-lucide="zap" class="w-3 h-3"></i> Simple, transparent pricing
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 tracking-tight">Plans for every scale</h1>
            <p class="text-slate-400 max-w-xl mx-auto">No hidden fees. No contracts. Deploy as many servers as you need and only pay for what you use.</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <?php foreach ($plans as $plan): ?>
                <div class="relative rounded-2xl border p-6 flex flex-col <?php echo !empty($plan['highlight']) ? 'bg-indigo-600/10 border-indigo-500/40 shadow-xl shadow-indigo-500/10' : 'bg-white/[0.02] border-white/[0.07]'; ?>">
                    <?php if (!empty($plan['badge'])): ?>
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-indigo-600 rounded-full text-xs font-semibold text-white whitespace-nowrap"><?php echo html_escape($plan['badge']); ?></div>
                    <?php endif; ?>
                    <div class="mb-5">
                        <p class="text-sm font-semibold text-white mb-1"><?php echo html_escape($plan['name']); ?></p>
                        <div class="flex items-end gap-1 mb-2">
                            <span class="text-3xl font-bold text-white"><?php echo html_escape($plan['price']); ?></span>
                            <span class="text-sm text-slate-500 mb-1"><?php echo html_escape($plan['period']); ?></span>
                        </div>
                        <p class="text-xs text-slate-500"><?php echo html_escape($plan['desc']); ?></p>
                    </div>
                    <ul class="space-y-2.5 flex-1 mb-6">
                        <?php foreach ($plan['features'] as $feature): ?>
                            <li class="flex items-center gap-2 text-xs text-slate-400"><i data-lucide="check-circle-2" class="w-3 h-3 <?php echo !empty($plan['highlight']) ? 'text-indigo-400' : 'text-slate-600'; ?>"></i><?php echo html_escape($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo site_url('register'); ?>" class="text-center py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo !empty($plan['highlight']) ? 'bg-indigo-600 hover:bg-indigo-500 text-white' : 'bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 hover:text-white'; ?>">Get Started</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12 bg-white/[0.02] border border-white/[0.05] rounded-2xl p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><p class="text-sm font-semibold text-white mb-2">Can I upgrade anytime?</p><p class="text-xs text-slate-500 leading-relaxed">Yes. Resize your server to a larger plan at any time directly from the dashboard with zero downtime.</p></div>
            <div><p class="text-sm font-semibold text-white mb-2">What payment methods?</p><p class="text-xs text-slate-500 leading-relaxed">We accept all major credit cards and PayPal. Monthly billing with no long-term contracts required.</p></div>
            <div><p class="text-sm font-semibold text-white mb-2">Is there a free trial?</p><p class="text-xs text-slate-500 leading-relaxed">New accounts get a 3-day free trial on the Starter plan. No credit card required to start.</p></div>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
