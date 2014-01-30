<h2>Gene search</h2>

<div class="box narrow"><span id="select-count">0</span> genes selected</div>
<table id="gene-table" class="datatable small">
	<thead>
		<th>Selected</th>
		<th>ORF</th>
		<th>Accession</th>
		<th>Symbol</th>
		<th>Category</th>
		<th>Definition</th>
		<th>Regulatory function</th>
		<th>Operon member</th>
	</thead>
	<tbody>
	</tbody>
	<tfoot>
		<th>Selected</th>
		<th>ORF</th>
		<th>Accession</th>
		<th>Symbol</th>
		<th>Category</th>
		<th>Definition</th>
		<th>Regulatory function</th>
		<th>Operon member</th>
	</tfoot>
</table>

<div class="clear-fix small">
	<button id="load-example">Load example gene set</button>
	<button id="clear-selection">Clear selection</button>
	<button id="select-all">Select all</button>
</div>

<section>
	<h3>Upload gene list</h3>
	<div class="small">
	<?php echo form_open_multipart('search/upload'); ?>
		<label for="gene-file">Upload gene list:</label>
		<input type="file" name="gene-file", id="gene-file">
		<input type="submit" value="Upload">
	<?php echo form_close(); ?>
	</div>
</section>

<script type="text/javascript">
	var selected = <?php echo json_encode($basket); ?>
</script>
