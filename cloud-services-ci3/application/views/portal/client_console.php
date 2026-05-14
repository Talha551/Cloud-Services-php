<div>
    <div class="flex items-center gap-3 mb-5">
        <a href="<?php echo isset($back_url) ? $back_url : site_url('client/services/'.(int) $service['id']); ?>" class="p-1.5 rounded-lg hover:bg-[#1e2130] text-slate-400 hover:text-slate-200 transition-colors">
            <i data-lucide="arrow-left" class="w-[18px] h-[18px]"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">Console</h2>
            <p class="text-sm text-slate-400 mt-1">Service ID: <?php echo (int) $service['id']; ?> | Provider ID: <?php echo (int) $provider_server_id; ?></p>
        </div>
    </div>

    <div id="vnc_status" class="mb-3 text-xs text-slate-400">Connecting...</div>
    <div id="vnc_screen" class="w-full h-[70vh] rounded-xl border border-[#2a2d3e] bg-black overflow-hidden"></div>

    <?php if (!empty($vnc_password)): ?>
    <div class="mt-4 p-3 bg-[#0f1117] rounded-lg border border-yellow-500/20 flex flex-wrap items-center gap-4">
        <div>
            <p class="text-xs text-slate-500 mb-0.5">VNC / Console Password</p>
            <p class="text-sm text-slate-200 font-mono select-all" id="vncPassDisplay">••••••••</p>
        </div>
        <?php if (!empty($vnc_username)): ?>
        <div>
            <p class="text-xs text-slate-500 mb-0.5">Username</p>
            <p class="text-sm text-slate-200 font-mono select-all"><?php echo html_escape($vnc_username); ?></p>
        </div>
        <?php endif; ?>
        <div class="flex gap-2">
            <button type="button" onclick="toggleVncPass()" class="text-xs text-indigo-400 hover:text-indigo-300">Show/Hide</button>
            <button type="button" onclick="copyVncPass(this)" class="text-xs text-slate-500 hover:text-slate-300">Copy</button>
        </div>
        <p class="text-xs text-slate-600 w-full mt-0.5">Use this if the console shows a password prompt. For OS login (Linux terminal), use your root password set during installation.</p>
    </div>
    <script>
    var _vncPass = <?php echo json_encode($vnc_password); ?>;
    var _vncPassShown = false;
    function toggleVncPass() {
        _vncPassShown = !_vncPassShown;
        var el = document.getElementById('vncPassDisplay');
        if (el) { el.textContent = _vncPassShown ? _vncPass : '••••••••'; }
    }
    function copyVncPass(btn) {
        navigator.clipboard.writeText(_vncPass).then(function() {
            var orig = btn.textContent; btn.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = orig; }, 1500);
        });
    }
    </script>
    <?php endif; ?>

    <div class="mt-3 flex flex-wrap gap-3">
        <button type="button" id="reconnectBtn" class="px-3 py-2 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 text-sm rounded-lg transition-colors">Reconnect Console</button>
        <a href="<?php echo html_escape($http_console_url); ?>" target="_blank" rel="noopener noreferrer" class="px-3 py-2 bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm rounded-lg transition-colors">Open in New Tab</a>
        <a href="<?php echo isset($back_url) ? $back_url : site_url('client/services/'.(int) $service['id']); ?>" class="px-3 py-2 bg-[#1e2130] hover:bg-[#252938] text-slate-300 text-sm rounded-lg transition-colors">Back to Service</a>
    </div>
</div>

