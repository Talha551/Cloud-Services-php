<div class="mb-8">
    <h1 class="text-3xl font-bold text-white">Welcome back, <?php echo html_escape($user['full_name']); ?></h1>
    <p class="text-slate-400 mt-2">Here's a summary of your account</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Active Services</p><p class="text-2xl font-bold text-white"><?php echo count($services); ?></p></div><div class="w-10 h-10 rounded-lg flex items-center justify-center text-indigo-400 bg-indigo-500/10"><i data-lucide="server" class="w-[18px] h-[18px]"></i></div></div></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Active Orders</p><p class="text-2xl font-bold text-white"><?php echo count($orders); ?></p></div><div class="w-10 h-10 rounded-lg flex items-center justify-center text-green-400 bg-green-500/10"><i data-lucide="shopping-cart" class="w-[18px] h-[18px]"></i></div></div></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Pending Invoices</p><p class="text-2xl font-bold text-white"><?php $pending = 0; foreach ($invoices as $invoice) { if (strtolower((string) $invoice['status']) !== 'paid') { $pending++; } } echo $pending; ?></p></div><div class="w-10 h-10 rounded-lg flex items-center justify-center text-blue-400 bg-blue-500/10"><i data-lucide="receipt-text" class="w-[18px] h-[18px]"></i></div></div></div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-white">My Services</h3><a href="<?php echo site_url('client/services'); ?>" class="px-3 py-2 bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm rounded-lg transition-colors">View All</a></div>
        <?php if (empty($services)): ?>
            <div class="text-center py-12"><p class="text-slate-500 mb-4">You don't have any active services yet</p><a href="<?php echo site_url('store'); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-lg transition-colors">Browse Plans <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a></div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach (array_slice($services, 0, 5) as $svc): ?>
                    <?php $status = strtolower((string) $svc['status']); $cls = ($status === 'running' || $status === 'active') ? 'bg-green-500/15 text-green-400 border-green-500/30' : (($status === 'stopped') ? 'bg-red-500/15 text-red-400 border-red-500/30' : 'bg-slate-500/15 text-slate-400 border-slate-500/30'); ?>
                    <a href="<?php echo site_url('client/services/'.(int) $svc['id']); ?>" class="flex items-center justify-between p-4 bg-[#0f1117] border border-[#2a2d3e] rounded-lg hover:border-indigo-600 hover:bg-[#0f1117]/80 transition-colors">
                        <div><p class="font-medium text-white"><?php echo html_escape($svc['name'] ? $svc['name'] : $svc['hostname']); ?></p><p class="text-xs text-slate-500 mt-1"><?php echo html_escape($svc['ip_address'] ? $svc['ip_address'] : 'No IP'); ?></p></div>
                        <div class="flex items-center gap-2"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border <?php echo $cls; ?>"><?php echo html_escape($svc['status']); ?></span><i data-lucide="chevron-right" class="w-4 h-4 text-slate-600"></i></div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="space-y-4">
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3><div class="space-y-2"><a href="<?php echo site_url('store'); ?>" class="block w-full text-center px-4 py-2 bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm rounded-lg transition-colors">Order New Service</a><a href="<?php echo site_url('client/invoices'); ?>" class="block w-full text-center px-4 py-2 bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm rounded-lg transition-colors">View Invoices</a></div></div>
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><h3 class="text-sm font-semibold text-white mb-3">Recent Invoices</h3><?php if (empty($invoices)): ?><p class="text-xs text-slate-600">No invoices yet</p><?php else: ?><div class="space-y-2 text-xs"><?php foreach (array_slice($invoices, 0, 3) as $invoice): ?><div class="flex justify-between text-slate-400"><span>Invoice #<?php echo (int) $invoice['id']; ?></span><span class="font-medium text-slate-200">$<?php echo number_format((float) $invoice['total'], 2); ?></span></div><?php endforeach; ?></div><?php endif; ?></div>
    </div>
</div>
