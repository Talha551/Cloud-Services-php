<div class="min-h-screen flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-white/70 backdrop-blur">
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-br from-sky-500 to-blue-700 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i data-lucide="cloud" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-bold text-slate-900 text-sm">CloudPanel</span>
        </a>
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-1.5 text-xs text-slate-600 hover:text-slate-900 transition-colors">
            <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to website
        </a>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            <div class="hidden lg:flex rounded-2xl border border-slate-200 bg-slate-900 text-white p-8 flex-col justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-300 mb-3">CloudPanel Access</p>
                    <h2 class="text-3xl mb-3">Manage your cloud stack from one panel.</h2>
                    <p class="text-sm text-slate-300">Monitor services, open console sessions, and control infrastructure operations in seconds.</p>
                </div>
                <div class="space-y-2 text-sm text-slate-300">
                    <p class="inline-flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-cyan-300"></i> Instant VPS lifecycle actions</p>
                    <p class="inline-flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-cyan-300"></i> Billing, invoices, and tickets in one place</p>
                    <p class="inline-flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-cyan-300"></i> Secure account and session controls</p>
                </div>
            </div>
            <div class="w-full max-w-sm lg:max-w-none mx-auto">
                <div class="text-center mb-8">
                    <h1 class="text-3xl text-slate-900 mb-2">Welcome back</h1>
                    <p class="text-sm text-slate-600">Sign in to your CloudPanel account</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo html_escape($error); ?></div>
                <?php endif; ?>
                <?php if ($this->input->get('registered')): ?>
                    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">Account created successfully. You can sign in now.</div>
                <?php endif; ?>

                <form method="post" action="<?php echo site_url('login'); ?>" class="space-y-4 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Email</label>
                        <input type="email" name="email" required placeholder="admin@example.com" value="<?php echo html_escape($this->input->post('email', true)); ?>" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-sky-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-sky-500 transition-colors">
                    </div>
                    <button type="submit" class="w-full mt-2 bg-sky-600 hover:bg-sky-500 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">Sign In</button>
                </form>

                <p class="text-center text-xs text-slate-600 mt-6">Don't have an account? <a href="<?php echo site_url('register'); ?>" class="text-sky-600 hover:text-sky-500 transition-colors">Create one free</a></p>
            </div>
        </div>
    </div>
</div>
