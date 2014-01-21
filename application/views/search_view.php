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
<div class="clear-fix"></div>

<button id="load-example">Load example gene set</button>
<button id="clear-selection">Clear selection</button>

<script type="text/javascript">
	var selected = <?php echo json_encode($basket); ?>
</script>