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
	<p>Note that a gene can occur multiple times in the table below since
	a motif can be assigned to more than one position in its promoter.
	</p>
	<form>
		<fieldset>
			<legend>Table filtering</legend>
			<label for="q-value-filter"><i>q</i>-value filter</label>
			<select id="q-value-filter">
				<option value="0.01">&lt;0.01</option>
				<option value="0.05">&lt;0.05</option>
				<option value="0.10">&lt;0.10</option>
				<option value="0.15" selected>&lt;0.15</option>
				<option value="0.20">&lt;0.20</option>
				<option value="0.25">&lt;0.25</option>
				<option value="0.30">&lt;0.30</option>
			</select>
		</fieldset>
	</form>
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
	
	<p><a href="http://meme.nbcr.net/meme/cgi-bin/tomtom.cgi" class="external">TOMTOM</a>
    is an application for comparing motifs to known motifs in existing databases. 
    Here's the possibility of running TOMTOM on this motif against the 
    <a href="http://prodoric.tu-bs.de" class="external">Prodoric</a> or
    <a href="http://regtransbase.lbl.gov/cgi-bin/regtransbase?page=main" class="external">
    RegTransBase</a> database. It can take up to 1 minute 
    before the results show up, depending on server load. If you want to run TOMTOM
    against other databases, or with more custom settings, please visit
    <a href="http://meme.nbcr.net/meme/cgi-bin/tomtom.cgi" class="external">the 
    TOMTOM website</a>.</p>

   	<form>
   		<fieldset>
   			<legend>Options</legend>
   			<ol>
   				<li>
   					<label for="tomtom-db">Database</label>
				    <select id="tomtom-db" required>
				    	<option value="prodoric" selected>Prodoric</option>
				    	<option value="regtransbase">RegTransBase</option>
				    </select>	
   				</li>
   				<li>
   					<label for="tomtom-th">Significance threshold</label>
   					<input id="tomtom-th" type="number" min="0" value="10" required>
   				</li>
   				<li>
   					<label for="tomtom-thtype">Threshold type</label>
   					<select id="tomtom-thtype" required>
   						<option value="evalue" selected>E-value</option>
   						<option value="qvalue">q-value</option>
   					</select>
   				</li>
   				<li>
   					<label for="tomtom-minovlp">Minimum overlap</label>
   					<input id="tomtom-minovlp" type="number" min="1" value="5" required>
   				</li>
   			</ol>
		</fieldset>
		<button id="run-tomtom">Run TOMTOM</button>
	</form>
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
