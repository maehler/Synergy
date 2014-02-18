<h2>Motif search</h2>

<section>
	<?php echo form_open('motifsearch'); ?>
	<input id="search-iupac-radio" type="radio" name="motif-search-radio" 
		value="search-iupac-pane"<?php echo set_radio('motif-search-radio', 'search-iupac-pane', TRUE); ?>>
	<label for="search-iupac-radio">IUPAC</label>

	<input id="search-matrix-radio" type="radio" name="motif-search-radio" 
		value="search-matrix-pane"<?php echo set_radio('motif-search-radio', 'search-matrix-pane'); ?>>
	<label for="search-matrix-radio">Matrix</label>

	<input id="search-id-radio" type="radio" name="motif-search-radio" 
		value="search-id-pane"<?php echo set_radio('motif-search-radio', 'search-id-pane'); ?>>
	<label for="search-id-radio">Motif ID</label>

	<div id="search-iupac-pane" class="<?php echo $pane === 'iupac' ? '' : 'hidden' ?>">
		<p>Search for a motif using the IUPAC one letter abbreviation standard.</p>

		<input name="motif-iupac" type="text" placeholder="IUPAC motif" value="<?php echo set_value('motif-iupac'); ?>"><br>
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

		<textarea name="motif-matrix" rows="10" placeholder="Matrix"><?php echo set_value('motif-matrix'); ?></textarea><br>
		<?php if ($pane === 'matrix'): ?>
			<?php echo validation_errors(); ?>
		<?php endif ?>
	</div>

	<div id="search-id-pane" class="<?php echo $pane === 'id' ? '' : 'hidden' ?>">
		<p>Search for a motif using a <i>Syn</i>ergy motif name, e.g. 
		NP_441106.1_3.</p>

		<input name="motif-name" type="text" placeholder="Motif name" value="<?php echo set_value('motif-name'); ?>">
		<?php if ($pane === 'id'): ?>
			<?php echo validation_errors(); ?>
		<?php endif ?>
	</div>
	<div id="search-options"<?php echo $pane === 'id' ? ' class="hidden"' : ''; ?>>
		<h4>Options</h4>
		<table>
			<tr>
				<td><label for="sign-thresh">Significance threshold</label></td>
				<td><input type="number" name="sign-thresh" id="sign-thresh" value="<?php echo set_value('sign-thresh', '0.05'); ?>" step="0.01" min="0"></td>
			</tr>
			<tr>
				<td><label for="thresh-type">Treshold type</label></td>
				<td>
					<select id="thresh-type" name="thresh-type">
						<option value="qvalue" <?php echo set_select('thresh-type', 'qvalue', TRUE); ?>>q-value</option>
						<option value="evalue" <?php echo set_select('thresh-type', 'evalue'); ?>>E-value</option>
					</select>
				</td>
			</tr>	
			<tr>
				<td><label for="central-motifs">Only central motifs</label></td>
				<td><input name="central-motifs" id="central-motifs" type="checkbox" value="central-motifs" <?php echo set_checkbox('central-motifs', 'central-motifs', TRUE) ?>></td>
			</tr>
			<tr>
				<td><label for="min-overlap">Minimum overlap</label></td>
				<td><input id="min-overlap" name="min-overlap" type="number" min="1" step="1" value="<?php echo set_value('min-overlap', '1') ?>"></td>
			</tr>
		</table>		
	</div>
	<input type="reset" value="Reset">
	<input name="submit" type="submit" value="Search">
	<?php echo form_close(); ?>
</section>
