<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Support Tickets</h1>
        <p class="text-slate-400 mt-1">Create and track your support requests</p>
    </div>

    <?php if (!empty($flash_success)): ?>
        <div class="mb-4 rounded-lg border border-emerald-500/50 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            <?php echo html_escape($flash_success); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($flash_error)): ?>
        <div class="mb-4 rounded-lg border border-rose-500/50 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
            <?php echo html_escape($flash_error); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 lg:col-span-1">
            <h3 class="text-lg font-semibold text-white mb-3">Open New Ticket</h3>
            <form method="post" action="<?php echo site_url('client/tickets/create'); ?>" class="space-y-3">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Subject</label>
                    <input name="subject" type="text" minlength="8" maxlength="200" required placeholder="Example: VPS rebooted and network unreachable" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm placeholder-slate-600">
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-lg transition-colors">Create Ticket</button>
            </form>
        </div>

        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden lg:col-span-2">
            <div class="grid grid-cols-3 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">
                <div>ID</div>
                <div>Subject</div>
                <div>Created</div>
            </div>
            <?php if (empty($rows)): ?>
                <div class="px-5 py-12 text-center text-slate-600">No tickets yet</div>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <div class="grid grid-cols-3 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                        <div class="text-slate-400">#<?php echo (int) $row['id']; ?></div>
                        <div class="text-white font-medium"><?php echo html_escape($row['subject']); ?></div>
                        <div class="text-slate-400"><?php echo html_escape(substr((string) $row['created_at'], 0, 19)); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
