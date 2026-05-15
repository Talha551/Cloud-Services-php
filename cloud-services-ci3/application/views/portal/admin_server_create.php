<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Create Server</h1>
        <p class="text-slate-400 mt-1">Provision a new VPS instance via SolusVM API</p>
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
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 lg:col-span-2">
            <form class="space-y-4" method="post" action="<?php echo site_url('admin/servers/provision'); ?>">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Hostname</label>
                    <input name="hostname" type="text" required placeholder="server.example.com" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Location Label (UI)</label>
                        <select name="location_id" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200">
                            <?php if (!empty($location_options) && is_array($location_options)): ?>
                                <?php foreach ($location_options as $loc_id => $loc_label): ?>
                                    <option value="<?php echo (int) $loc_id; ?>"><?php echo html_escape($loc_label); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">OS Label (UI)</label>
                        <select name="os_id" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200">
                            <?php if (!empty($os_options) && is_array($os_options)): ?>
                                <?php foreach ($os_options as $os_id => $os_label): ?>
                                    <option value="<?php echo (int) $os_id; ?>"><?php echo html_escape($os_label); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Application (optional, metadata)</label>
                    <select name="application_id" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200">
                        <option value="0">No application</option>
                        <?php if (!empty($application_options) && is_array($application_options)): ?>
                            <?php foreach ($application_options as $app_id => $app_label): ?>
                                <option value="<?php echo (int) $app_id; ?>"><?php echo html_escape($app_label); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </select>
                    </div>

                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Local Plan (for dashboard tracking)</label>
                    <select name="local_plan_id" required class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200">
                        <?php foreach ($plans as $plan): ?>
                            <option value="<?php echo (int) $plan['id']; ?>"><?php echo html_escape($plan['name']); ?> - $<?php echo number_format((float) $plan['price'], 2); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Root Password (optional)</label>
                    <input name="vps_password" type="text" minlength="8" maxlength="64" autocomplete="new-password" placeholder="Set root password or leave blank for random" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600">
                    <p class="text-xs text-slate-500 mt-1">Solus IDs are auto-selected now. You only need Hostname, Plan, Location, and OS.</p>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="install_after_create" value="1" checked>
                    Initiate OS installation after server creation
                </label>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-lg transition-colors">Create Server + Start OS Install</button>
                </div>
            </form>
        </div>

        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">Integration Status</h3>
                <p class="text-sm text-slate-400">SolusVM Base URL: <span class="text-slate-200"><?php echo html_escape($solus_base_url !== '' ? $solus_base_url : 'Not configured'); ?></span></p>
                <p class="text-sm mt-1 <?php echo !empty($solus_configured) ? 'text-emerald-300' : 'text-amber-300'; ?>"><?php echo !empty($solus_configured) ? 'Configured' : 'Set SOLUSVM_BASE_URL and SOLUSVM_API_TOKEN in environment'; ?></p>
            </div>
            <?php if (!empty($flash_result)): ?>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-2">Last API Response</h4>
                    <pre class="text-xs text-slate-300 bg-[#0f1117] border border-[#2a2d3e] rounded-lg p-3 overflow-auto max-h-72"><?php echo html_escape($flash_result); ?></pre>
                </div>
            <?php endif; ?>
            <p class="text-sm text-slate-400 leading-relaxed">This form sends POST to SolusVM <code>/api/v1/servers</code> and then POST to <code>/api/v1/servers/{id}/reinstall</code> when enabled.</p>
        </div>
    </div>
</div>
