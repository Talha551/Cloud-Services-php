<div class="min-h-screen bg-[#0a0c12] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-indigo-600 rounded-md flex items-center justify-center">
                <i data-lucide="globe" class="w-3.5 h-3.5 text-white"></i>
            </div>
            <span class="font-bold text-white text-sm">CloudPanel</span>
        </a>
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-300 transition-colors">
            <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to website
        </a>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-white mb-2">Welcome back</h1>
                <p class="text-sm text-slate-500">Sign in to your CloudPanel account</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300"><?php echo html_escape($error); ?></div>
            <?php endif; ?>
            <?php if ($this->input->get('registered')): ?>
                <div class="mb-4 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-300">Account created successfully. You can sign in now.</div>
            <?php endif; ?>

            <form method="post" action="<?php echo site_url('login'); ?>" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Email</label>
                    <input type="email" name="email" required placeholder="admin@example.com" value="<?php echo html_escape($this->input->post('email', true)); ?>" class="w-full bg-[#0a0c12] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-[#0a0c12] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <button type="submit" class="w-full mt-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">Sign In</button>
            </form>

            <p class="text-center text-xs text-slate-600 mt-6">Don't have an account? <a href="<?php echo site_url('register'); ?>" class="text-indigo-400 hover:text-indigo-300 transition-colors">Create one free</a></p>
        </div>
    </div>
</div>
