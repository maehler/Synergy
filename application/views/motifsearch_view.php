<h2>Motif search</h2>

<section>
	<?php echo form_open('motifsearch'); ?>
	<input id="search-iupac-radio" type="radio" name="motif-search-radio" 
		value="search-iupac-pane"<?php echo $pane === 'iupac' ? ' checked' : ''; ?>>
	<label for="search-iupac-radio">IUPAC</label>

	<input id="search-matrix-radio" type="radio" name="motif-search-radio" 
		value="search-matrix-pane"<?php echo $pane === 'matrix' ? ' checked' : ''; ?>>
	<label for="search-matrix-radio">Matrix</label>

	<input id="search-id-radio" type="radio" name="motif-search-radio" 
		value="search-id-pane"<?php echo $pane === 'id' ? ' checked' : ''; ?>>
	<label for="search-id-radio">Motif ID</label>

	<div id="search-iupac-pane" class="<?php echo $pane === 'iupac' ? '' : 'hidden' ?>">
		<p>Search for a motif using the IUPAC one letter abbreviation standard.</p>

		<input name="motif-iupac" type="text" placeholder="IUPAC motif"><br>
		<input name="central-motifs" id="central-motifs" type="checkbox" checked>
		<label for="central-motifs">Search only central motifs</label>
		<?php if ($pane === 'iupac'): ?>
			<?php echo validation_errors(); ?>
		<?php endif ?>
	</div>

	<div id="search-matrix-pane" class="<?php echo $pane === 'matrix' ? '' : 'hidden' ?>">
		<p>Search for a motif using a position specific probability matrix, like 
		the ones that can be found on the <a 
		href="<?php echo base_url(array('motif', 'details', 'NP_441106.1_3')); ?>">
		motif details page</a>.
		</p>

		<textarea name="motif-matrix" rows="10" placeholder="Matrix"></textarea>
		<?php if ($pane === 'matrix'): ?>
			<?php echo validation_errors(); ?>
		<?php endif ?>
	</div>

	<div id="search-id-pane" class="<?php echo $pane === 'id' ? '' : 'hidden' ?>">
		<p>Search for a motif using a <i>Syn</i>ergy motif name, e.g. 
		NP_441106.1_3.</p>

		<input name="motif-name" type="text" placeholder="Motif name">
		<?php if ($pane === 'id'): ?>
			<?php echo validation_errors(); ?>
		<?php endif ?>
	</div>
	<input name="submit" type="submit" value="Search">
	<?php echo form_close(); ?>
</section>
