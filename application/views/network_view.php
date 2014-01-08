<?php if (!$basket): ?>
<h2>Network</h2>
<p>No genes are selected. Now is a good time to <a href="<?php echo base_url('search') ?>">go fetch some</a>!</p>
<?php else: ?>
	
<aside id="network-menu" class="small">
	<h3>Settings</h3>
	<hr>
	<div>
		<label for="network-type">Network type</label><br>
		<select id="network-type">
			<option value="clr_complete">CLR complete</option>
			<option value="clr_subset">CLR subset</option>
		</select><br>
		<label for="network-threshold">Correlation threshold</label>
		<input id="network-threshold" type="number" value="5">
	</div>
	<hr>
	<div>
		<button>Select all</button>
		<button>Select none</button>
	</div>
</aside>

<h2>Network</h2>

<div id="network-container"></div>
<?php endif ?>
