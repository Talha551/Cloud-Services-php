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
    <style>
        * { box-sizing: border-box; }
        :root {
            --bg-main: #0b1220;
            --bg-nav: #111b2e;
            --bg-panel: #162238;
            --line: #2a3a58;
            --text-main: #e5edf8;
            --text-muted: #93a7c4;
            --brand: #22c1ee;
        }
        body { margin: 0; font-family: Manrope, sans-serif; background: radial-gradient(850px 420px at 15% -20%, #173156 0%, transparent 70%), var(--bg-main); color: var(--text-main); }
        h1, h2, h3, h4, h5, h6 { font-family: Sora, sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #10192c; }
        ::-webkit-scrollbar-thumb { background: #39527c; border-radius: 999px; }
        .bg-\[\#0f1117\] { background: var(--bg-main) !important; }
        .bg-\[\#13151f\] { background: linear-gradient(180deg, #121d31 0%, var(--bg-nav) 100%) !important; }
        .bg-\[\#1e2130\] { background: var(--bg-panel) !important; }
        .border-\[\#2a2d3e\] { border-color: var(--line) !important; }
        .text-slate-400 { color: var(--text-muted) !important; }
        .text-slate-500, .text-slate-600 { color: #7f95b6 !important; }
        .hover\:bg-\[\#1e2130\]:hover, .hover\:bg-\[\#252938\]:hover { background: #253958 !important; }
        .bg-indigo-600 { background-color: #0891b2 !important; }
        .hover\:bg-indigo-500:hover { background-color: #0ea5c6 !important; }
        .text-indigo-400 { color: #66e3ff !important; }
        .app-card,
        .bg-white {
            background: linear-gradient(180deg, rgba(19, 31, 53, 0.92) 0%, rgba(15, 25, 43, 0.94) 100%) !important;
            border: 1px solid rgba(77, 110, 157, 0.42) !important;
            border-radius: 14px !important;
            box-shadow: 0 12px 24px rgba(5, 10, 18, 0.3);
        }
        .text-dark, .text-black { color: #e5edf8 !important; }
        .text-secondary, .text-muted { color: #94a8c7 !important; }
        .table {
            color: #d7e5fa !important;
            --bs-table-color: #d7e5fa;
            --bs-table-striped-color: #d7e5fa;
            --bs-table-striped-bg: rgba(31, 49, 79, 0.55);
            --bs-table-bg: transparent;
            --bs-table-border-color: rgba(83, 112, 152, 0.5);
        }
        .table thead th {
            color: #9eb6da !important;
            border-bottom-color: rgba(83, 112, 152, 0.6) !important;
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .table > :not(caption) > * > * { border-color: rgba(83, 112, 152, 0.4) !important; }
        .form-control,
        .form-select,
        .form-check-input {
            background: rgba(20, 33, 56, 0.85) !important;
            border-color: rgba(83, 112, 152, 0.6) !important;
            color: #e5edf8 !important;
        }
        .form-control::placeholder { color: #8ca4c7 !important; }
        .form-control:focus,
        .form-select:focus {
            border-color: #30c8f0 !important;
            box-shadow: 0 0 0 0.2rem rgba(48, 200, 240, 0.22) !important;
        }
        .btn {
            border-radius: 10px !important;
            font-weight: 600 !important;
            border-width: 1px !important;
        }
        .btn-primary,
        .bg-primary {
            background: linear-gradient(90deg, #0ea5c6 0%, #1782e8 100%) !important;
            border-color: #27a6d9 !important;
            color: #fff !important;
        }
        .btn-outline-primary {
            color: #79e3ff !important;
            border-color: rgba(66, 194, 232, 0.7) !important;
        }
        .btn-outline-primary:hover { background: rgba(34, 193, 238, 0.18) !important; }
        .btn-outline-secondary,
        .btn-outline-info,
        .btn-outline-success,
        .btn-outline-danger {
            border-color: rgba(103, 132, 175, 0.65) !important;
            color: #b8cae8 !important;
        }
        .btn-outline-secondary:hover,
        .btn-outline-info:hover,
        .btn-outline-success:hover,
        .btn-outline-danger:hover { background: rgba(72, 99, 140, 0.24) !important; }
        .badge { font-weight: 700; letter-spacing: 0.02em; }
        .alert {
            border-radius: 12px !important;
            border: 1px solid rgba(83, 112, 152, 0.48) !important;
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
                <a href="<?php echo site_url('client/tickets'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'tickets') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Support</a>
                <a href="<?php echo site_url('store'); ?>" class="text-sm transition-colors <?php echo ($active_nav === 'store') ? 'text-slate-200' : 'text-slate-400 hover:text-slate-200'; ?>">Store</a>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 hidden sm:inline"><?php echo html_escape($user['email']); ?></span>
                <a href="<?php echo site_url('logout'); ?>" class="inline-flex items-center gap-1.5 px-2 py-1.5 rounded-lg hover:bg-[#1e2130] text-slate-400 hover:text-slate-200 transition-colors text-xs" title="Logout">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>
    <main class="flex-1 max-w-7xl mx-auto w-full p-6">
