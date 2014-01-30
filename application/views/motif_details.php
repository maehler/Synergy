<?php if (!isset($motif_name)): ?>
	<h2>No motif found...</h2>
<?php else: ?>
<h2>Details for <?php echo $motif_name; ?></h2>

<section class="box auto-width">
	<table>
		<tr>
			<th class="align-right">Name</th>
			<td><?php echo $motif_name ?></td>
		</tr>
		<tr>
			<th class="align-right">Length</th>
			<td><?php echo $length ?></td>
		</tr>
		<tr>
			<th class="align-right">Regular expression</th>
			<td><?php echo $regex ?></td>
		</tr>
		<tr>
			<th class="align-right">Central</th>
			<td><?php echo $central ? 'Yes' : 'No' ?></td>
		</tr>
	</table>
</section>

<section>
	<h3>Motif logo</h3>
	<div id="motif-logo"></div>
</section>

<section>
	<h3>Genes containing this motif</h3>
	<table id="gene-table" class="small">
		<thead>
			<tr>
				<th>ORF</th>
				<th>Direction</th>
				<th>Start</th>
				<th>Stop</th>
				<th>Score</th>
				<th><span class="italic">q</span>-value</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($genes as $gene): ?>
			<tr>
				<td><?php echo $gene['orf_id']; ?></td>
				<td><?php echo $gene['startpos'] < $gene['stoppos'] ? 'Forward' : 'Reverse'; ?></td>
				<td><?php echo $gene['startpos']; ?></td>
				<td><?php echo $gene['stoppos']; ?></td>
				<td><?php echo $gene['score']; ?></td>
				<td><?php echo $gene['q']; ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<th>ORF</th>
				<th>Direction</th>
				<th>Start</th>
				<th>Stop</th>
				<th>Score</th>
				<th><span class="italic">q</span>-value</th>
			</tr>
		</tfoot>
	</table>
</section>

<script type="text/javascript">
	var baseURL = '<?php echo base_url(); ?>';
	var pspm = <?php echo json_encode($pspm); ?>;
</script>
<?php endif; ?>
