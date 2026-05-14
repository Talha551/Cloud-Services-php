<div class="mb-6"><h1 class="text-2xl font-bold text-white">Tickets</h1><p class="text-slate-400 mt-1">Support requests from clients</p></div>
<div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
    <div class="grid grid-cols-4 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]"><div>ID</div><div>Subject</div><div>Status</div><div>Created</div></div>
    <?php if (empty($rows)): ?><div class="px-5 py-12 text-center text-slate-600">No tickets found</div><?php else: foreach ($rows as $row): ?>
        <?php $status = strtolower((string) $row['status']); $cls = ($status === 'open') ? 'bg-blue-500/15 text-blue-400 border-blue-500/30' : 'bg-slate-500/15 text-slate-400 border-slate-500/30'; ?>
        <div class="grid grid-cols-4 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0"><div class="text-slate-400"><?php echo (int) $row['id']; ?></div><div class="font-medium text-white"><?php echo html_escape($row['subject']); ?></div><div><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border <?php echo $cls; ?>"><?php echo html_escape($row['status']); ?></span></div><div class="text-slate-400"><?php echo html_escape(substr($row['created_at'], 0, 10)); ?></div></div>
    <?php endforeach; endif; ?>
</div>
