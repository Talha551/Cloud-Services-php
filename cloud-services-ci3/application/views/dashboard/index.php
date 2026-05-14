<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Welcome, <?php echo html_escape($user['full_name']); ?></h3>
        <p class="text-secondary mb-0">Role: <?php echo html_escape($user['role']); ?></p>
    </div>
    <a href="<?php echo site_url('services'); ?>" class="btn btn-primary">View Services</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="app-card bg-white p-3">
            <h6 class="text-secondary mb-1">Services</h6>
            <h4 class="mb-0"><?php echo count($services); ?></h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="app-card bg-white p-3">
            <h6 class="text-secondary mb-1">Orders</h6>
            <h4 class="mb-0"><?php echo (int) $orders_count; ?></h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="app-card bg-white p-3">
            <h6 class="text-secondary mb-1">Invoices</h6>
            <h4 class="mb-0"><?php echo (int) $invoices_count; ?></h4>
        </div>
    </div>
</div>

<div class="app-card bg-white p-3 mb-3">
    <h5 class="mb-3">Quick Navigation</h5>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary btn-sm" href="<?php echo site_url('services'); ?>">Services</a>
        <a class="btn btn-outline-primary btn-sm" href="<?php echo site_url('plans'); ?>">Plans</a>
        <a class="btn btn-outline-primary btn-sm" href="<?php echo site_url('orders'); ?>">Orders</a>
        <a class="btn btn-outline-primary btn-sm" href="<?php echo site_url('invoices'); ?>">Invoices</a>
        <a class="btn btn-outline-primary btn-sm" href="<?php echo site_url('domains'); ?>">Domains</a>
        <a class="btn btn-outline-primary btn-sm" href="<?php echo site_url('tickets'); ?>">Tickets</a>
        <?php if ($user['role'] === 'admin'): ?>
            <a class="btn btn-outline-info btn-sm" href="<?php echo site_url('clients'); ?>">Clients</a>
        <?php endif; ?>
    </div>
</div>

<div class="app-card bg-white p-3">
    <h5 class="mb-3">Quick API Endpoints</h5>
    <ul class="mb-0">
        <li><a href="<?php echo site_url('api/health'); ?>"><?php echo site_url('api/health'); ?></a></li>
        <li><a href="<?php echo site_url('api/auth/profile'); ?>"><?php echo site_url('api/auth/profile'); ?></a> (requires login session)</li>
        <li><a href="<?php echo site_url('api/client/services'); ?>"><?php echo site_url('api/client/services'); ?></a> (requires login session)</li>
    </ul>
</div>
