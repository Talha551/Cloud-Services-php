<?php if (!empty($success)): ?>
<div class="min-h-screen bg-[#0a0c12] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-indigo-600 rounded-md flex items-center justify-center">
                <i data-lucide="globe" class="w-3.5 h-3.5 text-white"></i>
            </div>
            <span class="font-bold text-white text-sm">CloudPanel</span>
        </a>
    </div>
    <div class="flex-1 flex items-center justify-center p-6">
        <div class="text-center max-w-sm">
            <div class="w-16 h-16 bg-green-500/10 border border-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="check-circle-2" class="w-7 h-7 text-green-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-3">Account Created!</h2>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">Your account has been created successfully. You can now sign in and start deploying servers.</p>
            <a href="<?php echo site_url('login?registered=1'); ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition-colors">Sign In Now</a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="min-h-screen bg-[#0a0c12] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-indigo-600 rounded-md flex items-center justify-center">
                <i data-lucide="globe" class="w-3.5 h-3.5 text-white"></i>
            </div>
            <span class="font-bold text-white text-sm">CloudPanel</span>
        </a>
        <a href="<?php echo site_url('/'); ?>" class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-300 transition-colors"><i data-lucide="arrow-left" class="w-3 h-3"></i> Back to website</a>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-sm">
            <div class="text-center mb-7">
                <h1 class="text-2xl font-bold text-white mb-2">Create your account</h1>
                <p class="text-sm text-slate-500">Start deploying servers in minutes</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300"><?php echo html_escape($error); ?></div>
            <?php endif; ?>

            <form method="post" action="<?php echo site_url('register'); ?>" class="space-y-3.5">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">First Name</label>
                        <input name="first_name" value="<?php echo html_escape($this->input->post('first_name', true)); ?>" placeholder="John" class="w-full bg-[#0a0c12] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Last Name</label>
                        <input name="last_name" value="<?php echo html_escape($this->input->post('last_name', true)); ?>" placeholder="Doe" class="w-full bg-[#0a0c12] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Email Address <span class="text-red-400">*</span></label>
                    <input type="email" required name="email" value="<?php echo html_escape($this->input->post('email', true)); ?>" placeholder="john@example.com" class="w-full bg-[#0a0c12] border border-white/10 rounded-lg px-3 py-2.5 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Password <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input id="signup-password" type="password" required minlength="8" name="password" placeholder="Create a strong password" class="w-full bg-[#0a0c12] border border-white/10 rounded-lg px-3 py-2.5 pr-10 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors">
                        <button id="toggle-password" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                    </div>
                    <div class="mt-2 space-y-2">
                        <div class="flex gap-1">
                            <div class="h-1 flex-1 rounded-full bg-white/10" data-strength-bar="0"></div>
                            <div class="h-1 flex-1 rounded-full bg-white/10" data-strength-bar="1"></div>
                            <div class="h-1 flex-1 rounded-full bg-white/10" data-strength-bar="2"></div>
                            <div class="h-1 flex-1 rounded-full bg-white/10" data-strength-bar="3"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-1 text-xs">
                            <div id="check-length" class="text-slate-600">At least 8 characters</div>
                            <div id="check-number" class="text-slate-600">Contains a number</div>
                            <div id="check-upper" class="text-slate-600">Contains uppercase</div>
                            <div id="check-symbol" class="text-slate-600">Contains symbol</div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Confirm Password <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input id="signup-confirm" type="password" required minlength="8" name="confirm_password" placeholder="Repeat your password" class="w-full bg-[#0a0c12] border border-white/10 rounded-lg px-3 py-2.5 pr-10 text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors">
                        <button id="toggle-confirm" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                    </div>
                    <p id="confirm-error" class="hidden text-xs text-red-400 mt-1">Passwords do not match</p>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">By creating an account you agree to our <a href="#" class="text-indigo-400 hover:text-indigo-300">Terms of Service</a> and <a href="#" class="text-indigo-400 hover:text-indigo-300">Privacy Policy</a>.</p>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 mt-1">Create Account</button>
            </form>
            <p class="text-center text-xs text-slate-600 mt-5">Already have an account? <a href="<?php echo site_url('login'); ?>" class="text-indigo-400 hover:text-indigo-300 transition-colors">Sign in</a></p>
        </div>
    </div>
</div>
<script>
(function () {
    var password = document.getElementById('signup-password');
    var confirmPassword = document.getElementById('signup-confirm');
    var togglePassword = document.getElementById('toggle-password');
    var toggleConfirm = document.getElementById('toggle-confirm');
    var confirmError = document.getElementById('confirm-error');
    var bars = document.querySelectorAll('[data-strength-bar]');
    var checks = {
        length: document.getElementById('check-length'),
        number: document.getElementById('check-number'),
        upper: document.getElementById('check-upper'),
        symbol: document.getElementById('check-symbol')
    };

    function refreshMatch() {
        if (!confirmPassword.value) {
            confirmError.classList.add('hidden');
            confirmPassword.classList.remove('border-red-500/50', 'border-green-500/50');
            return;
        }
        if (confirmPassword.value !== password.value) {
            confirmError.classList.remove('hidden');
            confirmPassword.classList.add('border-red-500/50');
            confirmPassword.classList.remove('border-green-500/50');
        } else {
            confirmError.classList.add('hidden');
            confirmPassword.classList.remove('border-red-500/50');
            confirmPassword.classList.add('border-green-500/50');
        }
    }

    function refreshStrength() {
        var value = password.value;
        var states = {
            length: value.length >= 8,
            number: /\d/.test(value),
            upper: /[A-Z]/.test(value),
            symbol: /[^a-zA-Z0-9]/.test(value)
        };
        var score = 0;
        for (var key in states) {
            if (states[key]) score++;
            if (checks[key]) {
                checks[key].className = states[key] ? 'text-green-400' : 'text-slate-600';
            }
        }
        var color = score <= 1 ? 'bg-red-500' : (score === 2 ? 'bg-yellow-500' : 'bg-green-500');
        for (var i = 0; i < bars.length; i++) {
            bars[i].className = 'h-1 flex-1 rounded-full ' + (i < score ? color : 'bg-white/10');
        }
        refreshMatch();
    }

    function toggleField(input) {
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    if (togglePassword) {
        togglePassword.addEventListener('click', function () { toggleField(password); });
    }
    if (toggleConfirm) {
        toggleConfirm.addEventListener('click', function () { toggleField(confirmPassword); });
    }
    if (password) {
        password.addEventListener('input', refreshStrength);
    }
    if (confirmPassword) {
        confirmPassword.addEventListener('input', refreshMatch);
    }
}());
</script>
<?php endif; ?>
