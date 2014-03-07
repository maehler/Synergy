<?php if (!isset($orf_id)): ?>
	<h2>No gene found...</h2>
<?php else: ?>
<h2>
	Details for <?php echo $orf_id; ?>
	<span id="add-remove-basket"
		class="<?php echo $in_basket ? 'remove-from-basket' : 'add-to-basket'; ?>"
		title="<?php echo $in_basket ? 'Remove gene from basket' : 'Add gene to basket'; ?>"></span>
</h2>

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
	<form>
		<fieldset>
			<legend>Table filtering</legend>
			<ol>
				<li>
					<label for="motif-q-filter"><i>q</i>-value filter</label>
					<select id="motif-q-filter">
						<option value="0.01">&lt; 0.01</option>
						<option value="0.05">&lt; 0.05</option>
						<option value="0.10">&lt; 0.10</option>
						<option value="0.15" selected>&lt; 0.15</option>
						<option value="0.20">&lt; 0.20</option>
						<option value="0.25">&lt; 0.25</option>
						<option value="0.30">&lt; 0.30</option>
					</select>
				</li>
				<li>
					<label for="motif-only-central">Only central motifs</label>
					<input id="motif-only-central" type="checkbox" checked>
				</li>
			</ol>
		</fieldset>
	</form>
	<table id="motif-table" class="small">
		<thead>
			<tr>
				<th>Motif</th>
				<th>Direction</th>
				<th>Start</th>
				<th>Stop</th>
				<th>Central</th>
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
				<td><?php echo $motif['central'] ? '&#x2713;' : ''; ?></td>
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
				<th>Central</th>
				<th>FIMO score</th>
				<th>FIMO <span class="italic">q-value</span></th>
			</tr>
		</tfoot>
	</table>
	<div class="clear-fix"></div>
</section>

<?php echo $expression_plot; ?>

<script type="text/javascript">
	var baseURL = "<?php echo base_url(); ?>";
	var geneID = <?php echo $id; ?>;
</script>
<?php endif ?>
