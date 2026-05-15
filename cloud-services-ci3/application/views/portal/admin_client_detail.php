<div class="mb-6 flex items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Client Profile & History</h1>
        <p class="text-slate-400 mt-1">Complete activity timeline for <?php echo html_escape($client['full_name']); ?></p>
    </div>
    <a href="<?php echo site_url('admin/clients'); ?>" class="px-3 py-2 rounded-lg bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm">Back to Clients</a>
</div>

<div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div>
            <p class="text-slate-500">Name</p>
            <p class="text-white font-medium"><?php echo html_escape($client['full_name']); ?></p>
        </div>
        <div>
            <p class="text-slate-500">Email</p>
            <p class="text-slate-300"><?php echo html_escape($client['email']); ?></p>
        </div>
        <div>
            <p class="text-slate-500">Joined</p>
            <p class="text-slate-300"><?php echo html_escape($client_created_at !== '' ? substr($client_created_at, 0, 19) : '-'); ?></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 mb-6">
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-lg p-3"><p class="text-xs text-slate-500">Services</p><p class="text-lg text-white font-semibold"><?php echo (int) $stats['services']; ?></p></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-lg p-3"><p class="text-xs text-slate-500">Orders</p><p class="text-lg text-white font-semibold"><?php echo (int) $stats['orders']; ?></p></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-lg p-3"><p class="text-xs text-slate-500">Invoices</p><p class="text-lg text-white font-semibold"><?php echo (int) $stats['invoices']; ?></p></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-lg p-3"><p class="text-xs text-slate-500">Tickets</p><p class="text-lg text-white font-semibold"><?php echo (int) $stats['tickets']; ?></p></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-lg p-3"><p class="text-xs text-slate-500">Domains</p><p class="text-lg text-white font-semibold"><?php echo (int) $stats['domains']; ?></p></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-lg p-3"><p class="text-xs text-slate-500">Credits</p><p class="text-lg text-emerald-300 font-semibold">$<?php echo number_format((float) $stats['credits'], 2); ?></p></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-lg p-3"><p class="text-xs text-slate-500">Paid</p><p class="text-lg text-emerald-300 font-semibold">$<?php echo number_format((float) $stats['paid_total'], 2); ?></p></div>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-lg p-3"><p class="text-xs text-slate-500">Unpaid</p><p class="text-lg text-amber-300 font-semibold">$<?php echo number_format((float) $stats['unpaid_total'], 2); ?></p></div>
</div>

