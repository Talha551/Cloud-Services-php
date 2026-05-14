<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Services</h3>
    <a href="<?php echo site_url('dashboard'); ?>" class="btn btn-outline-secondary">Back</a>
</div>

<div class="app-card bg-white p-3">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>IP</th>
                    <th>Action API</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo (int) $service['id']; ?></td>
                        <td><?php echo html_escape($service['name']); ?></td>
                        <td>
                            <?php if ($service['status'] === 'running'): ?>
                                <span class="badge bg-success">running</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">stopped</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo html_escape($service['ip_address']); ?></td>
                        <td>
                            <a href="<?php echo site_url('api/client/services/'.$service['id'].'/start'); ?>" class="btn btn-sm btn-outline-success">Start</a>
                            <a href="<?php echo site_url('api/client/services/'.$service['id'].'/stop'); ?>" class="btn btn-sm btn-outline-danger">Stop</a>
                            <a href="<?php echo site_url('api/client/services/'.$service['id'].'/restart'); ?>" class="btn btn-sm btn-outline-primary">Restart</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
