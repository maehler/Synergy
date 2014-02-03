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

<section>
	<h3>Gene lists</h3>
	<div id="list-wrapper" class="small tabs">
		<ul>
			<li><a href="#go-tab">GO</a></li>
			<li><a href="#motif-tab">Motifs</a></li>
			<li><a href="#coexp-tab">Co-expression</a></li>
			<li><a href="#tf-tab">Regulatory genes</a></li>
		</ul>

		<div id="go-tab">
			<p>GO lists</p>
		</div>

		<div id="motif-tab">
			<p>Motif lists</p>
		</div>

		<div id="coexp-tab">
			<p>Co-expression cluster lists</p>
		</div>

		<div id="tf-tab">
			<p>Regulatory gene neighborhood lists</p>
		</div>
	</div>
</section>

<script type="text/javascript">
	var selected = <?php echo json_encode($basket); ?>
</script>