<div class="space-y-6">
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
        <div class="px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">Services</div>
        <?php if (empty($services)): ?><div class="px-5 py-4 text-sm text-slate-500">No services.</div><?php else: ?>
            <?php foreach ($services as $row): ?>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                    <div class="text-slate-400">#<?php echo (int) $row['id']; ?></div>
                    <div class="text-white"><?php echo html_escape(isset($row['hostname']) ? $row['hostname'] : ''); ?></div>
                    <div class="text-slate-300"><?php echo html_escape(isset($row['plan_name']) ? $row['plan_name'] : '-'); ?></div>
                    <div class="text-slate-300"><?php echo html_escape(isset($row['status']) ? $row['status'] : '-'); ?></div>
                    <div><a href="<?php echo site_url('admin/servers/'.(int) $row['id']); ?>" class="text-indigo-300 hover:text-indigo-200">Open</a></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
        <div class="px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">Orders</div>
        <?php if (empty($orders)): ?><div class="px-5 py-4 text-sm text-slate-500">No orders.</div><?php else: ?>
            <?php foreach ($orders as $row): ?>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                    <div class="text-slate-400">#<?php echo (int) $row['id']; ?></div>
                    <div class="text-white"><?php echo html_escape(isset($row['product_name']) ? $row['product_name'] : '-'); ?></div>
                    <div class="text-slate-300">$<?php echo number_format((float) (isset($row['total']) ? $row['total'] : 0), 2); ?></div>
                    <div class="text-slate-300"><?php echo html_escape(isset($row['status']) ? $row['status'] : '-'); ?></div>
                    <div class="text-slate-400"><?php echo !empty($row['invoice_id']) ? (int) $row['invoice_id'] : '-'; ?></div>
                    <div class="text-slate-500"><?php echo html_escape(isset($row['created_at']) ? substr($row['created_at'], 0, 19) : '-'); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
        <div class="px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">Invoices</div>
        <?php if (empty($invoices)): ?><div class="px-5 py-4 text-sm text-slate-500">No invoices.</div><?php else: ?>
            <?php foreach ($invoices as $row): ?>
                <div class="grid grid-cols-1 md:grid-cols-7 gap-3 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                    <div class="text-slate-400">#<?php echo (int) $row['id']; ?></div>
                    <div class="text-slate-300"><?php echo html_escape(isset($row['invoice_type']) && $row['invoice_type'] !== '' ? $row['invoice_type'] : 'service_order'); ?></div>
                    <div class="text-slate-300">$<?php echo number_format((float) (isset($row['total']) ? $row['total'] : 0), 2); ?></div>
                    <div class="text-slate-300"><?php echo html_escape(isset($row['status']) ? $row['status'] : '-'); ?></div>
                    <div class="text-slate-400"><?php echo html_escape(isset($row['payment_method']) ? $row['payment_method'] : '-'); ?></div>
                    <div class="text-slate-400"><?php echo html_escape(isset($row['transaction_id']) ? $row['transaction_id'] : '-'); ?></div>
                    <div class="text-slate-500"><?php echo html_escape(isset($row['created_at']) ? substr($row['created_at'], 0, 19) : '-'); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
        <div class="px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">Credit Transactions</div>
        <?php if (empty($credit_transactions)): ?><div class="px-5 py-4 text-sm text-slate-500">No credit transactions.</div><?php else: ?>
            <?php foreach ($credit_transactions as $row): ?>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                    <div class="text-slate-400">#<?php echo (int) $row['id']; ?></div>
                    <div class="text-slate-300"><?php echo html_escape(isset($row['type']) ? $row['type'] : '-'); ?></div>
                    <div class="<?php echo ((float) $row['amount'] >= 0) ? 'text-emerald-300' : 'text-red-300'; ?>">$<?php echo number_format((float) $row['amount'], 2); ?></div>
                    <div class="text-slate-300"><?php echo html_escape(isset($row['note']) ? $row['note'] : '-'); ?></div>
                    <div class="text-slate-400"><?php echo !empty($row['admin_email']) ? html_escape($row['admin_email']) : '-'; ?></div>
                    <div class="text-slate-500"><?php echo html_escape(isset($row['created_at']) ? substr($row['created_at'], 0, 19) : '-'); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
            <div class="px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">Support Tickets</div>
            <?php if (empty($tickets)): ?><div class="px-5 py-4 text-sm text-slate-500">No tickets.</div><?php else: ?>
                <?php foreach ($tickets as $row): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                        <div class="text-slate-400">#<?php echo (int) $row['id']; ?></div>
                        <div class="text-slate-300"><?php echo html_escape(isset($row['subject']) ? $row['subject'] : '-'); ?></div>
                        <div class="text-slate-500"><?php echo html_escape(isset($row['status']) ? $row['status'] : '-'); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
            <div class="px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">Domains</div>
            <?php if (empty($domains)): ?><div class="px-5 py-4 text-sm text-slate-500">No domains.</div><?php else: ?>
                <?php foreach ($domains as $row): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                        <div class="text-slate-400">#<?php echo (int) $row['id']; ?></div>
                        <div class="text-slate-300"><?php echo html_escape(isset($row['domain']) ? $row['domain'] : '-'); ?></div>
                        <div class="text-slate-500"><?php echo html_escape(isset($row['status']) ? $row['status'] : '-'); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
        <div class="px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">Audit Logs</div>
        <?php if (empty($audit_logs)): ?><div class="px-5 py-4 text-sm text-slate-500">No audit logs.</div><?php else: ?>
            <?php foreach ($audit_logs as $row): ?>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                    <div class="text-slate-400"><?php echo html_escape(isset($row['created_at']) ? substr($row['created_at'], 0, 19) : '-'); ?></div>
                    <div class="text-slate-300"><?php echo html_escape(isset($row['action']) ? $row['action'] : '-'); ?></div>
                    <div class="text-slate-400"><?php echo html_escape(isset($row['ip_address']) ? $row['ip_address'] : '-'); ?></div>
                    <div class="text-slate-500"><?php echo html_escape(isset($row['details']) ? $row['details'] : '-'); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
