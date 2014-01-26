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
				<td><?php echo $motif['name']; ?></td>
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
</section>

<section>
	<h3>Expression profile</h3>
	<div id="flot-expression" class="flot"></div>
</section>

<script type="text/javascript">
	var expression = <?php echo json_encode($expression['data']); ?>;
	var expAnnot = <?php echo json_encode($expression['annotation']); ?>;
</script>
<?php endif ?>
