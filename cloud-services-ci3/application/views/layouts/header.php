<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? html_escape($title) : 'Cloud Services'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7fb; }
        .app-nav { background: linear-gradient(90deg, #0c3c78, #0e5ca8); }
        .app-card { border-radius: 14px; box-shadow: 0 8px 24px rgba(2, 20, 40, 0.08); }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark app-nav mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?php echo site_url('/'); ?>">Cloud Services CI3</a>
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
                <a class="btn btn-sm btn-light" href="<?php echo site_url('login'); ?>">Login</a>
                <a class="btn btn-sm btn-warning" href="<?php echo site_url('signup'); ?>">Signup</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container pb-5">
