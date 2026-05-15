<div>
    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo site_url('admin/servers'); ?>" class="p-1.5 rounded-lg hover:bg-[#1e2130] text-slate-400 hover:text-slate-200 transition-colors">
            <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
        </a>
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-white"><?php echo html_escape($service['name'] ? $service['name'] : $service['hostname']); ?></h2>
            <p class="text-sm text-slate-500 mt-1">Server ID: <?php echo (int) $service['id']; ?></p>
        </div>
    </div>

    <?php if (!empty($flash_success)): ?>
        <div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300"><?php echo html_escape($flash_success); ?></div>
    <?php endif; ?>
    <?php if (!empty($flash_error)): ?>
        <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300"><?php echo html_escape($flash_error); ?></div>
    <?php endif; ?>

    <?php
        $status = strtolower((string) $service['status']);
        $cls = ($status === 'running' || $status === 'active')
            ? 'bg-green-500/15 text-green-400 border-green-500/30'
            : (($status === 'stopped')
                ? 'bg-red-500/15 text-red-400 border-red-500/30'
                : 'bg-slate-500/15 text-slate-400 border-slate-500/30');

        $providerBusy = !empty($provider_is_processing);
        $isTransitional = in_array($status, array('reinstalling', 'restarting', 'provisioning', 'building', 'migrating', 'processing'), true);
        $resolvedOsName  = !empty($provider_os_name)  ? $provider_os_name  : ($service['os'] ? $service['os'] : '-');
        $resolvedAppName = !empty($provider_app_name) ? $provider_app_name : '';
        $resolvedIp      = !empty($provider_ip)       ? $provider_ip       : '';
        $resolvedPlan    = $provider_server && isset($provider_server['plan']['name']) ? $provider_server['plan']['name'] : (isset($service['plan_name']) ? $service['plan_name'] : '-');
        $resolvedLoc     = $provider_server && isset($provider_server['location']['name']) ? $provider_server['location']['name'] : ($service['location'] ? $service['location'] : '-');
        $resolvedStatus  = $provider_server && isset($provider_server['status']) ? $provider_server['status'] : $service['status'];
        $hasProvider     = !empty($provider_server);
        $hasBandwidth    = (float) $provider_bandwidth_limit > 0;
    ?>

    <?php if ($providerBusy): ?>
        <div class="mb-4 rounded-lg border border-yellow-500/30 bg-yellow-500/10 px-4 py-3 text-sm text-yellow-300">Server is processing a task. Start/Stop/Restart/Reinstall actions may be temporarily blocked during this time.</div>
    <?php endif; ?>

    <div class="flex items-center gap-3 mb-6 flex-wrap gap-4">
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border <?php echo $cls; ?>"><?php echo html_escape($service['status']); ?></span>
        <div class="flex gap-2 flex-wrap">
            <a href="<?php echo site_url('admin/servers/'.(int) $service['id'].'/action/start'); ?>" class="px-3 py-2 <?php echo $providerBusy ? 'bg-[#161926] text-slate-600 pointer-events-none' : 'bg-[#1e2130] hover:bg-[#252938] text-slate-300'; ?> text-sm rounded-lg transition-colors">Start</a>
            <a href="<?php echo site_url('admin/servers/'.(int) $service['id'].'/action/stop'); ?>" class="px-3 py-2 <?php echo $providerBusy ? 'bg-[#161926] text-slate-600 pointer-events-none' : 'bg-[#1e2130] hover:bg-[#252938] text-slate-300'; ?> text-sm rounded-lg transition-colors">Stop</a>
            <a href="<?php echo site_url('admin/servers/'.(int) $service['id'].'/action/restart'); ?>" class="px-3 py-2 <?php echo $providerBusy ? 'bg-[#161926] text-slate-600 pointer-events-none' : 'bg-[#1e2130] hover:bg-[#252938] text-slate-300'; ?> text-sm rounded-lg transition-colors">Restart</a>
            <a href="<?php echo site_url('admin/servers/'.(int) $service['id'].'/action/delete'); ?>" onclick="return confirm('Delete this server permanently? This action cannot be undone.');" class="px-3 py-2 <?php echo $providerBusy ? 'bg-[#2c1720] text-red-900/60 pointer-events-none' : 'bg-red-600/20 hover:bg-red-600/30 text-red-300'; ?> text-sm rounded-lg transition-colors">Delete Server</a>
            <a href="<?php echo site_url('admin/servers/'.(int) $service['id'].'/console'); ?>" class="px-3 py-2 bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm rounded-lg transition-colors">Console</a>
            <?php if ((int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0) <= 0): ?>
                <form method="post" action="<?php echo site_url('admin/servers/'.(int) $service['id'].'/provision'); ?>" class="inline">
                    <button type="submit" class="px-3 py-2 bg-amber-600/20 hover:bg-amber-600/30 text-amber-300 text-sm rounded-lg transition-colors">Provision on Provider</button>
                </form>
            <?php endif; ?>
            <a href="<?php echo current_url(); ?>" class="px-3 py-2 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 text-sm rounded-lg transition-colors">Refresh Data</a>
        </div>
    </div>

    <?php if ((int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0) <= 0): ?>
        <div class="mb-4 rounded-lg border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
            This server is local-only right now. Click <strong>Provision on Provider</strong> to create and link it with SolusVM.
        </div>
    <?php endif; ?>

    <?php
        $app_access_map = array(
            'directadmin' => array('DirectAdmin', 2222, '/', 'http', 'text-blue-400'),
            'cpanel' => array('cPanel', 2083, '/', 'https', 'text-orange-400'),
            'whm' => array('WHM (cPanel)', 2087, '/', 'https', 'text-orange-400'),
            'plesk' => array('Plesk', 8443, '/', 'https', 'text-purple-400'),
            'webmin' => array('Webmin', 10000, '/', 'https', 'text-green-400'),
            'virtualmin' => array('Virtualmin', 10000, '/virtualmin', 'https', 'text-green-400'),
            'vesta' => array('VestaCP', 8083, '/', 'https', 'text-teal-400'),
            'hestia' => array('HestiaCP', 8083, '/', 'https', 'text-teal-400'),
            'wordpress' => array('WordPress Admin', 80, '/wp-admin', 'http', 'text-blue-300'),
            'nextcloud' => array('Nextcloud', 80, '/', 'http', 'text-sky-400'),
            'openvpn' => array('OpenVPN Access', 943, '/admin', 'https', 'text-amber-400'),
            'panel' => array('Control Panel', 8080, '/', 'http', 'text-slate-300'),
        );

        $detected_access = array();
        $app_search_name = strtolower(trim((string) $resolvedAppName));
        $providerLoginLink = !empty($provider_app_login_link) ? (string) $provider_app_login_link : '';
        if ($app_search_name !== '' && $resolvedIp !== '') {
            foreach ($app_access_map as $fragment => $info) {
                if (strpos($app_search_name, $fragment) !== false) {
                    $proto = $info[3];
                    $port = $info[1];
                    $path = $info[2];
                    if ($providerLoginLink !== '') {
                        $url = $providerLoginLink;
                    } else {
                        $standard = ($proto === 'https' && $port === 443) || ($proto === 'http' && $port === 80);
                        $url = $proto.'://'.$resolvedIp.($standard ? '' : ':'.$port).$path;
                    }
                    $detected_access[] = array('label' => $info[0], 'url' => $url, 'port' => $port, 'icon_color' => $info[4]);
                    break;
                }
            }
        }
    ?>

    <?php if (!empty($detected_access) || $resolvedIp !== ''): ?>
    <div class="mb-6 bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5">
        <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2"><i data-lucide="monitor" class="w-4 h-4 text-indigo-400"></i>Access Your Server</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <?php if ($resolvedIp !== ''): ?>
            <div class="flex items-center gap-3 p-3 bg-[#0f1117] rounded-lg border border-[#2a2d3e]">
                <i data-lucide="terminal" class="w-5 h-5 text-slate-400 shrink-0"></i>
                <div class="min-w-0">
                    <p class="text-xs text-slate-500 mb-0.5">SSH Access</p>
                    <p class="text-sm text-slate-200 font-mono truncate select-all"><?php echo html_escape($resolvedIp); ?>:22</p>
                    <button type="button" onclick="copyText('<?php echo html_escape($resolvedIp); ?>:22', this)" class="text-xs text-indigo-400 hover:text-indigo-300 mt-0.5">Copy</button>
                </div>
            </div>
            <?php endif; ?>

            <?php foreach ($detected_access as $acc): ?>
            <div class="flex items-center gap-3 p-3 bg-[#0f1117] rounded-lg border border-indigo-500/30">
                <i data-lucide="layout-dashboard" class="w-5 h-5 <?php echo html_escape($acc['icon_color']); ?> shrink-0"></i>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-slate-500 mb-0.5"><?php echo html_escape($acc['label']); ?></p>
                    <p class="text-xs text-slate-400 font-mono truncate"><?php echo html_escape($acc['url']); ?></p>
                    <div class="flex gap-3 mt-1">
                        <a href="<?php echo html_escape($acc['url']); ?>" target="_blank" rel="noopener noreferrer" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium flex items-center gap-1"><i data-lucide="external-link" class="w-3 h-3"></i>Open Dashboard</a>
                        <button type="button" onclick="copyText('<?php echo html_escape($acc['url']); ?>', this)" class="text-xs text-slate-500 hover:text-slate-300">Copy URL</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if ($resolvedIp !== ''): ?>
            <div class="flex items-center gap-3 p-3 bg-[#0f1117] rounded-lg border border-[#2a2d3e]">
                <i data-lucide="globe" class="w-5 h-5 text-green-400 shrink-0"></i>
                <div class="min-w-0">
                    <p class="text-xs text-slate-500 mb-0.5">Web (HTTP)</p>
                    <p class="text-xs text-slate-400 font-mono truncate">http://<?php echo html_escape($resolvedIp); ?>/</p>
                    <a href="http://<?php echo html_escape($resolvedIp); ?>/" target="_blank" rel="noopener noreferrer" class="text-xs text-green-400 hover:text-green-300 flex items-center gap-1 mt-1"><i data-lucide="external-link" class="w-3 h-3"></i>Open</a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($vps_password)): ?>
        <div class="mt-4 p-3 bg-[#0f1117] rounded-lg border border-yellow-500/20 flex flex-wrap items-center gap-4">
            <div><p class="text-xs text-slate-500 mb-0.5">Login Username</p><p class="text-sm text-slate-200 font-mono select-all">root</p></div>
            <div><p class="text-xs text-slate-500 mb-0.5">Root Password</p><p class="text-sm text-slate-200 font-mono select-all" id="rootPassDisplay">********</p></div>
            <div class="flex gap-2">
                <button type="button" onclick="togglePass()" class="text-xs text-indigo-400 hover:text-indigo-300">Show/Hide</button>
                <button type="button" onclick="copyText('<?php echo html_escape($vps_password); ?>', this)" class="text-xs text-slate-500 hover:text-slate-300">Copy</button>
            </div>
        </div>
        <script>
        var _vpsPass = <?php echo json_encode($vps_password); ?>;
        var _passShown = false;
        function togglePass() {
            _passShown = !_passShown;
            var el = document.getElementById('rootPassDisplay');
            if (el) { el.textContent = _passShown ? _vpsPass : '********'; }
        }
        </script>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 lg:col-span-2 space-y-0">
            <h3 class="text-lg font-semibold text-white mb-4">Server Details
                <?php if ($hasProvider): ?>
                    <span class="ml-2 text-xs font-normal text-green-400 border border-green-500/30 bg-green-500/10 px-2 py-0.5 rounded">Live from Provider</span>
                <?php else: ?>
                    <span class="ml-2 text-xs font-normal text-slate-500 border border-slate-600/30 bg-slate-500/10 px-2 py-0.5 rounded">Local Only</span>
                <?php endif; ?>
            </h3>

            <?php
                $rows = array();
                $rows[] = array('Hostname', html_escape($provider_server && isset($provider_server['name']) ? $provider_server['name'] : ($service['hostname'] ? $service['hostname'] : '-')), 'mono');
                $rows[] = array('Status', '<span class="'.$cls.' border px-2 py-0.5 rounded text-xs">'.html_escape($resolvedStatus).'</span>', 'raw');
                $rows[] = array('Plan', html_escape($resolvedPlan));
                $rows[] = array('OS', html_escape($resolvedOsName));
                if ($resolvedAppName) { $rows[] = array('Installed App', html_escape($resolvedAppName)); }
                $rows[] = array('Location', html_escape($resolvedLoc));
                $rows[] = array('Provider Server ID', isset($service['provider_server_id']) && (int) $service['provider_server_id'] > 0 ? (int) $service['provider_server_id'] : '<span class="text-amber-400">Not Linked</span>', 'raw');
                $rows[] = array('IPv4', $resolvedIp ? '<span class="font-mono select-all">'.html_escape($resolvedIp).'</span>' : '<span class="text-slate-500">Pending...</span>', 'raw');
                if (!empty($vps_password)) { $rows[] = array('Root Password', '<span class="font-mono select-all">'.html_escape($vps_password).'</span>', 'raw'); }
                $rows[] = array('Created', html_escape(substr($service['created_at'], 0, 19)));
            ?>

            <?php $last = count($rows) - 1; foreach ($rows as $i => $row): ?>
                <div class="flex justify-between items-center py-3 <?php echo $i < $last ? 'border-b border-[#1e2130]' : ''; ?>">
                    <span class="text-slate-400 text-sm shrink-0 pr-4"><?php echo $row[0]; ?></span>
                    <span class="text-slate-200 text-sm text-right <?php echo isset($row[2]) && $row[2] === 'mono' ? 'font-mono' : ''; ?>">
                        <?php echo isset($row[2]) && $row[2] === 'raw' ? $row[1] : html_escape($row[1]); ?>
                    </span>
                </div>
            <?php endforeach; ?>

            <div class="mt-6 border-t border-[#1e2130] pt-6">
                <h4 class="text-white font-semibold mb-1">Reinstall OS / Install Application</h4>
                <p class="text-xs text-slate-500 mb-3">Select an OS or an Application (not both). Adding a root password is optional but recommended.</p>
                <form method="post" action="<?php echo site_url('admin/servers/'.(int) $service['id'].'/reinstall'); ?>" class="space-y-3" id="reinstallForm">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">OS</label>
                        <select name="os" id="osSelect" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm">
                            <option value="">- Select OS -</option>
                            <?php if (!empty($available_os) && is_array($available_os)): ?>
                                <?php foreach ($available_os as $os): ?>
                                    <?php $osId = (int) (isset($os['id']) ? $os['id'] : 0); $osLbl = ''; foreach (array('label','name','title','version_name') as $ok) { if (!empty($os[$ok])) { $osLbl = $os[$ok]; break; } } if (!$osLbl) { $osLbl = 'OS #'.$osId; } ?>
                                    <option value="<?php echo $osId; ?>"><?php echo html_escape($osLbl); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No OS options from provider</option>
                            <?php endif; ?>
                        </select>
                        <?php if (!empty($os_dropdown_note)): ?><p class="text-xs text-slate-500 mt-1"><?php echo html_escape($os_dropdown_note); ?></p><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Application</label>
                        <select name="application" id="applicationSelect" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm">
                            <option value="">- Select Application -</option>
                            <?php if (!empty($applications) && is_array($applications)): ?>
                                <?php foreach ($applications as $app): ?>
                                    <?php $appId = (int) (isset($app['id']) ? $app['id'] : 0); $appLbl = ''; foreach (array('label','name','title') as $ak) { if (!empty($app[$ak])) { $appLbl = $app[$ak]; break; } } if (!$appLbl) { $appLbl = 'App #'.$appId; } ?>
                                    <option value="<?php echo $appId; ?>"><?php echo html_escape($appLbl); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Root Password <span class="text-slate-600">(optional)</span></label>
                        <input name="password" type="text" minlength="8" maxlength="64" autocomplete="new-password" placeholder="Leave blank to keep current" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Application Data <span class="text-slate-600">(JSON, optional)</span></label>
                        <textarea name="application_data" id="applicationData" rows="3" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm font-mono" placeholder='{"domain":"example.com","password":"StrongPass123"}'></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 <?php echo $providerBusy ? 'bg-[#373746] text-slate-500 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-500 text-white'; ?> text-sm rounded-lg transition-colors" <?php echo $providerBusy ? 'disabled' : ''; ?>>Reinstall / Install App</button>
                </form>
            </div>

            <div class="mt-6 border-t border-[#1e2130] pt-6">
                <h4 class="text-white font-semibold mb-1">Change Root Password</h4>
                <p class="text-xs text-slate-500 mb-3">Change the root/administrator password on the VPS without reinstalling.</p>
                <form method="post" action="<?php echo site_url('admin/servers/'.(int) $service['id'].'/change_password'); ?>" class="flex gap-2 items-end">
                    <div class="flex-1">
                        <label class="block text-xs text-slate-400 mb-1">New Password</label>
                        <input name="password" type="text" minlength="8" maxlength="64" required autocomplete="new-password" placeholder="Min 8 characters" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm">
                    </div>
                    <button type="submit" class="px-4 py-2 <?php echo $providerBusy ? 'bg-[#373746] text-slate-500 cursor-not-allowed' : 'bg-teal-700 hover:bg-teal-600 text-white'; ?> text-sm rounded-lg transition-colors shrink-0" <?php echo $providerBusy ? 'disabled' : ''; ?>>Change Password</button>
                </form>
            </div>
        </div>

        <div class="space-y-4">
            <?php
                $pv = isset($provider_resources['vcpu']) ? (int) $provider_resources['vcpu'] : 0;
                $pm = isset($provider_resources['memory']) ? (int) $provider_resources['memory'] : 0;
                $pd = isset($provider_resources['disk']) ? (int) $provider_resources['disk'] : 0;
                $vcpu = ($pv > 0) ? $pv : (int) $service['vcpu'];
                $memory = ($pm > 0) ? $pm : (int) $service['memory'];
                $disk = ($pd > 0) ? $pd : (int) $service['disk'];
                $resLive = ($pv > 0 || $pm > 0 || $pd > 0);
            ?>
            <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5">
                <h3 class="text-lg font-semibold text-white mb-4">Resources <span class="ml-1 text-xs font-normal <?php echo $resLive ? 'text-green-400' : 'text-slate-500'; ?>(<?php echo $resLive ? 'Real-time' : 'Local'; ?>)</span></h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-[#0f1117] rounded-lg"><i data-lucide="zap" class="w-4 h-4 text-yellow-400 shrink-0"></i><div><p class="text-xs text-slate-500">CPU</p><p class="text-lg font-semibold text-white"><?php echo $vcpu; ?> vCPU</p></div></div>
                    <div class="flex items-center gap-3 p-3 bg-[#0f1117] rounded-lg"><i data-lucide="memory-stick" class="w-4 h-4 text-blue-400 shrink-0"></i><div><p class="text-xs text-slate-500">RAM</p><p class="text-lg font-semibold text-white"><?php echo $memory >= 1024 ? round($memory/1024, 1).' GB' : $memory.' MB'; ?></p></div></div>
                    <div class="flex items-center gap-3 p-3 bg-[#0f1117] rounded-lg"><i data-lucide="hard-drive" class="w-4 h-4 text-green-400 shrink-0"></i><div><p class="text-xs text-slate-500">Disk</p><p class="text-lg font-semibold text-white"><?php echo $disk; ?> GB</p></div></div>
                </div>
            </div>

            <?php if ($hasBandwidth): ?>
            <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5">
                <h3 class="text-base font-semibold text-white mb-3">Bandwidth</h3>
                <?php $pct = $provider_bandwidth_limit > 0 ? min(100, round(($provider_bandwidth_used / $provider_bandwidth_limit) * 100)) : 0; $bwColor = $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-yellow-500' : 'bg-indigo-500'); ?>
                <div class="mb-2 flex justify-between text-xs text-slate-400"><span>Used: <?php echo number_format($provider_bandwidth_used, 1); ?> GB</span><span>Limit: <?php echo number_format($provider_bandwidth_limit, 1); ?> GB</span></div>
                <div class="w-full bg-[#0f1117] rounded-full h-2"><div class="<?php echo $bwColor; ?> h-2 rounded-full" style="width:<?php echo $pct; ?>%"></div></div>
                <p class="text-xs text-slate-500 mt-1 text-right"><?php echo $pct; ?>% used</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function () {
    window.copyText = function (text, btn) {
        if (!text) { return; }
        navigator.clipboard ? navigator.clipboard.writeText(text).then(function () {
            var orig = btn.textContent;
            btn.textContent = 'Copied!';
            setTimeout(function () { btn.textContent = orig; }, 1500);
        }) : (function () {
            var ta = document.createElement('textarea');
            ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            var orig = btn.textContent;
            btn.textContent = 'Copied!';
            setTimeout(function () { btn.textContent = orig; }, 1500);
        })();
    };

    var osSelect = document.getElementById('osSelect');
    var appSelect = document.getElementById('applicationSelect');
    var appData = document.getElementById('applicationData');

    if (appSelect) {
        appSelect.addEventListener('change', function () {
            if (appSelect.value && osSelect) { osSelect.value = ''; }
            if (appData && !appData.value.trim()) { appData.value = '{}'; }
        });
    }

    if (osSelect) {
        osSelect.addEventListener('change', function () {
            if (osSelect.value && appSelect) { appSelect.value = ''; }
            if (osSelect.value && appData) { appData.value = ''; }
        });
    }

    <?php if ($isTransitional): ?>
    (function () {
        var remaining = 30;
        var badge = document.createElement('span');
        badge.className = 'text-xs text-slate-500 ml-2';
        var refreshBtn = document.querySelector('a[href="<?php echo current_url(); ?>"]');
        if (refreshBtn) {
            refreshBtn.parentNode.insertBefore(badge, refreshBtn.nextSibling);
        }
        var t = setInterval(function () {
            remaining--;
            badge.textContent = '(auto-refresh in ' + remaining + 's)';
            if (remaining <= 0) {
                clearInterval(t);
                window.location.reload();
            }
        }, 1000);
    })();
    <?php endif; ?>
})();
</script>
