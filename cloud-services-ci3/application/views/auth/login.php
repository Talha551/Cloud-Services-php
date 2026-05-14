<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="app-card bg-white p-4">
            <h3 class="mb-3">Login</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo html_escape($error); ?></div>
            <?php endif; ?>
            <form method="post" action="<?php echo site_url('login'); ?>">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Sign In</button>
            </form>
        </div>
    </div>
</div>
