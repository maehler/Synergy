<h2>Details for <?php echo $orf_id; ?></h2>

<section class="box">
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
