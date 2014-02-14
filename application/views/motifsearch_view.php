<h2>Motif search</h2>

<section>
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

		<?php echo form_open('motifsearch/iupac_search'); ?>
			<input name="motif-iupac" type="text" placeholder="IUPAC motif">
			<input name="submit" type="submit" value="Search">
		<?php echo form_close(); ?>
	</div>

	<div id="search-matrix-pane" class="<?php echo $pane === 'matrix' ? '' : 'hidden' ?>">
		<p>Search for a motif using a position specific probability matrix.</p>

		<?php echo form_open('motifsearch/matrix_search'); ?>
			<input name="motif-matrix" type="textarea" placeholder="Matrix">
			<input name="submit" type="submit" value="Search">
		<?php echo form_close(); ?>
	</div>

	<div id="search-id-pane" class="<?php echo $pane === 'id' ? '' : 'hidden' ?>">
		<p>Search for a motif using a <i>Syn</i>ergy motif name, e.g. 
		NP_441106.1_3.</p>

		<?php echo form_open('Motifsearch/name_search'); ?>
			<input name="motif-name" type="text" placeholder="Motif name">
			<input name="submit" type="submit" value="Search">
		<?php echo form_close(); ?>
	</div>
</section>
