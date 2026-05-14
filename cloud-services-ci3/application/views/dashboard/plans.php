<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Plans</h3>
        <p class="text-secondary mb-0">Order a new VPS from PHP frontend</p>
    </div>
    <a href="<?php echo site_url('dashboard'); ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo html_escape($this->session->flashdata('success')); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo html_escape($this->session->flashdata('error')); ?></div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($plans as $plan): ?>
        <div class="col-lg-4 col-md-6">
            <div class="app-card bg-white p-3 h-100">
                <h5 class="mb-1"><?php echo html_escape($plan['name']); ?></h5>
                <p class="text-secondary mb-2">$<?php echo number_format((float) $plan['price'], 2); ?>/month</p>
                <ul class="small text-secondary mb-3">
                    <li><?php echo (int) $plan['vcpu']; ?> vCPU</li>
                    <li><?php echo (int) $plan['memory']; ?> MB RAM</li>
                    <li><?php echo (int) $plan['disk']; ?> GB Disk</li>
                    <li><?php echo (int) $plan['bandwidth']; ?> GB Bandwidth</li>
                </ul>
                <form method="post" action="<?php echo site_url('orders/create'); ?>">
                    <input type="hidden" name="plan_id" value="<?php echo (int) $plan['id']; ?>">
                    <div class="mb-2">
                        <input type="text" class="form-control" name="hostname" placeholder="Hostname" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="text" class="form-control" name="location_id" placeholder="Location ID" value="1">
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="os_id" placeholder="OS ID" value="1">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2">Create Order</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
