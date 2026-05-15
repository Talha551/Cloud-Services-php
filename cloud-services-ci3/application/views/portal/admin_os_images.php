<div class="mb-6">
	<h1 class="text-2xl font-bold text-white">OS Images & Applications</h1>
	<p class="text-slate-400 mt-1">Comprehensive deployment templates and one-click apps catalog.</p>
</div>

<div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden mb-6">
	<div class="grid grid-cols-4 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">
		<div>ID</div><div>Name</div><div>Type</div><div>Status</div>
	</div>
	<?php if (!empty($rows) && is_array($rows)): ?>
		<?php foreach ($rows as $row): ?>
			<?php
				$name = '';
				foreach (array('label', 'name', 'title', 'version_name') as $key) {
					if (!empty($row[$key])) { $name = (string) $row[$key]; break; }
				}
				if ($name === '') { $name = 'OS Template'; }
				$type = !empty($row['type']) ? (string) $row['type'] : 'linux';
				$status = !empty($row['status']) ? (string) $row['status'] : 'available';
				$id = !empty($row['id']) ? (int) $row['id'] : 0;
			?>
			<div class="grid grid-cols-4 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
				<div class="text-slate-400"><?php echo $id > 0 ? $id : '-'; ?></div>
				<div class="font-medium text-white"><?php echo html_escape($name); ?></div>
				<div class="text-slate-400 uppercase text-xs"><?php echo html_escape($type); ?></div>
				<div><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border bg-green-500/15 text-green-400 border-green-500/30"><?php echo html_escape($status); ?></span></div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="px-5 py-8 text-sm text-slate-500">No OS images available.</div>
	<?php endif; ?>
</div>

<div class="bg-[#13151f] border border-[#2a2d3e] rounded-xl overflow-hidden">
	<div class="grid grid-cols-4 px-5 py-3 text-xs uppercase tracking-wide text-slate-500 border-b border-[#2a2d3e]">
		<div>ID</div><div>Application</div><div>Category</div><div>Status</div>
	</div>
	<?php if (!empty($app_rows) && is_array($app_rows)): ?>
		<?php foreach ($app_rows as $row): ?>
			<?php
				$name = '';
				foreach (array('label', 'name', 'title') as $key) {
					if (!empty($row[$key])) { $name = (string) $row[$key]; break; }
				}
				if ($name === '') { $name = 'Application'; }
				$type = !empty($row['type']) ? (string) $row['type'] : 'app';
				$status = !empty($row['status']) ? (string) $row['status'] : 'available';
				$id = !empty($row['id']) ? (int) $row['id'] : 0;
			?>
			<div class="grid grid-cols-4 px-5 py-3 text-sm border-b border-[#1e2130] last:border-0">
				<div class="text-slate-400"><?php echo $id > 0 ? $id : '-'; ?></div>
				<div class="font-medium text-white"><?php echo html_escape($name); ?></div>
				<div class="text-slate-400 uppercase text-xs"><?php echo html_escape($type); ?></div>
				<div><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border bg-green-500/15 text-green-400 border-green-500/30"><?php echo html_escape($status); ?></span></div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="px-5 py-8 text-sm text-slate-500">No applications available.</div>
	<?php endif; ?>
</div>
