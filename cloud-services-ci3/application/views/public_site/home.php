<div class="min-h-screen bg-[#0a0c12] text-slate-200 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'home')); ?>

    <section class="relative pt-32 pb-24 overflow-hidden">
        <div class="absolute rounded-full blur-3xl opacity-20 pointer-events-none w-[600px] h-[600px] bg-indigo-600 -top-40 -left-40"></div>
        <div class="absolute rounded-full blur-3xl opacity-20 pointer-events-none w-[400px] h-[400px] bg-purple-600 top-20 right-0"></div>

        <div class="relative max-w-6xl mx-auto px-5 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 rounded-full text-xs text-indigo-400 mb-6">
                <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></div>
                Powered by SolusVM2 - Enterprise-grade infrastructure
            </div>

            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight tracking-tight">
                Cloud Servers
                <br>
                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Built for Speed</span>
            </h1>

            <p class="text-lg text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Deploy high-performance KVM virtual machines in seconds.
                Full root access, NVMe SSD storage, and 24/7 monitoring
                - all managed from one powerful dashboard.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?php echo site_url('register'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
                    Deploy Now <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
                <a href="<?php echo site_url('features'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm font-medium rounded-xl transition-colors">
                    Explore Features
                </a>
            </div>

            <div class="flex items-center justify-center gap-12 mt-16 pt-12 border-t border-white/5 flex-wrap">
                <div class="text-center"><p class="text-3xl font-bold text-white mb-1">99.9%</p><p class="text-xs text-slate-500">Uptime SLA</p></div>
                <div class="text-center"><p class="text-3xl font-bold text-white mb-1">&lt;1s</p><p class="text-xs text-slate-500">Deploy Time</p></div>
                <div class="text-center"><p class="text-3xl font-bold text-white mb-1">NVMe</p><p class="text-xs text-slate-500">SSD Storage</p></div>
                <div class="text-center"><p class="text-3xl font-bold text-white mb-1">24/7</p><p class="text-xs text-slate-500">Support</p></div>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 pb-16">
        <div class="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 bg-white/[0.03] border-b border-white/[0.06]">
                <div class="w-3 h-3 rounded-full bg-red-500/60"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500/60"></div>
                <div class="w-3 h-3 rounded-full bg-green-500/60"></div>
                <div class="flex-1 mx-4 bg-white/[0.04] rounded-md px-3 py-1 text-xs text-slate-600">cloudpanel.io/admin</div>
            </div>
            <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-4"><p class="text-xs text-slate-600 mb-1">Total Servers</p><div class="flex items-center justify-between"><p class="text-xl font-bold text-indigo-400">24</p><i data-lucide="server" class="w-3.5 h-3.5 text-slate-700"></i></div></div>
                <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-4"><p class="text-xs text-slate-600 mb-1">Running</p><div class="flex items-center justify-between"><p class="text-xl font-bold text-green-400">21</p><i data-lucide="activity" class="w-3.5 h-3.5 text-slate-700"></i></div></div>
                <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-4"><p class="text-xs text-slate-600 mb-1">CPU Usage</p><div class="flex items-center justify-between"><p class="text-xl font-bold text-blue-400">34%</p><i data-lucide="cpu" class="w-3.5 h-3.5 text-slate-700"></i></div></div>
                <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-4"><p class="text-xs text-slate-600 mb-1">Storage Used</p><div class="flex items-center justify-between"><p class="text-xl font-bold text-yellow-400">1.2TB</p><i data-lucide="hard-drive" class="w-3.5 h-3.5 text-slate-700"></i></div></div>
            </div>
            <div class="mx-6 mb-6 bg-white/[0.02] border border-white/[0.05] rounded-xl overflow-hidden">
                <div class="grid grid-cols-5 px-4 py-2 bg-white/[0.03] text-xs text-slate-600 font-medium">
                    <span>Name</span><span>Status</span><span>IP</span><span>Plan</span><span>Location</span>
                </div>
                <?php
                $rows = array(
                    array('web-prod-01', 'Running', '185.x.x.1', 'Pro 4', 'Frankfurt'),
                    array('db-primary', 'Running', '185.x.x.2', 'Pro 8', 'Frankfurt'),
                    array('api-server', 'Running', '185.x.x.3', 'Starter', 'London'),
                    array('staging-01', 'Stopped', '185.x.x.4', 'Starter', 'Amsterdam'),
                );
                foreach ($rows as $row): ?>
                    <div class="grid grid-cols-5 px-4 py-2.5 text-xs text-slate-400 border-t border-white/[0.04] hover:bg-white/[0.02]">
                        <span class="text-white font-medium"><?php echo html_escape($row[0]); ?></span>
                        <span class="<?php echo ($row[1] === 'Running') ? 'text-green-400' : 'text-red-400'; ?>"><?php echo html_escape($row[1]); ?></span>
                        <span class="font-mono"><?php echo html_escape($row[2]); ?></span>
                        <span><?php echo html_escape($row[3]); ?></span>
                        <span><?php echo html_escape($row[4]); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-5 py-20">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Everything you need</h2>
            <p class="text-slate-500 max-w-xl mx-auto">A complete cloud management platform with every tool to deploy, manage, and scale your infrastructure.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            $features = array(
                array('zap', 'bg-indigo-500/10 text-indigo-400', 'Instant Deployment', 'Spin up VPS instances in seconds. KVM-powered virtual machines with dedicated resources and full root access.'),
                array('shield', 'bg-green-500/10 text-green-400', 'Automatic Backups', 'Daily automated backups with one-click restore. Keep your data safe with configurable backup schedules.'),
                array('activity', 'bg-blue-500/10 text-blue-400', 'Real-Time Monitoring', 'Live CPU, RAM, disk, and network metrics. Get instant alerts on performance anomalies before they impact your users.'),
                array('network', 'bg-purple-500/10 text-purple-400', 'Advanced Networking', 'IPv4 and IPv6 support, VPC networks, custom IP blocks. Full control over your networking topology.'),
                array('hard-drive', 'bg-yellow-500/10 text-yellow-400', 'Flexible Storage', 'NVMe SSD storage with expandable volumes. Attach additional disks on the fly without downtime.'),
                array('globe', 'bg-pink-500/10 text-pink-400', 'Multiple Locations', 'Deploy globally across multiple data centers. Choose the region closest to your users for minimal latency.'),
            );
            foreach ($features as $feature): ?>
                <div class="bg-white/[0.03] border border-white/[0.07] rounded-2xl p-6 hover:bg-white/[0.05] transition-colors">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 <?php echo $feature[1]; ?>"><i data-lucide="<?php echo $feature[0]; ?>" class="w-[18px] h-[18px]"></i></div>
                    <h3 class="text-sm font-semibold text-white mb-2"><?php echo html_escape($feature[2]); ?></h3>
                    <p class="text-xs text-slate-500 leading-relaxed"><?php echo html_escape($feature[3]); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="relative overflow-hidden py-20">
        <div class="absolute rounded-full blur-3xl opacity-20 pointer-events-none w-[500px] h-[500px] bg-indigo-700 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="relative max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-4">Why choose CloudPanel?</h2>
                    <p class="text-slate-500 mb-8 leading-relaxed">Built on SolusVM2 - the most advanced VPS management platform available. We give you full control with an intuitive interface.</p>
                    <ul class="space-y-3">
                        <?php
                        $checks = array(
                            'Deploy KVM VMs in under 60 seconds',
                            'Full root access - you own your server',
                            'Automatic daily backups included',
                            'Instant VNC console access',
                            'IPv4 + IPv6 support on all plans',
                            'No setup fees or hidden charges'
                        );
                        foreach ($checks as $check): ?>
                            <li class="flex items-center gap-2.5 text-sm text-slate-400"><i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-indigo-400 shrink-0"></i><?php echo html_escape($check); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo site_url('pricing'); ?>" class="inline-flex items-center gap-2 mt-8 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition-colors">View Pricing <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                </div>
                <div class="space-y-3">
                    <?php
                    $uptime = array(
                        array('web-prod-01', '99.98%', '2ms'),
                        array('db-primary', '99.99%', '1ms'),
                        array('api-server', '99.95%', '4ms'),
                        array('cdn-node', '100%', '1ms'),
                    );
                    foreach ($uptime as $server): ?>
                        <div class="flex items-center justify-between bg-white/[0.03] border border-white/[0.06] rounded-xl px-4 py-3">
                            <div class="flex items-center gap-3"><div class="w-2 h-2 rounded-full bg-green-400 shadow-lg shadow-green-500/50"></div><span class="text-sm text-slate-300"><?php echo html_escape($server[0]); ?></span></div>
                            <div class="flex items-center gap-6 text-xs">
                                <div class="text-right"><p class="text-slate-500">Uptime</p><p class="text-green-400 font-medium"><?php echo html_escape($server[1]); ?></p></div>
                                <div class="text-right"><p class="text-slate-500">Latency</p><p class="text-slate-300 font-medium"><?php echo html_escape($server[2]); ?></p></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