<script type="module">
(async function () {
    var candidates = [
        'https://cdn.jsdelivr.net/npm/@novnc/novnc@1.4.0/core/rfb.js',
        'https://unpkg.com/@novnc/novnc@1.4.0/core/rfb.js'
    ];
    for (var i = 0; i < candidates.length; i++) {
        try {
            var mod = await import(candidates[i]);
            var RFB = (mod && (mod.default || mod.RFB)) ? (mod.default || mod.RFB) : null;
            if (RFB) { window.RFB = RFB; window.dispatchEvent(new Event('novnc-ready')); return; }
        } catch (e) { /* try next */ }
    }
    window.dispatchEvent(new Event('novnc-failed'));
})();
</script>
<script>
(function () {
    var wsUrl = <?php echo json_encode($ws_url); ?>;
    var httpConsoleUrl = <?php echo json_encode($http_console_url); ?>;
    var sessionRefreshUrl = <?php echo json_encode(isset($session_refresh_url) ? $session_refresh_url : ''); ?>;
    var vncPassword = <?php echo json_encode(isset($vnc_password) ? $vnc_password : ''); ?>;
    var statusEl = document.getElementById('vnc_status');
    var screenEl = document.getElementById('vnc_screen');
    var reconnectBtn = document.getElementById('reconnectBtn');
    var rfb = null;
    var reconnecting = false;

    function showOpenTabFallback(msg) {
        statusEl.textContent = msg;
        statusEl.className = 'mb-3 text-xs text-orange-400';
        screenEl.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9ca3af;font-size:14px;text-align:center;padding:16px;">'
            + msg + '<br><br>Use the <strong style="color:#e2e8f0;">Open in New Tab</strong> button below.</div>';
    }

    function initVNC() {
        if (!wsUrl || (wsUrl.indexOf('ws://') !== 0 && wsUrl.indexOf('wss://') !== 0)) {
            showOpenTabFallback('Console WebSocket URL is invalid. Use "Open in New Tab".');
            return;
        }
        if (!window.RFB) {
            showOpenTabFallback('noVNC library not available. Use "Open in New Tab".');
            return;
        }

        try {
            if (rfb) { try { rfb.disconnect(); } catch(e) {} }
            screenEl.innerHTML = '';

            rfb = new window.RFB(screenEl, wsUrl, { credentials: vncPassword ? { password: vncPassword } : {} });
            rfb.scaleViewport = true;
            rfb.resizeSession = true;

            rfb.addEventListener('connect', function () {
                statusEl.textContent = 'Connected to console';
                statusEl.className = 'mb-3 text-xs text-green-400';
            });

            rfb.addEventListener('disconnect', function (e) {
                var clean = e && e.detail && e.detail.clean;
                statusEl.textContent = clean ? 'Console session ended.' : 'Disconnected. Click Reconnect to try again.';
                statusEl.className = 'mb-3 text-xs text-red-400';
            });

            rfb.addEventListener('credentialsrequired', function () {
                if (vncPassword) {
                    rfb.sendCredentials({ password: vncPassword });
                } else {
                    statusEl.textContent = 'VNC credentials required but not provided by server.';
                    statusEl.className = 'mb-3 text-xs text-red-400';
                }
            });

            rfb.addEventListener('error', function (e) {
                statusEl.textContent = 'Console error: ' + (e && e.detail ? e.detail : 'Connection failed');
                statusEl.className = 'mb-3 text-xs text-red-400';
            });

        } catch (e) {
            statusEl.textContent = 'Console init failed: ' + (e && e.message ? e.message : 'Unknown error');
            statusEl.className = 'mb-3 text-xs text-red-400';
        }
    }

    async function reconnect() {
        if (reconnecting) return;
        reconnecting = true;
        statusEl.textContent = 'Refreshing session...';
        statusEl.className = 'mb-3 text-xs text-slate-300';
        try {
            if (sessionRefreshUrl) {
                var resp = await fetch(sessionRefreshUrl, { method: 'GET', credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
                var data = await resp.json();
                if (resp.ok && data && data.ok) {
                    wsUrl = data.ws_url || wsUrl;
                    httpConsoleUrl = data.http_console_url || httpConsoleUrl;
                    vncPassword = data.vnc_password || '';
                } else {
                    statusEl.textContent = 'Session refresh failed. Use "Open in New Tab".';
                    statusEl.className = 'mb-3 text-xs text-red-400';
                    reconnecting = false;
                    return;
                }
            }
            initVNC();
        } catch (err) {
            statusEl.textContent = 'Reconnect error: ' + (err && err.message ? err.message : 'Network error');
            statusEl.className = 'mb-3 text-xs text-red-400';
        } finally {
            reconnecting = false;
        }
    }

    window.addEventListener('novnc-ready', function () { initVNC(); });
    window.addEventListener('novnc-failed', function () {
        showOpenTabFallback('noVNC library failed to load from CDNs. Use "Open in New Tab".');
    });

    if (reconnectBtn) { reconnectBtn.addEventListener('click', function () { reconnect(); }); }
})();
</script>
