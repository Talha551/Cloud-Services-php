<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total Servers</p><p class="text-2xl font-bold text-white"><?php echo count($services); ?></p></div><div class="w-10 h-10 rounded-lg flex items-center justify-center text-indigo-400 bg-indigo-500/10"><i data-lucide="server" class="w-[18px] h-[18px]"></i></div></div></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Running</p><p class="text-2xl font-bold text-white"><?php echo (int) $running_count; ?></p></div><div class="w-10 h-10 rounded-lg flex items-center justify-center text-green-400 bg-green-500/10"><i data-lucide="activity" class="w-[18px] h-[18px]"></i></div></div></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Plans</p><p class="text-2xl font-bold text-white"><?php echo count($plans); ?></p></div><div class="w-10 h-10 rounded-lg flex items-center justify-center text-blue-400 bg-blue-500/10"><i data-lucide="package" class="w-[18px] h-[18px]"></i></div></div></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Users</p><p class="text-2xl font-bold text-white"><?php echo count($clients); ?></p></div><div class="w-10 h-10 rounded-lg flex items-center justify-center text-purple-400 bg-purple-500/10"><i data-lucide="users" class="w-[18px] h-[18px]"></i></div></div></div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 lg:col-span-2">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2"><i data-lucide="server" class="w-3.5 h-3.5 text-indigo-400"></i> Recent Servers</h3>
        <?php if (empty($services)): ?>
            <p class="text-sm text-slate-600 text-center py-8">No servers found</p>
        <?php else: ?>
            <?php foreach (array_slice($services, 0, 8) as $server): ?>
                <?php $status = strtolower((string) $server['status']); $dot = ($status === 'running' || $status === 'active') ? 'bg-green-400' : (($status === 'stopped') ? 'bg-red-400' : 'bg-slate-400'); ?>
                <div class="flex items-center justify-between py-2.5 border-b border-[#1e2130] last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full <?php echo $dot; ?>"></div>
                        <div>
                            <p class="text-sm text-slate-200"><?php echo html_escape($server['name']); ?></p>
                            <p class="text-xs text-slate-600"><?php echo html_escape($server['ip_address'] ? $server['ip_address'] : 'No IP'); ?></p>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500 capitalize"><?php echo html_escape($server['status']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="space-y-4">
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5">
            <h3 class="text-sm font-semibold text-white mb-3 flex items-center gap-2"><i data-lucide="activity" class="w-3.5 h-3.5 text-green-400"></i> Server Status</h3>
            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between"><span class="text-slate-400">Running</span><span class="text-green-400 font-medium"><?php echo (int) $running_count; ?></span></div>
                <div class="flex items-center justify-between"><span class="text-slate-400">Stopped</span><span class="text-red-400 font-medium"><?php echo (int) $stopped_count; ?></span></div>
                <div class="flex items-center justify-between"><span class="text-slate-400">Other</span><span class="text-slate-400 font-medium"><?php echo count($services) - (int) $running_count - (int) $stopped_count; ?></span></div>
            </div>
        </div>
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5">
            <h3 class="text-sm font-semibold text-white mb-3 flex items-center gap-2"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-400"></i> Locations</h3>
            <div class="space-y-1">
                <?php foreach (array('Frankfurt', 'London', 'Amsterdam', 'New York') as $location): ?>
                    <div class="text-sm text-slate-400"><?php echo $location; ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
