<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Wallet Credits</h1>
    <p class="text-slate-400 mt-1">Buy credits and use them to pay VPS invoices instantly.</p>
</div>

<?php if (!empty($flash_success)): ?>
    <div class="mb-4 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-300"><?php echo html_escape($flash_success); ?></div>
<?php endif; ?>
<?php if (!empty($flash_error)): ?>
    <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300"><?php echo html_escape($flash_error); ?></div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 lg:col-span-1">
        <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Current Balance</p>
        <p class="text-3xl font-bold text-emerald-300">$<?php echo number_format((float) $balance, 2); ?></p>
        <p class="text-xs text-slate-500 mt-2">Credits can be used to pay unpaid service invoices.</p>
    </div>

    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 lg:col-span-2">
        <h3 class="text-sm font-semibold text-white mb-3">Buy Credits</h3>
        <form method="post" action="<?php echo site_url('client/credits/topup'); ?>" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs text-slate-400 mb-1">Amount (USD)</label>
                <input type="number" step="0.01" min="1" name="amount" required class="w-44 bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm" placeholder="e.g. 25.00">
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm">Create Topup Invoice</button>
            <a href="<?php echo site_url('client/invoices'); ?>" class="px-4 py-2 rounded-lg bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm">Go to Invoices</a>
        </form>
    </div>
</div>

<div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
    <div class="px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">Credit Transactions</div>
    <?php if (empty($rows)): ?>
        <div class="px-5 py-10 text-center text-slate-500 text-sm">No credit transactions yet.</div>
    <?php else: ?>
        <?php foreach ($rows as $row): ?>
            <?php
                $amount = (float) $row['amount'];
                $amount_class = $amount >= 0 ? 'text-emerald-300' : 'text-red-300';
            ?>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 px-5 py-3 border-b border-[#1e2130] last:border-0 text-sm">
                <div class="text-slate-400">#<?php echo (int) $row['id']; ?></div>
                <div class="text-slate-300"><?php echo html_escape(isset($row['type']) ? $row['type'] : '-'); ?></div>
                <div class="<?php echo $amount_class; ?> font-medium"><?php echo ($amount >= 0 ? '+' : '-'); ?>$<?php echo number_format(abs($amount), 2); ?></div>
                <div class="text-slate-400"><?php echo html_escape(isset($row['note']) && $row['note'] ? $row['note'] : '-'); ?></div>
                <div class="text-slate-500 text-xs"><?php echo html_escape(substr((string) $row['created_at'], 0, 19)); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
