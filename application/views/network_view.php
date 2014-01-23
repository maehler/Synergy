<?php if (!$basket): ?>
<h2>Network</h2>
<p>No genes are selected. Now is a good time to <a href="<?php echo base_url('search') ?>">go fetch some</a>!</p>
<?php else: ?>

<aside id="network-menu" class="small">
	<h3>Settings</h3>
	<hr>

	<?php echo form_open('network'); ?>
	<div>
		<label for="network-type">Network type</label><br>
		<select name="network-type" id="network-type">
			<option value="clr_complete"<?php echo $settings['network_type'] == 'clr_complete' ? " selected" : ""; ?>>CLR complete</option>
			<option value="clr_subset"<?php echo $settings['network_type'] == 'clr_subset' ? " selected" : ""; ?>>CLR subset</option>
		</select><br>
		<label for="network-threshold">Correlation threshold</label>
		<input name="network-threshold" id="network-threshold" type="number" value="<?php echo $settings['network_threshold']; ?>"><br>
		<label for="expand-threshold">Expansion threshold</label>
		<input name="expand-threshold" id="expand-threshold" type="number" value="<?php echo $settings['expand-threshold']; ?>"></br>
		<!--<label for="expand-network">Expand network</label>
		<input type="checkbox" name="expand-network" id="expand-network"<?php echo $settings['expand'] ? " checked" : ""; ?>>-->
		<button id="submit" type="submit">Redraw</button>
	</div>
	<?php echo form_close(); ?>
	
	<hr>
	<div>
		<button id="select-all">Select all</button>
		<button id="select-none">Select none</button>
		<button id="select-invert">Invert selection</button>
		<button id="select-neighbors">Grow selection</button>
		<button id="remove-selection">Remove selection</button>
	</div>
	<hr>
	<div>
		<button id="basket-add">Add selection to basket</button>
		<button id="basket-remove">Remove selection from basket</button>
	</div>
</aside>

<h2>Network</h2>

<div id="network-container">
	<div id="network-stats" class="small"><span id="node-count">0</span> nodes, <span id="edge-count">0</span> edges</div>
</div>
<?php endif ?>
<script type="text/javascript">
	var network_data = <?php echo $network; ?>;
</script>
