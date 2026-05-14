<div class="min-h-screen bg-[#0a0c12] text-slate-200 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'features')); ?>

    <section class="relative pt-32 pb-16 overflow-hidden">
        <div class="absolute rounded-full blur-3xl opacity-20 pointer-events-none w-[500px] h-[500px] bg-purple-600 -top-20 left-1/2 -translate-x-1/2"></div>
        <div class="relative max-w-6xl mx-auto px-5 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 tracking-tight">Everything in one place</h1>
            <p class="text-slate-400 max-w-xl mx-auto mb-8">A complete cloud management platform. From deploying your first VM to scaling a fleet of servers - we've got every tool you need.</p>
            <a href="<?php echo site_url('pricing'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition-colors">See Pricing <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
            <?php foreach ($features as $feature): ?>
                <div class="flex gap-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5 <?php echo $feature['color']; ?>">
                        <i data-lucide="<?php echo html_escape($feature['icon']); ?>" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-1"><?php echo html_escape($feature['title']); ?></h3>
                        <p class="text-xs text-slate-500 leading-relaxed"><?php echo html_escape($feature['desc']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-20 text-center">
            <p class="text-slate-500 text-sm mb-4">Ready to experience all of this?</p>
            <a href="<?php echo site_url('pricing'); ?>" class="inline-flex items-center gap-2 px-7 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition-colors shadow-lg shadow-indigo-500/25 text-sm">Get Started Today <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
