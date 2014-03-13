<h2>Motif search</h2>

<p>Here you can search for motifs in <i>Syn</i>ergy. Motifs in <i>Syn</i>ergy
similar to your motif will be identified using the software
<a href="http://meme.nbcr.net/meme/cgi-bin/tomtom.cgi">TOMTOM</a>. You can search using a IUPAC consensus
sequence or a matrix representation of the motif. If you already know what 
<i>Syn</i>ergy motif you are after, you can also search for that using the motif
name.
</p>

<section>
	<?php echo form_open('motifsearch'); ?>
	<fieldset>
		<legend>Search type</legend>
		<input id="search-iupac-radio" type="radio" name="motif-search-radio" 
			value="search-iupac-pane"<?php echo set_radio('motif-search-radio', 'search-iupac-pane', TRUE); ?>>
		<label for="search-iupac-radio">IUPAC</label>

		<input id="search-matrix-radio" type="radio" name="motif-search-radio" 
			value="search-matrix-pane"<?php echo set_radio('motif-search-radio', 'search-matrix-pane'); ?>>
		<label for="search-matrix-radio">Matrix</label>

		<input id="search-id-radio" type="radio" name="motif-search-radio" 
			value="search-id-pane"<?php echo set_radio('motif-search-radio', 'search-id-pane'); ?>>
		<label for="search-id-radio">Motif name</label>
	</fieldset>

	<fieldset id="search-iupac-pane" class="<?php echo $pane === 'iupac' ? '' : 'hidden' ?>">
		<legend>IUPAC motif</legend>
		<p>Search for a motif using the IUPAC one letter abbreviation standard.</p>
		<ol>
			<li>
				<button id="load-iupac-example">Load example</button>
			</li>
			<li>
				<input id="motif-iupac" name="motif-iupac" type="text" placeholder="IUPAC motif" value="<?php echo set_value('motif-iupac'); ?>">
			</li>
		</ol>
		<?php if ($pane === 'iupac'): ?>
			<?php echo validation_errors(); ?>
		<?php endif ?>
	</fieldset>

	<fieldset id="search-matrix-pane" class="<?php echo $pane === 'matrix' ? '' : 'hidden' ?>">
		<legend>Matrix motif</legend>
		<p>Search for a motif using a position specific probability matrix, like 
		the ones that can be found on the <a 
		href="<?php echo base_url(array('motif', 'details', 'NP_441106.1_3')); ?>">
		motif details page</a>.
		</p>

		<ol>
			<li>
				<button id="load-matrix-example">Load example</button>
			</li>
			<li>
				<textarea class="monospace" id="motif-matrix" name="motif-matrix" rows="10" placeholder="Matrix"><?php echo set_value('motif-matrix'); ?></textarea>
			</li>
		</ol>
		<?php if ($pane === 'matrix'): ?>
			<?php echo validation_errors(); ?>
		<?php endif ?>
	</fieldset>

	<fieldset id="search-id-pane" class="<?php echo $pane === 'id' ? '' : 'hidden' ?>">
		<legend>Motif name</legend>
		<p>Search for a motif using a <i>Syn</i>ergy motif name, e.g. 
		NP_441106.1_3.</p>

		<input name="motif-name" type="text" placeholder="Motif name" value="<?php echo set_value('motif-name'); ?>">
		<?php if ($pane === 'id'): ?>
			<?php echo validation_errors(); ?>
		<?php endif ?>
	</fieldset>
	<fieldset id="search-options"<?php echo $pane === 'id' ? ' class="hidden"' : ''; ?>>
		<legend>Options</legend>
		<ol>
			<li>
				<label for="sign-thresh">Significance threshold</label>
				<input type="number" name="sign-thresh" id="sign-thresh" value="<?php echo set_value('sign-thresh', '0.05'); ?>" step="0.01" min="0">
			</li>
			<li>
				<label for="thresh-type">Treshold type</label>
				<select id="thresh-type" name="thresh-type">
					<option value="qvalue" <?php echo set_select('thresh-type', 'qvalue', TRUE); ?>>q-value</option>
					<option value="evalue" <?php echo set_select('thresh-type', 'evalue'); ?>>E-value</option>
				</select>
			</li>	
			<li>
				<label for="central-motifs">Only central motifs</label>
				<input name="central-motifs" id="central-motifs" type="checkbox" value="central-motifs" <?php echo set_checkbox('central-motifs', 'central-motifs', TRUE) ?>>
			</li>
			<li>
				<label for="min-overlap">Minimum overlap</label>
				<input id="min-overlap" name="min-overlap" type="number" min="1" step="1" value="<?php echo set_value('min-overlap', '1') ?>">
			</li>
		</ol>		
	</fieldset>
	<input type="reset" value="Reset">
	<input name="submit" type="submit" value="Search">
	<?php echo form_close(); ?>
</section>
