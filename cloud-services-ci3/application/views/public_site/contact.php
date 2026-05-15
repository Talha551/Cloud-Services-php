<div class="min-h-screen text-slate-800 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'contact')); ?>

    <section class="pt-28 pb-12">
        <div class="max-w-7xl mx-auto px-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-3">Contact</p>
                    <h1 class="text-5xl text-slate-900 mb-4">Talk to sales or support.</h1>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6">Need help choosing a plan, migrating workloads, or clarifying billing? Send us a message and our team will respond shortly.</p>
                    <div class="space-y-3">
                        <div class="bg-white border border-slate-200 rounded-xl p-4 text-sm text-slate-700 inline-flex items-center gap-2.5 w-full"><i data-lucide="mail" class="w-4 h-4 text-sky-600"></i> support@cloudpanel.local</div>
                        <div class="bg-white border border-slate-200 rounded-xl p-4 text-sm text-slate-700 inline-flex items-center gap-2.5 w-full"><i data-lucide="headset" class="w-4 h-4 text-sky-600"></i> 24/7 technical support for active customers</div>
                        <div class="bg-white border border-slate-200 rounded-xl p-4 text-sm text-slate-700 inline-flex items-center gap-2.5 w-full"><i data-lucide="map-pin" class="w-4 h-4 text-sky-600"></i> Regions: Frankfurt, London, Amsterdam, Singapore</div>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?php echo html_escape($this->session->flashdata('success')); ?></div>
                    <?php endif; ?>
                    <form method="post" action="<?php echo site_url('contact'); ?>" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200 focus:border-sky-400" placeholder="Your name" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                            <input type="email" name="email" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200 focus:border-sky-400" placeholder="you@example.com" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Subject</label>
                            <input type="text" name="subject" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200 focus:border-sky-400" placeholder="How can we help?" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Message</label>
                            <textarea name="message" rows="5" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200 focus:border-sky-400" placeholder="Write your message" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white py-2.5 rounded-xl text-sm font-semibold">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
