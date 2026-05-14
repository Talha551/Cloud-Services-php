<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1"><?php echo html_escape($title); ?></h3>
        <p class="text-secondary mb-0">Signed in as <?php echo html_escape($user['full_name']); ?></p>
    </div>
    <a href="<?php echo site_url('dashboard'); ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
</div>

<div class="app-card bg-white p-3">
    <?php if (empty($rows)): ?>
        <p class="text-secondary mb-0">No records found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <th><?php echo html_escape(ucwords(str_replace('_', ' ', $column))); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <?php foreach ($columns as $column): ?>
                                <td>
                                    <?php
                                    $value = isset($row[$column]) ? $row[$column] : '';
                                    if ($column === 'total') {
                                        echo '$'.number_format((float) $value, 2);
                                    } else {
                                        echo html_escape((string) $value);
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
