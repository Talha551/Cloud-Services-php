<div>
    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo site_url('store'); ?>" class="p-1.5 rounded-lg hover:bg-[#1e2130] text-slate-400 hover:text-slate-200 transition-colors"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
        <h1 class="text-2xl font-bold text-white">Checkout</h1>
    </div>
    <?php if (!$plan): ?>
        <div class="text-center py-12"><p class="text-slate-500 mb-4">No plan selected</p><a href="<?php echo site_url('store'); ?>" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-lg transition-colors">Back to Store</a></div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 lg:col-span-2">
                <h3 class="text-lg font-semibold text-white mb-6">Configure Your VPS</h3>
                <form method="post" action="<?php echo site_url('orders/create'); ?>" class="space-y-4">
                    <input type="hidden" name="plan_id" value="<?php echo (int) $plan['id']; ?>">
                    <div><label class="block text-xs font-medium text-slate-400 mb-1.5">Hostname *</label><input required name="hostname" placeholder="server.example.com" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600"></div>

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Location</label>
                        <select name="location_id" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200">
                            <?php if (!empty($location_options) && is_array($location_options)): ?>
                                <?php foreach ($location_options as $loc_id => $loc_label): ?>
                                    <option value="<?php echo (int) $loc_id; ?>"><?php echo html_escape($loc_label); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Operating System</label>
                        <select name="os_id" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200">
                            <?php if (!empty($os_options) && is_array($os_options)): ?>
                                <?php foreach ($os_options as $os_id => $os_label): ?>
                                    <option value="<?php echo (int) $os_id; ?>"><?php echo html_escape($os_label); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Application (optional)</label>
                        <select name="application_id" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200">
                            <option value="0">No application</option>
                            <?php if (!empty($application_options) && is_array($application_options)): ?>
                                <?php foreach ($application_options as $app_id => $app_label): ?>
                                    <option value="<?php echo (int) $app_id; ?>"><?php echo html_escape($app_label); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div><label class="block text-xs font-medium text-slate-400 mb-1.5">Root Password <span class="text-slate-600">(optional)</span></label><input name="root_password" type="text" minlength="8" maxlength="64" autocomplete="new-password" placeholder="Leave blank to auto-generate" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600"></div>
                    <div><label class="block text-xs font-medium text-slate-400 mb-1.5">Billing Period</label><select class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200"><option>Monthly</option><option>Quarterly (3 months)</option><option>Semi-Annual (6 months)</option><option>Annual (12 months)</option></select></div>
                    <div class="flex justify-end gap-2 pt-4"><a href="<?php echo site_url('store'); ?>" class="px-4 py-2 bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm rounded-lg transition-colors">Cancel</a><button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-lg transition-colors">Place Order</button></div>
                </form>
            </div>
            <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5"><h3 class="text-lg font-semibold text-white mb-6">Order Summary</h3><div class="space-y-4"><div><p class="text-xs text-slate-500 uppercase mb-1">Plan</p><p class="text-lg font-semibold text-white"><?php echo html_escape($plan['name']); ?></p></div><div class="border-t border-[#2a2d3e] pt-4"><div class="flex justify-between mb-2"><span class="text-slate-400">vCPU</span><span class="text-white"><?php echo (int) $plan['vcpu']; ?></span></div><div class="flex justify-between mb-2"><span class="text-slate-400">RAM</span><span class="text-white"><?php echo (int) $plan['memory']; ?> MB</span></div><div class="flex justify-between"><span class="text-slate-400">Disk</span><span class="text-white"><?php echo (int) $plan['disk']; ?> GB</span></div></div><div class="border-t border-[#2a2d3e] pt-4"><div class="flex justify-between items-baseline"><span class="text-slate-400">Subtotal</span><div><span class="text-2xl font-bold text-indigo-400">$<?php echo number_format((float) $plan['price'], 2); ?></span><span class="text-xs text-slate-500 ml-1">/month</span></div></div></div><div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3"><p class="text-xs text-emerald-300">Wallet Credits: <strong>$<?php echo number_format((float) (isset($credit_balance) ? $credit_balance : 0), 2); ?></strong> - you can pay unpaid service invoices with credits from invoices page.</p></div><div class="bg-green-500/10 border border-green-500/20 rounded-lg p-3 mt-6"><div class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-400 mt-0.5"></i><div class="text-xs text-green-300"><p class="font-medium">Money-back guarantee</p><p>30 days money-back guarantee if not satisfied</p></div></div></div></div></div>
        </div>
    <?php endif; ?>
</div>
