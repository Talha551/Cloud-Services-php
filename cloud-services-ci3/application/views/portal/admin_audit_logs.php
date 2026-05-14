<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Audit Logs</h1>
    <p class="text-slate-400 mt-1">Operational activity trail for admin and automation actions (latest 300)</p>
</div>

<div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
    <div class="grid grid-cols-5 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">
        <div>ID</div>
        <div>User ID</div>
        <div>Action</div>
        <div>Created</div>
        <div>Details</div>
    </div>

    <?php if (empty($rows)): ?>
        <div class="px-5 py-12 text-center text-slate-600">No audit logs found</div>
    <?php else: ?>
        <?php foreach ($rows as $row): ?>
            <div class="grid grid-cols-5 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                <div class="text-slate-400"><?php echo (int) $row['id']; ?></div>
                <div class="text-slate-400"><?php echo isset($row['user_id']) && $row['user_id'] !== '' ? (int) $row['user_id'] : '-'; ?></div>
                <div class="text-slate-200 font-medium"><?php echo html_escape((string) $row['action']); ?></div>
                <div class="text-slate-400"><?php echo html_escape(substr((string) $row['created_at'], 0, 19)); ?></div>
                <div class="text-slate-400 text-xs truncate" title="<?php echo html_escape((string) $row['details']); ?>"><?php echo html_escape((string) $row['details']); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
