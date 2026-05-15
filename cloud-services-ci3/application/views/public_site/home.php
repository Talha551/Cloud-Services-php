<div class="min-h-screen text-slate-800 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'home')); ?>

    <section class="relative pt-28 pb-16 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -left-28 w-[420px] h-[420px] rounded-full bg-sky-200/70 blur-3xl"></div>
            <div class="absolute -top-20 right-[-80px] w-[360px] h-[360px] rounded-full bg-orange-200/70 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <div class="reveal inline-flex items-center gap-2 px-4 py-2 bg-white border border-sky-100 rounded-full text-xs font-semibold text-sky-700 mb-5 shadow-sm">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        Cloud Infrastructure for Real Business Workloads
                    </div>
                    <h1 class="reveal reveal-delay-1 text-5xl md:text-6xl leading-tight text-slate-900 mb-5">
                        Deploy Fast.
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-700 to-blue-500">Scale Confidently.</span>
                    </h1>
                    <p class="reveal reveal-delay-2 text-lg text-slate-600 leading-relaxed max-w-xl mb-8">
                        CloudPanel gives you production-ready VPS hosting with transparent billing,
                        enterprise security, and a simple control experience powered by SolusVM2.
                    </p>
                    <div class="reveal reveal-delay-3 flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-7">
                        <a href="<?php echo site_url('register'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-sky-500/30">
                            Start Free Trial <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <a href="<?php echo site_url('/'); ?>#pricing" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 hover:border-slate-300 text-slate-800 text-sm font-semibold rounded-xl transition-colors">
                            View Plans
                        </a>
                    </div>
                    <div class="flex items-center gap-6 text-sm text-slate-600 flex-wrap">
                        <span class="inline-flex items-center gap-2"><i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>99.95% Uptime SLA</span>
                        <span class="inline-flex items-center gap-2"><i data-lucide="clock-3" class="w-4 h-4 text-orange-500"></i>60s Average Provisioning</span>
                        <span class="inline-flex items-center gap-2"><i data-lucide="headset" class="w-4 h-4 text-blue-600"></i>24/7 Expert Support</span>
                    </div>
                </div>
                <div class="reveal reveal-delay-2 bg-white/95 border border-slate-200 rounded-3xl shadow-xl shadow-slate-200/70 p-5 md:p-7">
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="rounded-2xl bg-sky-50 border border-sky-100 p-4">
                            <p class="text-xs text-slate-600 mb-1">Active Instances</p>
                            <p class="text-2xl font-bold text-slate-900">2,476</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
                            <p class="text-xs text-slate-600 mb-1">Global Regions</p>
                            <p class="text-2xl font-bold text-slate-900">12</p>
                        </div>
                        <div class="rounded-2xl bg-orange-50 border border-orange-100 p-4">
                            <p class="text-xs text-slate-600 mb-1">Avg. Response</p>
                            <p class="text-2xl font-bold text-slate-900">14ms</p>
                        </div>
                        <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4">
                            <p class="text-xs text-slate-600 mb-1">Support CSAT</p>
                            <p class="text-2xl font-bold text-slate-900">98.9%</p>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                        <p class="text-sm font-semibold text-slate-800 mb-3">Live Deployment Feed</p>
                        <?php
                        $events = array(
                            array('ecommerce-prod-eu', 'Frankfurt', 'Provisioned in 48 sec', 'text-emerald-600'),
                            array('ai-worker-node-3', 'London', 'Snapshot completed', 'text-blue-600'),
                            array('billing-db-replica', 'Amsterdam', 'Auto-backup verified', 'text-orange-600'),
                        );
                        foreach ($events as $event): ?>
                            <div class="flex items-center justify-between py-2 border-b border-slate-200 last:border-0">
                                <div>
                                    <p class="text-sm text-slate-800 font-medium"><?php echo html_escape($event[0]); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo html_escape($event[1]); ?></p>
                                </div>
                                <span class="text-xs font-semibold <?php echo html_escape($event[3]); ?>"><?php echo html_escape($event[2]); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-5 pb-14">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-5">Trusted by fast-growing teams</p>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php foreach (array('NovaStack', 'FinBox', 'ByteForge', 'KiteLabs', 'ApexShip') as $brand): ?>
                    <div class="h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-sm font-semibold text-slate-600">
                        <?php echo html_escape($brand); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="features" class="max-w-7xl mx-auto px-5 py-14">
        <div class="text-center mb-12">
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-3">Platform Features</p>
            <h2 class="text-4xl text-slate-900 mb-3">Everything you need in one cloud platform</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">From instant provisioning to backups, observability, and access control, every core capability is available directly in the dashboard.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <?php
            $features = array(
                array('rocket', 'from-sky-50 to-sky-100', 'Instant Provisioning', 'Launch KVM servers in under a minute with predictable performance.'),
                array('shield-check', 'from-emerald-50 to-emerald-100', 'Automated Backups', 'Set daily backup policies and restore with one click.'),
                array('activity', 'from-orange-50 to-orange-100', 'Live Monitoring', 'Track CPU, memory, bandwidth and disk IOPS in real-time.'),
                array('terminal-square', 'from-indigo-50 to-indigo-100', 'Browser Console', 'Open emergency console access even when SSH is unavailable.'),
                array('network', 'from-cyan-50 to-cyan-100', 'Private Networking', 'Create project networks and isolate production workloads safely.'),
                array('key-round', 'from-pink-50 to-pink-100', 'API + Tokens', 'Automate deployments and integrate your CI/CD pipelines quickly.'),
            );
            foreach ($features as $feature): ?>
                <div class="bg-gradient-to-br <?php echo html_escape($feature[1]); ?> border border-slate-200 rounded-2xl p-6">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center mb-4 text-slate-800">
                        <i data-lucide="<?php echo html_escape($feature[0]); ?>" class="w-[18px] h-[18px]"></i>
                    </div>
                    <h3 class="text-lg text-slate-900 mb-2"><?php echo html_escape($feature[2]); ?></h3>
                    <p class="text-sm text-slate-600 leading-relaxed"><?php echo html_escape($feature[3]); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-5 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-900 rounded-3xl p-7 text-white relative overflow-hidden">
                <div class="absolute -top-8 -right-8 w-40 h-40 bg-sky-400/20 rounded-full blur-2xl"></div>
                <p class="text-xs uppercase tracking-[0.18em] text-sky-200 mb-4">How It Works</p>
                <h3 class="text-3xl mb-4">From signup to production in minutes</h3>
                <div class="space-y-4 text-sm text-slate-200">
                    <div><span class="text-sky-300 font-semibold">1.</span> Create your account and verify your email in less than one minute.</div>
                    <div><span class="text-sky-300 font-semibold">2.</span> Pick a plan, region, and OS template from your dashboard.</div>
                    <div><span class="text-sky-300 font-semibold">3.</span> Deploy instantly and connect using SSH keys or browser console.</div>
                    <div><span class="text-sky-300 font-semibold">4.</span> Scale resources, add snapshots, and monitor uptime from one place.</div>
                </div>
                <a href="<?php echo site_url('register'); ?>" class="inline-flex mt-6 items-center gap-2 px-5 py-2.5 bg-sky-500 hover:bg-sky-400 text-white text-sm font-semibold rounded-xl transition-colors">Create Account <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
            </div>
            <div class="bg-white border border-slate-200 rounded-3xl p-7">
                <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-4">Use Cases</p>
                <h3 class="text-3xl text-slate-900 mb-5">Built for teams shipping real products</h3>
                <div class="space-y-4">
                    <?php
                    $useCases = array(
                        array('E-commerce', 'Run high-availability storefronts with autoscaled app nodes and backup replicas.'),
                        array('SaaS Platforms', 'Host APIs, background workers, and databases with private service networking.'),
                        array('Dev/Test Labs', 'Spin up disposable environments for QA, demos, and staging pipelines.'),
                        array('Agencies', 'Manage multiple client servers from one billing and support dashboard.'),
                    );
                    foreach ($useCases as $useCase): ?>
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                            <p class="text-sm font-semibold text-slate-900 mb-1"><?php echo html_escape($useCase[0]); ?></p>
                            <p class="text-sm text-slate-600"><?php echo html_escape($useCase[1]); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="max-w-7xl mx-auto px-5 py-14">
        <div class="text-center mb-12">
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-3">Pricing</p>
            <h2 class="text-4xl text-slate-900 mb-3">Simple plans for every stage</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">Monthly billing, no setup fees, and instant upgrades as your product grows.</p>
        </div>
        <?php
        $pricingPlans = array(
            array('name' => 'Starter', 'price' => '$5', 'period' => '/month', 'desc' => 'Best for prototypes and personal projects.', 'highlight' => false, 'features' => array('1 vCPU', '1 GB RAM', '25 GB NVMe SSD', '1 TB bandwidth', '1 IPv4', 'Daily backups')),
            array('name' => 'Pro', 'price' => '$12', 'period' => '/month', 'desc' => 'Great for growing startups and production apps.', 'highlight' => true, 'features' => array('2 vCPU', '4 GB RAM', '80 GB NVMe SSD', '3 TB bandwidth', 'IPv4 + IPv6', 'Priority support')),
            array('name' => 'Business', 'price' => '$24', 'period' => '/month', 'desc' => 'For teams handling high-traffic workloads.', 'highlight' => false, 'features' => array('4 vCPU', '8 GB RAM', '160 GB NVMe SSD', '6 TB bandwidth', '2 IPv4 + IPv6', 'Snapshots + backups')),
            array('name' => 'Enterprise', 'price' => '$48', 'period' => '/month', 'desc' => 'Mission-critical performance with dedicated support.', 'highlight' => false, 'features' => array('8 vCPU', '16 GB RAM', '320 GB NVMe SSD', 'Unlimited bandwidth', '4 IPv4 + IPv6', 'Dedicated account manager')),
        );
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <?php foreach ($pricingPlans as $plan): ?>
                <div class="rounded-2xl border p-6 flex flex-col <?php echo !empty($plan['highlight']) ? 'bg-sky-600 text-white border-sky-600 shadow-xl shadow-sky-600/25' : 'bg-white border-slate-200'; ?>">
                    <?php if (!empty($plan['highlight'])): ?>
                        <span class="inline-flex w-fit items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-white/15 border border-white/20 mb-4">Most Popular</span>
                    <?php endif; ?>
                    <p class="text-sm font-semibold <?php echo !empty($plan['highlight']) ? 'text-sky-100' : 'text-slate-700'; ?>"><?php echo html_escape($plan['name']); ?></p>
                    <div class="flex items-end gap-1 my-2">
                        <span class="text-4xl font-bold <?php echo !empty($plan['highlight']) ? 'text-white' : 'text-slate-900'; ?>"><?php echo html_escape($plan['price']); ?></span>
                        <span class="text-sm <?php echo !empty($plan['highlight']) ? 'text-sky-100' : 'text-slate-500'; ?> mb-1"><?php echo html_escape($plan['period']); ?></span>
                    </div>
                    <p class="text-sm <?php echo !empty($plan['highlight']) ? 'text-sky-100' : 'text-slate-600'; ?> mb-5"><?php echo html_escape($plan['desc']); ?></p>
                    <ul class="space-y-2 text-sm flex-1 mb-6">
                        <?php foreach ($plan['features'] as $feature): ?>
                            <li class="flex items-center gap-2 <?php echo !empty($plan['highlight']) ? 'text-white' : 'text-slate-700'; ?>"><i data-lucide="check" class="w-4 h-4 <?php echo !empty($plan['highlight']) ? 'text-sky-200' : 'text-emerald-600'; ?>"></i><?php echo html_escape($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo site_url('register'); ?>" class="text-center py-2.5 rounded-xl text-sm font-semibold transition-colors <?php echo !empty($plan['highlight']) ? 'bg-white text-sky-700 hover:bg-sky-50' : 'bg-slate-900 text-white hover:bg-slate-700'; ?>">Choose <?php echo html_escape($plan['name']); ?></a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-5 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <h3 class="text-2xl text-slate-900 mb-2">What customers say</h3>
                <p class="text-slate-600 mb-5">Teams use CloudPanel for reliability, speed, and predictable costs.</p>
                <div class="space-y-4">
                    <?php
                    $testimonials = array(
                        array('CloudPanel cut our deployment time by 70%. It is now part of our CI process.', 'Awais Khalid', 'CTO, FinBox'),
                        array('We migrated 40+ client workloads with no downtime and better performance.', 'Mariam Ali', 'Founder, Orbit Agency'),
                        array('Support team is excellent. Every technical query gets a useful response quickly.', 'Hassan Raza', 'Lead DevOps, NovaStack'),
                    );
                    foreach ($testimonials as $testimonial): ?>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <p class="text-sm text-slate-700 mb-2">"<?php echo html_escape($testimonial[0]); ?>"</p>
                            <p class="text-xs text-slate-500"><?php echo html_escape($testimonial[1]); ?> - <?php echo html_escape($testimonial[2]); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="faqs" class="bg-slate-900 text-white rounded-2xl p-6">
                <h3 class="text-2xl mb-2">Frequently Asked Questions</h3>
                <p class="text-slate-300 mb-5">Quick answers before you deploy.</p>
                <div class="space-y-3">
                    <?php
                    $faqs = array(
                        array('Can I upgrade without downtime?', 'Yes, you can resize your plan from dashboard controls with guided steps.'),
                        array('Do you provide root access?', 'Absolutely. Every VPS includes full root/administrator access.'),
                        array('Is support available all the time?', 'Yes, support is available 24/7 for infrastructure and account issues.'),
                        array('Which OS templates are available?', 'Ubuntu, Debian, AlmaLinux, and multiple additional templates are available.'),
                    );
                    foreach ($faqs as $faq): ?>
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                            <p class="text-sm font-semibold text-white mb-1"><?php echo html_escape($faq[0]); ?></p>
                            <p class="text-sm text-slate-300"><?php echo html_escape($faq[1]); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-5 pb-16">
        <div class="bg-gradient-to-r from-blue-700 to-sky-600 rounded-3xl p-8 md:p-10 text-white flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 shadow-2xl shadow-blue-500/25">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-sky-100 mb-2">Ready to Launch?</p>
                <h3 class="text-3xl mb-2">Build your next project on dependable cloud infrastructure.</h3>
                <p class="text-sky-100">Start your free trial today and deploy your first server in under 60 seconds.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo site_url('register'); ?>" class="px-5 py-3 bg-white text-blue-700 hover:bg-sky-50 rounded-xl font-semibold text-sm">Create Free Account</a>
                <a href="<?php echo site_url('pricing'); ?>" class="px-5 py-3 bg-blue-900/30 hover:bg-blue-900/45 border border-white/20 rounded-xl font-semibold text-sm text-white">Detailed Pricing</a>
            </div>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
