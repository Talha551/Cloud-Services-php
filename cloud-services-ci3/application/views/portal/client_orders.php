<div class="mb-6"><h1 class="text-2xl font-bold text-white">My Orders</h1><p class="text-slate-400 mt-1">Track your service orders</p></div>
<?php if (empty($rows)): ?>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="text-center py-12"><i data-lucide="shopping-cart" class="w-12 h-12 mx-auto text-slate-600 mb-4"></i><p class="text-slate-500">No orders yet</p></div></div>
<?php else: ?>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
        <div class="grid grid-cols-6 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]"><div>Order #</div><div>Product</div><div>Amount</div><div>Status</div><div>Invoice</div><div>Created</div></div>
        <?php foreach ($rows as $row): ?>
            <?php $status = strtolower((string) $row['status']); $cls = ($status === 'pending') ? 'bg-blue-500/15 text-blue-400 border-blue-500/30' : 'bg-green-500/15 text-green-400 border-green-500/30'; ?>
            <div class="grid grid-cols-6 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0"><div class="text-slate-400"><?php echo (int) $row['id']; ?></div><div class="font-medium text-white"><?php echo html_escape($row['product_name'] ? $row['product_name'] : '—'); ?></div><div class="text-slate-300">$<?php echo number_format((float) $row['total'], 2); ?></div><div><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border <?php echo $cls; ?>"><?php echo html_escape($row['status']); ?></span></div><div class="text-slate-400"><?php echo html_escape(isset($row['invoice_status']) ? $row['invoice_status'] : '—'); ?></div><div class="text-slate-400"><?php echo html_escape(substr($row['created_at'], 0, 10)); ?></div></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
