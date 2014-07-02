<?php if (!$basket): ?>
<h2>Network</h2>
<p>No genes are selected. Now is a good time to <a href="<?php echo base_url('search') ?>">go fetch some</a>!</p>
<?php else: ?>

<aside id="network-menu" class="small">
	<h3>Settings</h3>
	<hr>

	<?php echo form_open('network', 'class="narrow"'); ?>
	<fieldset class="clean">
		<ol>
			<li>		
				<label for="network-type">Network type</label>
				<select name="network-type" id="network-type">
					<option value="clr_complete"<?php echo $settings['network_type'] == 'clr_complete' ? " selected" : ""; ?>>CLR complete</option>
					<option value="clr_subset"<?php echo $settings['network_type'] == 'clr_subset' ? " selected" : ""; ?>>CLR subset</option>
				</select>
			</li>
			<li>
				<label for="network-threshold">Correlation threshold</label>
				<input name="network-threshold" id="network-threshold" type="number" value="<?php echo $settings['network_threshold']; ?>">
			</li>
			<li>
				<label for="expand-threshold">Expansion threshold</label>
				<input name="expand-threshold" id="expand-threshold" type="number" value="<?php echo $settings['expand-threshold']; ?>">
			</li>
			<li>
				<label for="expand-animate">Layout on expansion</label>
				<input type="checkbox" id="expand-animate" name="expand-animate"<?php echo $settings['animate'] ? " checked" : ""; ?>>
			</li>
			<li>
				<label for="node-labels">Node labels</label>
				<select id="node-labels" name="node-labels">
					<option value="data(orf)"<?php echo $settings['node_labels'] == 'data(orf)' ? ' selected' : ''; ?>>ORF</option>
					<option value="data(symbol)"<?php echo $settings['node_labels'] == 'data(symbol)' ? ' selected' : ''; ?>>Symbol</option>
				</select>
			</li>
			<li>
				<button id="submit" type="submit">Redraw</button>
			</li>
		</ol>
	</fieldset>
	<?php echo form_close(); ?>
	
	<hr>
	<div>
		<button id="select-all">Select all</button>
		<button id="select-none">Select none</button>
		<button id="select-highlighted">Select highlighted</button>
		<button id="select-invert">Invert selection</button>
		<button id="select-neighbors">Grow selection</button>
		<button id="remove-selection">Delete selection</button>
	</div>
	<hr>
	<div>
		<button id="basket-add">Add to basket</button>
		<button id="basket-remove">Delete from basket</button>
	</div>
	<hr>
	<div>
		<button id="export-gml">Export GML</button>
		<button id="export-png">Export PNG</button>
		<button id="export-pdf">Export PDF</button>
	</div>
</aside>

<h2>Network</h2>

<div id="network-search" class="small">
	<input type="text" id="network-search-input">
	<button id="network-search-button">Search</button>
</div>
<div id="network-container" class="clear-right">
	<div id="network-stats" class="small"><span id="node-count">0</span> nodes, <span id="edge-count">0</span> edges</div>
	<div id="select-stats" class="small"><span id="select-count">0</span> nodes selected</div>
	<div id="load-message">
		<span id="load-gif"></span><span class="message">Loading network...</span>
	</div>
	<div id="network-loading"></div>
</div>

<?php echo $enrichment_view; ?>

<?php echo $plot_view; ?>

<?php endif ?>
<script type="text/javascript">
	var baseURL = "<?php echo base_url(); ?>";
	var network_data = <?php echo $network; ?>;
</script>
