<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white"><?php echo html_escape(isset($title) ? $title : 'Profile'); ?></h1>
        <p class="text-slate-400 mt-1">Update your account details and password.</p>
    </div>

    <?php if (!empty($flash_success)): ?>
        <div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300"><?php echo html_escape($flash_success); ?></div>
    <?php endif; ?>
    <?php if (!empty($flash_error)): ?>
        <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300"><?php echo html_escape($flash_error); ?></div>
    <?php endif; ?>

    <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5">
        <form method="post" action="<?php echo html_escape(isset($submit_url) ? $submit_url : site_url('client/profile/update')); ?>" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Full Name</label>
                    <input type="text" name="full_name" value="<?php echo html_escape(isset($user['full_name']) ? $user['full_name'] : ''); ?>" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Email</label>
                    <input type="email" name="email" value="<?php echo html_escape(isset($user['email']) ? $user['email'] : ''); ?>" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm" required>
                </div>
            </div>

            <div class="border-t border-[#2a2d3e] pt-4">
                <h2 class="text-sm font-semibold text-white">Change Password (Optional)</h2>
                <p class="text-xs text-slate-500 mt-1">Leave these fields empty if you do not want to change password.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Current Password</label>
                    <input type="password" name="current_password" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm" autocomplete="current-password">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">New Password</label>
                    <input type="password" name="new_password" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm" autocomplete="new-password">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="w-full bg-[#0f1117] border border-[#2a2d3e] rounded-lg px-3 py-2 text-slate-200 text-sm" autocomplete="new-password">
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 flex-wrap pt-2">
                <span class="text-xs text-slate-500">Role: <?php echo html_escape(isset($user['role']) ? ucfirst($user['role']) : 'User'); ?></span>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-lg transition-colors">Save Changes</button>
            </div>
        </form>
    </div>
</div>
