<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Servers</h1>
            <p class="text-slate-400 mt-1"><?php echo count($rows); ?> virtual servers</p>
        </div>
        <a href="<?php echo site_url('admin/servers/create'); ?>" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-lg transition-colors">Create Server</a>
    </div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
        <div class="grid grid-cols-7 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]"><div>Name</div><div>Client</div><div>Status</div><div>IP</div><div>OS</div><div>Plan</div><div></div></div>
        <?php foreach ($rows as $row): ?>
            <?php $status = strtolower((string) $row['status']); $cls = ($status === 'running' || $status === 'active') ? 'bg-green-500/15 text-green-400 border-green-500/30' : (($status === 'stopped') ? 'bg-red-500/15 text-red-400 border-red-500/30' : 'bg-slate-500/15 text-slate-400 border-slate-500/30'); ?>
            <div class="grid grid-cols-7 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                <div class="font-medium text-white"><?php echo html_escape($row['name']); ?></div>
                <div class="text-slate-400"><?php echo html_escape(!empty($row['client_name']) ? $row['client_name'] : (!empty($row['client_email']) ? $row['client_email'] : ('User #'.(int) $row['user_id']))); ?></div>
                <div><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border <?php echo $cls; ?>"><?php echo html_escape($row['status']); ?></span></div>
                <div class="font-mono text-slate-300"><?php echo html_escape($row['ip_address'] ? $row['ip_address'] : '—'); ?></div>
                <div class="text-slate-400"><?php echo html_escape($row['os'] ? $row['os'] : '—'); ?></div>
                <div class="text-slate-400"><?php echo html_escape(isset($row['plan_name']) ? $row['plan_name'] : '—'); ?></div>
                <div class="text-right"><a href="<?php echo site_url('admin/servers/'.(int) $row['id']); ?>" class="text-indigo-400 hover:text-indigo-300 text-xs">View</a></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
