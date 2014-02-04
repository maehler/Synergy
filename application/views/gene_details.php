<?php if (!isset($orf_id)): ?>
	<h2>No gene found...</h2>
<?php else: ?>
<h2>Details for <?php echo $orf_id; ?></h2>

<section class="box auto-width">
	<table>
		<tr>
			<th class="align-right">ORF</th>
			<td><?php echo $orf_id ?></td>
		</tr>
		<tr>
			<th class="align-right">Symbol</th>
			<td><em><?php echo $symbol ?></em></td>
		</tr>
		<tr>
			<th class="align-right">Category</th>
			<td><?php echo $category ?></td>
		</tr>
		<tr>
			<th class="align-right">Definition</th>
			<td><?php echo $definition ?></td>
		</tr>
		<tr>
			<th class="align-right">Regulatory function</th>
			<td><?php echo $tf == 1 ? 'Yes' : 'No' ?></td>
		</tr>
		<tr>
			<th>External resources</th>
			<td><a class="external" href="http://genome.microbedb.jp/cyanobase/Synechocystis/genes/<?php echo $orf_id ?>">CyanoBase</a>,
				<a class="external" href="http://www.genome.jp/dbget-bin/www_bget?syn:<?php echo $orf_id ?>">KEGG</a>,
				<a class="external" href="http://www.ncbi.nlm.nih.gov/gene?Db=gene&amp;Cmd=DetailsSearch&amp;Term=<?php echo $orf_id ?>">NCBI</a>
			</td>
		</tr>
	</table>
</section>

<section>
	<h3>Promoter profile</h3>
	<table id="motif-table" class="small">
		<thead>
			<tr>
				<th>Motif</th>
				<th>Direction</th>
				<th>Start</th>
				<th>Stop</th>
				<th>FIMO score</th>
				<th>FIMO <span class="italic">q-value</span></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($motifs as $motif): ?>
			<tr>
				<td><a href="<?php echo base_url(array('motif', 'details', $motif['name'])) ?>"><?php echo $motif['name']; ?></a></td>
				<td><?php echo $motif['startpos'] > $motif['stoppos'] ? 'Reverse' : 'Forward'; ?></td>
				<td><?php echo $motif['startpos']; ?></td>
				<td><?php echo $motif['stoppos']; ?></td>
				<td><?php echo $motif['score']; ?></td>
				<td><?php echo $motif['q']; ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<th>Motif</th>
				<th>Direction</th>
				<th>Start</th>
				<th>Stop</th>
				<th>FIMO score</th>
				<th>FIMO <span class="italic">q-value</span></th>
			</tr>
		</tfoot>
	</table>
	<div class="clear-fix"></div>
</section>

<section>
	<h3>Expression profile</h3>
	<?php if (count($expression['data']) !== 0): ?>		
	<div class="flot-overview"></div>
	<button id="reset-zoom" class="small">Reset zoom</button>
	<div id="flot-expression" class="flot"></div>
	<?php else: ?>
	<p>No expression data available</p>
	<?php endif ?>
</section>

<script type="text/javascript">
	var expression = <?php echo json_encode($expression['data']); ?>;
	var expAnnot = <?php echo json_encode($expression['annotation']); ?>;
</script>
<?php endif ?>
