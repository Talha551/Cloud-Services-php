<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? html_escape($title) : 'Cloud Services'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Manrope, sans-serif;
            background:
                radial-gradient(900px 420px at 10% -10%, #dff5ff 0%, transparent 70%),
                radial-gradient(900px 420px at 90% -15%, #ffe9db 0%, transparent 70%),
                #f5f8fc;
            color: #11203b;
        }
        h1, h2, h3, h4, h5, h6 { font-family: Sora, sans-serif; }
        .app-nav { background: linear-gradient(90deg, #0f2f58, #1d5da0); box-shadow: 0 8px 30px rgba(8, 21, 40, 0.2); }
        .app-card {
            border-radius: 16px;
            border: 1px solid #d7e3f3;
            box-shadow: 0 12px 28px rgba(16, 37, 68, 0.08);
        }
        .btn { border-radius: 10px; font-weight: 600; }
        .btn-primary { background: linear-gradient(90deg, #0ea5c6 0%, #1f7ae0 100%); border-color: #1f7ae0; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark app-nav mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?php echo site_url('/'); ?>">CloudPanel</a>
        <div class="d-flex flex-wrap gap-2 justify-content-end">
            <?php if ($this->session->userdata('user_id')): ?>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('services'); ?>">Services</a>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('plans'); ?>">Plans</a>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('orders'); ?>">Orders</a>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('invoices'); ?>">Invoices</a>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('domains'); ?>">Domains</a>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('tickets'); ?>">Tickets</a>
                <?php if ($this->session->userdata('user_role') === 'admin'): ?>
                    <a class="btn btn-sm btn-info" href="<?php echo site_url('clients'); ?>">Clients</a>
                <?php endif; ?>
                <a class="btn btn-sm btn-warning" href="<?php echo site_url('logout'); ?>">Logout</a>
            <?php else: ?>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('features'); ?>">Features</a>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('pricing'); ?>">Pricing</a>
                <a class="btn btn-sm btn-light" href="<?php echo site_url('login'); ?>">Login</a>
                <a class="btn btn-sm btn-warning" href="<?php echo site_url('signup'); ?>">Signup</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container pb-5">
