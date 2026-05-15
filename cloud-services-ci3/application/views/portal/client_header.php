<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape(isset($title) ? $title : 'CloudPanel'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Manrope', 'sans-serif'], display: ['Sora', 'sans-serif'] } } } };
    </script>
    <script>
        (function () {
            var saved = null;
            try { saved = localStorage.getItem('cp_theme'); } catch (e) { saved = null; }
            document.documentElement.setAttribute('data-theme', saved || 'light');
        }());
    </script>
    <style>
        * { box-sizing: border-box; }
        :root {
            --bg-main: #f3f7fd;
            --bg-nav: #ffffff;
            --bg-panel: #fcfdff;
            --line: #cad9ee;
            --text-main: #1c2f48;
            --text-strong: #0f223a;
            --text-muted: #4f6788;
            --brand: #1f86e3;
            --card-bg: #ffffff;
            --table-stripe: rgba(14, 76, 150, 0.05);
            --input-bg: #ffffff;
            --toggle-bg: #0f172a;
            --toggle-text: #f8fafc;
        }
        html[data-theme='dark'] {
            --bg-main: #0b1220;
            --bg-nav: #111b2e;
            --bg-panel: #162238;
            --line: #2a3a58;
            --text-main: #e5edf8;
            --text-strong: #ffffff;
            --text-muted: #93a7c4;
            --brand: #22c1ee;
            --card-bg: linear-gradient(180deg, rgba(19, 31, 53, 0.92) 0%, rgba(15, 25, 43, 0.94) 100%);
            --table-stripe: rgba(31, 49, 79, 0.55);
            --input-bg: rgba(20, 33, 56, 0.85);
            --toggle-bg: #f8fafc;
            --toggle-text: #0f172a;
        }
        body { margin: 0; font-family: Manrope, sans-serif; background: radial-gradient(850px 420px at 15% -20%, color-mix(in srgb, var(--brand) 18%, transparent) 0%, transparent 70%), var(--bg-main); color: var(--text-main); }
        h1, h2, h3, h4, h5, h6 { font-family: Sora, sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: color-mix(in srgb, var(--bg-main) 80%, #64748b); }
        ::-webkit-scrollbar-thumb { background: color-mix(in srgb, var(--line) 80%, #334155); border-radius: 999px; }
        .bg-\[\#0f1117\] { background: var(--bg-main) !important; }
        .bg-\[\#13151f\] { background: var(--bg-nav) !important; }
        .bg-\[\#1e2130\] { background: var(--bg-panel) !important; }
        .border-\[\#2a2d3e\] { border-color: var(--line) !important; }
        .text-slate-400 { color: var(--text-muted) !important; }
        .text-slate-500, .text-slate-600 { color: var(--text-muted) !important; }
        .text-slate-200, .text-slate-300 { color: var(--text-main) !important; }
        .text-white { color: var(--text-strong) !important; }
        .hover\:bg-\[\#1e2130\]:hover, .hover\:bg-\[\#252938\]:hover { background: color-mix(in srgb, var(--bg-panel) 82%, #20334f 18%) !important; }
        .bg-indigo-600 { background-color: var(--brand) !important; }
        .hover\:bg-indigo-500:hover { background-color: color-mix(in srgb, var(--brand) 85%, #38bdf8) !important; }
        .text-indigo-400 { color: var(--brand) !important; }
        .app-card,
        .bg-white {
            background: var(--card-bg) !important;
            border: 1px solid var(--line) !important;
            border-radius: 14px !important;
            box-shadow: 0 12px 24px rgba(5, 10, 18, 0.08);
        }
        .text-dark, .text-black { color: var(--text-main) !important; }
        .text-secondary, .text-muted { color: var(--text-muted) !important; }
        .table {
            color: var(--text-main) !important;
            --bs-table-color: var(--text-main);
            --bs-table-striped-color: var(--text-main);
            --bs-table-striped-bg: var(--table-stripe);
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--line);
        }
        .table thead th {
            color: var(--text-muted) !important;
            border-bottom-color: var(--line) !important;
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .table > :not(caption) > * > * { border-color: var(--line) !important; }
        .form-control,
        .form-select,
        .form-check-input {
            background: var(--input-bg) !important;
            border-color: var(--line) !important;
            color: var(--text-main) !important;
        }
        .form-control::placeholder { color: var(--text-muted) !important; }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand) !important;
            box-shadow: 0 0 0 0.2rem color-mix(in srgb, var(--brand) 28%, transparent) !important;
        }
        .btn {
            border-radius: 10px !important;
            font-weight: 600 !important;
            border-width: 1px !important;
        }
        .btn-primary,
        .bg-primary {
            background: linear-gradient(90deg, color-mix(in srgb, var(--brand) 86%, #0891b2) 0%, #1782e8 100%) !important;
            border-color: color-mix(in srgb, var(--brand) 80%, #1f7ae0) !important;
            color: #fff !important;
        }
        .btn-outline-primary {
            color: var(--brand) !important;
            border-color: color-mix(in srgb, var(--brand) 70%, #64748b) !important;
        }
        .btn-outline-primary:hover { background: color-mix(in srgb, var(--bg-panel) 78%, transparent) !important; }
        .btn-outline-secondary,
        .btn-outline-info,
        .btn-outline-success,
        .btn-outline-danger {
            border-color: color-mix(in srgb, var(--line) 80%, #64748b) !important;
            color: var(--text-muted) !important;
        }
        .btn-outline-secondary:hover,
        .btn-outline-info:hover,
        .btn-outline-success:hover,
        .btn-outline-danger:hover { background: color-mix(in srgb, var(--bg-panel) 80%, transparent) !important; }
        .badge { font-weight: 700; letter-spacing: 0.02em; }
        .alert {
            border-radius: 12px !important;
            border: 1px solid var(--line) !important;
        }
        .theme-toggle-fab {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 70;
            border: 1px solid color-mix(in srgb, var(--line) 75%, #64748b);
            background: var(--toggle-bg);
            color: var(--toggle-text);
            border-radius: 999px;
            height: 44px;
            min-width: 44px;
            padding: 0 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.2);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-[#0f1117] text-slate-200">
<div class="flex flex-col min-h-screen bg-[#0f1117]">
    <nav class="bg-[#13151f] border-b border-[#2a2d3e]">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center"><span class="text-white font-bold text-sm">C</span></div>
                <span class="font-semibold text-white text-sm">CloudPanel Client</span>
            </div>
            <div class="hidden md:flex items-center gap-6">
                <a href="<?php echo site_url('client'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'dashboard') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Dashboard</a>
                <a href="<?php echo site_url('client/services'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'services') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">My Services</a>
                <a href="<?php echo site_url('client/orders'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'orders') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Orders</a>
                <a href="<?php echo site_url('client/invoices'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'invoices') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Invoices</a>
                <a href="<?php echo site_url('client/credits'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'credits') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Credits</a>
                <a href="<?php echo site_url('client/tickets'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'tickets') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Support</a>
                <a href="<?php echo site_url('store'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'store') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Store</a>
                <a href="<?php echo site_url('client/profile'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'profile') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Profile</a>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden md:inline-flex items-center rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-300">
                    Credits: $<?php echo number_format((float) (isset($credit_balance) ? $credit_balance : 0), 2); ?>
                </span>
                <span class="text-xs text-slate-400 hidden sm:inline"><?php echo html_escape($user['email']); ?></span>
                <a href="<?php echo site_url('logout'); ?>" class="inline-flex items-center gap-1.5 px-2 py-1.5 rounded-lg hover:bg-[#1e2130] text-slate-400 hover:text-slate-200 transition-colors text-xs" title="Logout">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>
    <main class="flex-1 max-w-7xl mx-auto w-full p-6">
