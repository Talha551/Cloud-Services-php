<div class="min-h-screen text-slate-800 flex flex-col">
    <?php $this->load->view('public_site/navbar', array('active_page' => 'faq')); ?>

    <section class="pt-28 pb-10">
        <div class="max-w-5xl mx-auto px-5 text-center">
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mb-3">Frequently Asked Questions</p>
            <h1 class="text-5xl text-slate-900 mb-4">Everything you may want to know before deploying.</h1>
            <p class="text-slate-600 text-lg">Quick answers about plans, operations, support, and infrastructure management.</p>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-5 pb-16">
        <div class="space-y-4">
            <?php foreach ($faqs as $item): ?>
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg text-slate-900 mb-2"><?php echo html_escape($item['q']); ?></h3>
                    <p class="text-slate-600"><?php echo html_escape($item['a']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php $this->load->view('public_site/site_footer'); ?>
</div>
