<h2>Gene basket</h2>

<?php if (!$genes): ?>
<p>You have no genes in your basket. This is a good time to <a href="<?php echo base_url('search'); ?>">go fetch some</a>!</p>
<?php else: ?>
<section>
	<button id="empty-basket">Empty basket</button>
</section>
<table id="basket-table" class="datatable small">
	<thead>
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
			<td><?php echo $gene['orf_id'] ?></td>
			<td><?php echo $gene['refseq_id'] ?></td>
			<td><em><?php echo $gene['symbol'] ?></em></td>
			<td><?php echo $gene['category'] ?></td>
			<td><?php echo $gene['definition'] ?></td>
			<td><?php echo $gene['tf'] == 1 ? "Yes" : "No" ?></td>
		</tr>
	<?php endforeach ?>
	</tbody>
	<tfoot>
		<th>ORF</th>
		<th>Accession</th>
		<th>Symbol</th>
		<th>Category</th>
		<th>Definition</th>
		<th>Regulatory function</th>
	</tfoot>
</table>
<?php endif ?>
