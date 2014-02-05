<h2>Gene basket</h2>

<?php if (!$genes): ?>
<p>You have no genes in your basket. This is a good time to <a href="<?php echo base_url('search'); ?>">go fetch some</a>!</p>
<?php else: ?>
<section>
	<table id="basket-table" class="dataTable small">
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

<?php echo $enrichment_view; ?>

<?php echo $plot_view; ?>

<script>
	var baseURL = '<?php echo base_url(); ?>';
</script>
<?php endif ?>
