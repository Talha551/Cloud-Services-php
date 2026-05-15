<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Users & Clients</h1>
    <p class="text-slate-400 mt-1"><?php echo count($rows); ?> total accounts</p>
</div>
<?php if (!empty($flash_success)): ?>
    <div class="mb-4 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-300"><?php echo html_escape($flash_success); ?></div>
<?php endif; ?>
<?php if (!empty($flash_error)): ?>
    <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300"><?php echo html_escape($flash_error); ?></div>
<?php endif; ?>
<div class="app-card overflow-hidden">
    <div class="grid grid-cols-7 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">
        <div>ID</div><div>Name</div><div>Email</div><div>Role</div><div>Status</div><div>Credits</div><div>Actions</div>
    </div>
    <?php foreach ($rows as $row): ?>
        <?php $is_client = isset($row['role']) && $row['role'] === 'client'; ?>
        <div class="grid grid-cols-7 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0 gap-3 items-center">
            <div class="text-slate-400 whitespace-nowrap"><?php echo (int) $row['id']; ?></div>
            <div class="font-medium text-white truncate max-w-[160px]" title="<?php echo html_escape($row['name']); ?>">
                <?php if ($is_client): ?>
                    <a href="<?php echo site_url('admin/clients/'.(int) $row['id']); ?>" class="hover:text-indigo-300 transition-colors truncate" title="<?php echo html_escape($row['name']); ?>"><?php echo html_escape($row['name']); ?></a>
                <?php else: ?>
                    <?php echo html_escape($row['name']); ?>
                <?php endif; ?>
            </div>
            <div class="text-slate-400 truncate max-w-[180px]" title="<?php echo html_escape($row['email']); ?>"><?php echo html_escape($row['email']); ?></div>
            <div><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border bg-green-500/15 text-green-400 border-green-500/30"><?php echo html_escape($row['role']); ?></span></div>
            <div><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border bg-emerald-500/15 text-emerald-300 border-emerald-500/30"><?php echo html_escape(isset($row['status']) ? $row['status'] : 'active'); ?></span></div>
            <div class="text-emerald-300 font-medium whitespace-nowrap">$<?php echo number_format((float) (isset($row['credit_balance']) ? $row['credit_balance'] : 0), 2); ?></div>
            <div>
                <?php if ($is_client): ?>
                    <form method="post" action="<?php echo site_url('admin/clients/'.(int) $row['id'].'/credits/send'); ?>" class="flex items-center gap-2 flex-wrap">
                        <input type="number" step="0.01" min="0.01" name="amount" required placeholder="Amount" class="w-24 bg-[#0f1117] border border-[#2a2d3e] rounded px-2 py-1 text-xs text-slate-200">
                        <input type="text" name="note" placeholder="Note" class="w-28 bg-[#0f1117] border border-[#2a2d3e] rounded px-2 py-1 text-xs text-slate-200">
                        <button type="submit" class="px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-500 text-white text-xs">Send</button>
                    </form>
                <?php else: ?>
                    <span class="text-slate-600 text-xs">No client actions</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
