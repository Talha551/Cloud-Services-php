<?php if ($this->session->flashdata('success')): ?><div class="mb-4 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-300"><?php echo html_escape($this->session->flashdata('success')); ?></div><?php endif; ?>
<?php if ($this->session->flashdata('error')): ?><div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300"><?php echo html_escape($this->session->flashdata('error')); ?></div><?php endif; ?>
<div class="mb-6"><h1 class="text-2xl font-bold text-white">Store</h1><p class="text-slate-400 mt-1">Choose a plan and deploy in minutes</p></div>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    <?php foreach ($plans as $plan): ?>
        <div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl p-5 flex flex-col">
            <div class="mb-5"><p class="text-sm font-semibold text-white mb-1"><?php echo html_escape($plan['name']); ?></p><div class="flex items-end gap-1 mb-2"><span class="text-3xl font-bold text-white">$<?php echo number_format((float) $plan['price'], 2); ?></span><span class="text-sm text-slate-500 mb-1">/mo</span></div><p class="text-xs text-slate-500"><?php echo (int) $plan['vcpu']; ?> vCPU, <?php echo (int) $plan['memory']; ?> MB RAM, <?php echo (int) $plan['disk']; ?> GB NVMe</p></div>
            <a href="<?php echo site_url('checkout?plan='.(int) $plan['id']); ?>" class="w-full text-center py-2.5 rounded-xl text-sm font-medium bg-indigo-600 hover:bg-indigo-500 text-white transition-colors block mt-auto">Order Now</a>
        </div>
    <?php endforeach; ?>
</div>
