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

<script type="text/javascript">
	var pspm = <?php echo json_encode($pspm); ?>;
</script>
<?php endif; ?>
