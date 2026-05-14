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
<div class="flex h-screen bg-[#0f1117] text-slate-200 overflow-hidden">
    <aside class="w-60 bg-[#13151f] border-r border-[#2a2d3e] flex flex-col shrink-0">
        <div class="flex items-center gap-3 px-5 py-5 border-b border-[#2a2d3e]">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i data-lucide="globe" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-semibold text-white text-sm tracking-wide">CloudPanel</span>
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
        <div class="px-5 py-3 border-t border-[#2a2d3e]"><p class="text-xs text-slate-600">SolusVM2 Platform</p></div>
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
