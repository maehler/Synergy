<h2>Gene basket</h2>

<?php if (!$genes): ?>
<p>You have no genes in your basket. This is a good time to <a href="<?php echo base_url('search'); ?>">go fetch some</a>!</p>
<?php else: ?>
<section>
	<table id="basket-table" class="datatable small">
		<thead>
			<th></th>
			<th>ORF</th>
			<th>Accession</th>
			<th>Symbol</th>
			<th>Category</th>
			<th>Definition</th>
			<th>Regulatory function</th>
		</thead>
		<tbody>
		<?php foreach ($genes as $gene): ?>
			<tr>
				<td><input type="checkbox" value="<?php echo $gene['id']; ?>" checked></td>
				<td><a href="<?php echo base_url(array('gene', 'details', $gene['orf_id'])); ?>"><?php echo $gene['orf_id'] ?></a></td>
				<td><?php echo $gene['refseq_id'] ?></td>
				<td><em><?php echo $gene['symbol'] ?></em></td>
				<td><?php echo $gene['category'] ?></td>
				<td><?php echo $gene['definition'] ?></td>
				<td><?php echo $gene['tf'] == 1 ? "Yes" : "No" ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
		<tfoot>
			<th></th>
			<th>ORF</th>
			<th>Accession</th>
			<th>Symbol</th>
			<th>Category</th>
			<th>Definition</th>
			<th>Regulatory function</th>
		</tfoot>
	</table>
	<div class="clear-fix small">
		<button id="select-all">Select all</button>
		<button id="select-none">Select none</button>
		<button id="empty-basket">Empty basket</button>
		<button id="export-selection">Export selection</button>
	</div>
</section>

<section>
	<h3>Enrichment tools</h3>
	<label for="enrichment-go-radio">GO enrichment</label>
	<input id="enrichment-go-radio" name="enrichment-radio" type="radio" value="go-pane" checked>
	<label for="enrichment-motif-radio">Motif enrichment</label>
	<input id="enrichment-motif-radio" name="enrichment-radio" type="radio" value="motif-pane">

	<section id="go-pane" class="small">
		<label for="go-p-th">FDR threshold</label>
		<select id="go-p-th">
			<option value="0.01">0.01</option>
			<option value="0.05" selected>0.05</option>
			<option value="0.1">0.1</option>
		</select><br>
		<button id="start-go-enrichment">Calculate</button>

		<table id="go-table">
			<thead>
				<tr>
					<th>GO term</th>
					<th>Category</th>
					<th>Description</th>
					<th>FDR</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr>
					<th>GO term</th>
					<th>Category</th>
					<th>Description</th>
					<th>FDR</th>
				</tr>
			</tfoot>
		</table>
	</section>

	<section id="motif-pane" class="hidden small">
		<label for="motif-p-th">FDR threshold</label>
		<select id="motif-p-th">
			<option value="0.01">0.01</option>
			<option value="0.05" selected>0.05</option>
			<option value="0.1">0.10</option>
		</select><br>
		<label for="motif-q-th">FIMO q-value threshold</label>
		<select id="motif-q-th">
			<option value="0.01">0.01</option>
			<option value="0.05">0.05</option>
			<option value="0.10">0.10</option>
			<option value="0.15" selected>0.15</option>
			<option value="0.20">0.20</option>
			<option value="0.25">0.25</option>
			<option value="0.30">0.30</option>
		</select><br>
		<label for="central-motifs">Central motifs</label>
		<input id="central-motifs" type="checkbox" checked><br>
		<button id="start-motif-enrichment">Calculate</button>

		<table id="motif-table">
			<thead>
				<tr>
					<th>Motif</th>
					<th>Length</th>
					<th>Description</th>
					<th>FDR</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr>
					<th>Motif</th>
					<th>Length</th>
					<th>Description</th>
					<th>FDR</th>
				</tr>
			</tfoot>
		</table>
	</section>
</section>

<section>
	<h3>Expression profile</h3>
	<button id="draw-expression" class="small">Draw expression profile</button>
	<div class="plot-overview"></div>
	<div id="flot-expression" class="flot"></div>
	<div id="flot-legend"></div>
</section>

<script>
	var baseURL = '<?php echo base_url(); ?>';
</script>
<?php endif ?>
