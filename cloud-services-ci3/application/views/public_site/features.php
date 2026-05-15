<div class="min-h-screen text-slate-800 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'features')); ?>

    <section class="relative pt-28 pb-12 overflow-hidden">
        <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-[520px] h-[320px] rounded-full bg-sky-200/50 blur-3xl"></div>
        <div class="relative max-w-6xl mx-auto px-5 text-center">
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-3">Platform Features</p>
            <h1 class="text-4xl md:text-6xl text-slate-900 mb-4 tracking-tight">Everything you need to run cloud workloads</h1>
            <p class="text-slate-600 max-w-2xl mx-auto mb-8">From instant deployment to backup automation and live observability, CloudPanel gives you end-to-end control over your infrastructure lifecycle.</p>
            <a href="<?php echo site_url('pricing'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white text-sm font-semibold rounded-xl transition-colors">See Pricing <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-5 pb-14">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <?php foreach ($features as $feature): ?>
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 bg-sky-50 border border-sky-100 text-sky-700">
                        <i data-lucide="<?php echo html_escape($feature['icon']); ?>" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 mb-2"><?php echo html_escape($feature['title']); ?></h3>
                        <p class="text-sm text-slate-600 leading-relaxed"><?php echo html_escape($feature['desc']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-10">
            <div class="rounded-2xl bg-slate-900 text-white p-6">
                <p class="text-xs uppercase tracking-[0.18em] text-slate-300 mb-2">Reliability</p>
                <p class="text-3xl font-semibold mb-2">99.95%</p>
                <p class="text-sm text-slate-300">Uptime target with proactive host monitoring and support escalation.</p>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-6">
                <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-2">Provisioning</p>
                <p class="text-3xl font-semibold mb-2 text-slate-900">&lt; 60s</p>
                <p class="text-sm text-slate-600">Typical VM deployment time using preconfigured templates.</p>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-6">
                <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-2">Support</p>
                <p class="text-3xl font-semibold mb-2 text-slate-900">24/7</p>
                <p class="text-sm text-slate-600">Technical guidance for migrations, incidents, and optimization.</p>
            </div>
        </div>

        <div class="mt-16 text-center">
            <p class="text-slate-600 text-sm mb-4">Ready to use these features in production?</p>
            <a href="<?php echo site_url('register'); ?>" class="inline-flex items-center gap-2 px-7 py-3.5 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-xl transition-colors text-sm">Create Your Account <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
