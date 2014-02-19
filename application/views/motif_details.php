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
	<div class="clear-fix"></div>
	<button id="eps-logo" class="small">Download EPS</button>
	<button id="png-logo" class="small">Download PNG</button>
</section>

<section>
	<h3>Genes containing this motif</h3>
	<label for="q-value-filter"><i>q</i>-value filter</label>
	<select id="q-value-filter">
		<option value="0.01">0.01</option>
		<option value="0.05" selected>0.05</option>
		<option value="0.10">0.10</option>
		<option value="0.20">0.20</option>
		<option value="0.30">0.30</option>
	</select>
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
				<td><a href="<?php echo base_url(array('gene', 'details', $gene['orf_id'])) ?>"><?php echo $gene['orf_id']; ?></a><input type="hidden" value="<?php echo $gene['id']; ?>"></td>
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
	<button id="replace-basket">Replace basket with filtered table</button>
</section>

<section>
	<h3>TOMTOM</h3>
	
	<p><a href="http://meme.sdsc.edu/meme/cgi-bin/tomtom.cgi" class="external">TOMTOM</a>
    is an application for comparing motifs to known motifs in existing databases. 
    Here's the possibility of running TOMTOM on this motif against the 
    <a href="http://prodoric.de" class="external">Prodoric</a> and 
    <a href="http://regtransbase.lbl.gov/cgi-bin/regtransbase?page=main" class="external">
    RegTransBase</a> databases with standard settings. It can take up to 1 minute 
    before the results show up, depending on server load. If you want to run TOMTOM
    against other databases, or with custom settings, please visit
    <a href="http://meme.sdsc.edu/meme/cgi-bin/tomtom.cgi" class="external">the 
    TOMTOM website</a>.</p>

	<button id="run-tomtom">Run TOMTOM</button>
	<p id="tomtom-running" class="hidden"><span class="loading"></span>Loading...</p>
	<p id="tomtom-results"></p>
</section>

<section>
	<h3>PSPM</h3>
<pre>
<?php foreach ($pspm as $row): ?>
<?php foreach ($row as $val): ?>
<?php echo sprintf('%.3f  ', $val); ?>
<?php endforeach ?>
<?php echo '<br>'; ?>
<?php endforeach ?>
</pre>
</section>

<script type="text/javascript">
	var baseURL = '<?php echo base_url(); ?>';
	var pspm = <?php echo json_encode($pspm); ?>;
</script>
<?php endif; ?>
