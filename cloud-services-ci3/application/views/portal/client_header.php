<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape(isset($title) ? $title : 'CloudPanel'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } } };
    </script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, sans-serif; background: #0f1117; color: #e2e8f0; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #1a1d27; }
        ::-webkit-scrollbar-thumb { background: #3a3f55; border-radius: 999px; }
    </style>
</head>
<body class="bg-[#0f1117] text-slate-200">
<div class="flex flex-col min-h-screen bg-[#0f1117]">
    <nav class="bg-[#13151f] border-b border-[#2a2d3e]">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center"><span class="text-white font-bold text-sm">C</span></div>
                <span class="font-semibold text-white text-sm">CloudPanel</span>
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
