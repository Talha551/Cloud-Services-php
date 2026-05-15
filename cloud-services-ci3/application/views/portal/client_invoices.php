<div class="mb-6"><h1 class="text-2xl font-bold text-white">My Invoices</h1><p class="text-slate-400 mt-1">View and manage your billing invoices</p></div>
<div class="mb-4 rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">Demo billing mode is active. Payments are simulated for testing and no real money is charged.</div>
<div class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">Wallet Credits Available: <strong>$<?php echo number_format((float) (isset($credit_balance) ? $credit_balance : 0), 2); ?></strong></div>
<?php if ($this->session->flashdata('success')): ?><div class="mb-4 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-300"><?php echo html_escape($this->session->flashdata('success')); ?></div><?php endif; ?>
<?php if ($this->session->flashdata('error')): ?><div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300"><?php echo html_escape($this->session->flashdata('error')); ?></div><?php endif; ?>
<?php if (empty($rows)): ?>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><div class="text-center py-12"><i data-lucide="receipt-text" class="w-12 h-12 mx-auto text-slate-600 mb-4"></i><p class="text-slate-500">No invoices yet</p></div></div>
<?php else: ?>
    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
        <div class="grid grid-cols-8 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]"><div>Invoice #</div><div>Type</div><div>Amount</div><div>Date</div><div>Status</div><div>Provision</div><div>Payment</div><div>Action</div></div>
        <?php foreach ($rows as $row): ?>
            <?php
                $status = strtolower((string) $row['status']);
                $cls = ($status === 'paid') ? 'bg-green-500/15 text-green-400 border-green-500/30' : 'bg-blue-500/15 text-blue-400 border-blue-500/30';
                $invoice_type = isset($row['invoice_type']) && trim((string) $row['invoice_type']) !== '' ? strtolower((string) $row['invoice_type']) : 'service_order';
                $type_label = $invoice_type === 'credit_topup' ? 'Credit Topup' : 'Service Order';
                $can_pay_with_credits = $status === 'unpaid' && $invoice_type === 'service_order' && ((float) (isset($credit_balance) ? $credit_balance : 0) >= (float) $row['total']);
            ?>
            <div class="grid grid-cols-8 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
                <div class="text-slate-400"><?php echo (int) $row['id']; ?></div>
                <div class="text-slate-300"><?php echo html_escape($type_label); ?></div>
                <div class="text-slate-300">$<?php echo number_format((float) $row['total'], 2); ?></div>
                <div class="text-slate-400"><?php echo html_escape(substr($row['created_at'], 0, 10)); ?></div>
                <div><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border <?php echo $cls; ?>"><?php echo html_escape($row['status']); ?></span></div>
                <div class="text-slate-400"><?php echo html_escape(isset($row['provisioning_status']) && $row['provisioning_status'] ? $row['provisioning_status'] : 'awaiting_payment'); ?></div>
                <div class="text-slate-400 text-xs"><?php echo !empty($row['transaction_id']) ? html_escape((string) $row['payment_method'].' | '.(string) $row['transaction_id']) : '-'; ?></div>
                <div>
                    <?php if ($status === 'unpaid'): ?>
                        <div class="flex flex-wrap items-center gap-2">
                            <form method="post" action="<?php echo site_url('client/invoices/'.(int) $row['id'].'/pay'); ?>">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs transition-colors">Pay (Demo)</button>
                            </form>
                            <?php if ($can_pay_with_credits): ?>
                                <form method="post" action="<?php echo site_url('client/invoices/'.(int) $row['id'].'/pay-credits'); ?>">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 text-xs transition-colors">Pay with Credits</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="text-xs text-green-400">Paid</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
