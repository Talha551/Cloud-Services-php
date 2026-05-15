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
            --bg-main: #eef3fb;
            --bg-sidebar: #ffffff;
            --bg-panel: #f7faff;
            --bg-soft: #e8f0fb;
            --line: #d4e1f2;
            --text-main: #223650;
            --text-strong: #12243d;
            --text-muted: #5e7597;
            --brand: #1f86e3;
            --brand-soft: rgba(31, 134, 227, 0.12);
            --card-bg: #ffffff;
            --table-stripe: rgba(14, 76, 150, 0.05);
            --input-bg: #ffffff;
            --toggle-bg: #0f172a;
            --toggle-text: #f8fafc;
        }
        html[data-theme='dark'] {
            --bg-main: #0b1220;
            --bg-sidebar: #0f172a;
            --bg-panel: #162238;
            --bg-soft: #1d2c48;
            --line: #2a3a58;
            --text-main: #e5edf8;
            --text-strong: #ffffff;
            --text-muted: #91a5c2;
            --brand: #22c1ee;
            --brand-soft: rgba(34, 193, 238, 0.16);
            --card-bg: linear-gradient(180deg, rgba(19, 31, 53, 0.92) 0%, rgba(15, 25, 43, 0.94) 100%);
            --table-stripe: rgba(31, 49, 79, 0.55);
            --input-bg: rgba(20, 33, 56, 0.85);
            --toggle-bg: #f8fafc;
            --toggle-text: #0f172a;
        }
        body { margin: 0; font-family: Manrope, sans-serif; background: radial-gradient(900px 420px at 20% -20%, color-mix(in srgb, var(--brand-soft) 70%, transparent) 0%, transparent 70%), var(--bg-main); color: var(--text-main); }
        h1, h2, h3, h4, h5, h6 { font-family: Sora, sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: color-mix(in srgb, var(--bg-main) 80%, #64748b); }
        ::-webkit-scrollbar-thumb { background: color-mix(in srgb, var(--line) 80%, #334155); border-radius: 999px; }
        .bg-\[\#0f1117\] { background: var(--bg-main) !important; }
        .bg-\[\#13151f\] { background: var(--bg-sidebar) !important; }
        .bg-\[\#1e2130\] { background: var(--bg-soft) !important; }
        .border-\[\#2a2d3e\] { border-color: var(--line) !important; }
        .text-slate-400 { color: var(--text-muted) !important; }
        .text-slate-500, .text-slate-600 { color: var(--text-muted) !important; }
        .text-slate-200, .text-slate-300 { color: var(--text-main) !important; }
        .text-white { color: var(--text-strong) !important; }
        .hover\:bg-\[\#1e2130\]:hover, .hover\:bg-\[\#252938\]:hover { background: #253958 !important; }
        .bg-indigo-600 { background-color: var(--brand) !important; }
        .text-indigo-400 { color: var(--brand) !important; }
        .bg-indigo-600\/20 { background-color: var(--brand-soft) !important; }
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
        .btn-outline-primary:hover { background: color-mix(in srgb, var(--brand-soft) 70%, transparent) !important; }
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
        .btn-outline-danger:hover { background: color-mix(in srgb, var(--bg-soft) 80%, transparent) !important; }
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
<div class="flex h-screen bg-[#0f1117] text-slate-200 overflow-hidden">
    <aside class="w-60 bg-[#13151f] border-r border-[#2a2d3e] flex flex-col shrink-0">
        <div class="flex items-center gap-3 px-5 py-5 border-b border-[#2a2d3e]">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-cyan-700/30">
                <i data-lucide="globe" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-semibold text-white text-sm tracking-wide">CloudPanel Admin</span>
        </div>
        <nav class="flex-1 p-3 space-y-0.5 overflow-y-auto">
            <?php
            $nav = array(
                'dashboard' => array('admin', 'Dashboard', 'layout-dashboard'),
                'servers' => array('admin/servers', 'Servers', 'server'),
                'compute-resources' => array('admin/compute-resources', 'Compute Resources', 'cpu'),
                'plans' => array('admin/plans', 'Plans', 'package'),
                'os-images' => array('admin/os-images', 'OS Images', 'hard-drive'),
                'users' => array('admin/users', 'Users', 'users'),
                'projects' => array('admin/projects', 'Projects', 'folder-kanban'),
                'clients' => array('admin/clients', 'Clients', 'user-round'),
                'invoices' => array('admin/invoices', 'Invoices', 'receipt-text'),
                'orders' => array('admin/orders', 'Orders', 'shopping-cart'),
                'domains' => array('admin/domains', 'Domains', 'globe-2'),
                'tickets' => array('admin/tickets', 'Tickets', 'life-buoy'),
                'audit-logs' => array('admin/audit-logs', 'Audit Logs', 'history'),
                'locations' => array('admin/locations', 'Locations', 'map-pin'),
                'backups' => array('admin/backups', 'Backups', 'database-backup'),
                'ip-blocks' => array('admin/ip-blocks', 'IP Blocks', 'network')
            );
            foreach ($nav as $key => $item):
                $active = ($active_nav === $key);
            ?>
                <a href="<?php echo site_url($item[0]); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors <?php echo $active ? 'bg-indigo-600/20 text-indigo-400 font-medium' : 'text-slate-400 hover:text-slate-200 hover:bg-[#1e2130]'; ?>">
                    <i data-lucide="<?php echo $item[2]; ?>" class="w-4 h-4"></i>
                    <?php echo html_escape($item[1]); ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="px-5 py-3 border-t border-[#2a2d3e]"><p class="text-xs text-slate-600">SolusVM2 Operations Platform</p></div>
    </aside>
    <div class="flex flex-col flex-1 overflow-hidden">
        <header class="h-14 bg-[#13151f] border-b border-[#2a2d3e] flex items-center justify-between px-6 shrink-0">
            <h1 class="text-sm font-semibold text-white"><?php echo html_escape(isset($page_title) ? $page_title : 'CloudPanel'); ?></h1>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-sm text-slate-400">
                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    <span><?php echo html_escape($user['email']); ?></span>
                </div>
                <a href="<?php echo site_url('logout'); ?>" class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-red-400 transition-colors px-2 py-1 rounded">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    Logout
                </a>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-6">
