<div class="min-h-screen text-slate-800 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'about')); ?>

    <section class="pt-28 pb-14">
        <div class="max-w-7xl mx-auto px-5">
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-3">About CloudPanel</p>
            <h1 class="text-5xl text-slate-900 mb-4">Cloud infrastructure focused on speed, clarity, and control.</h1>
            <p class="text-slate-600 max-w-3xl text-lg leading-relaxed">CloudPanel is built for teams that need production-ready VPS operations without complexity. We combine automation, transparent pricing, and practical operations tooling so engineering teams can move faster.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-5 pb-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-7 shadow-sm">
                <h2 class="text-2xl text-slate-900 mb-4">Our mission</h2>
                <p class="text-slate-600 leading-relaxed mb-4">Make cloud hosting operationally simple while preserving flexibility for advanced teams. Every feature in CloudPanel is designed around practical workflows: deploy quickly, monitor continuously, and scale safely.</p>
                <p class="text-slate-600 leading-relaxed">From startups to agencies and SaaS teams, we support environments where reliability and response time matter every day.</p>
            </div>
            <div class="bg-slate-900 text-white rounded-2xl p-7">
                <h2 class="text-2xl mb-4">What we focus on</h2>
                <div class="space-y-3">
                    <?php foreach ($highlights as $item): ?>
                        <div class="p-3 rounded-xl border border-white/10 bg-white/5 text-sm text-slate-200 inline-flex w-full items-center gap-2.5">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-cyan-300 shrink-0"></i>
                            <?php echo html_escape($item); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-5 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-sky-50 border border-sky-100 rounded-2xl p-6">
                <h3 class="text-lg text-slate-900 mb-2">Fast Deployments</h3>
                <p class="text-sm text-slate-600">Provision KVM instances in under a minute with prebuilt templates and instant console access.</p>
            </div>
            <div class="bg-orange-50 border border-orange-100 rounded-2xl p-6">
                <h3 class="text-lg text-slate-900 mb-2">Transparent Billing</h3>
                <p class="text-sm text-slate-600">Straightforward monthly plans with no setup charges and clear upgrade paths.</p>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6">
                <h3 class="text-lg text-slate-900 mb-2">Always-On Support</h3>
                <p class="text-sm text-slate-600">Support and operational guidance available around the clock for critical workloads.</p>
            </div>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
