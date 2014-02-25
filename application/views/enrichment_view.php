<section>
	<h3>Enrichment tools</h3>
	<label for="enrichment-go-radio">GO enrichment</label>
	<input id="enrichment-go-radio" name="enrichment-radio" type="radio" value="go-pane" checked>
	<label for="enrichment-motif-radio">Motif enrichment</label>
	<input id="enrichment-motif-radio" name="enrichment-radio" type="radio" value="motif-pane">

	<section id="go-pane" class="small">
		<table>
			<tr>
				<td class="align right">
					<label for="go-p-th">FDR threshold</label>
				</td>
				<td>
					<select id="go-p-th">
						<option value="0.01">0.01</option>
						<option value="0.05" selected>0.05</option>
						<option value="0.1">0.1</option>
						<option value="0.5">0.5</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><button id="start-go-enrichment">Calculate</button></td>				
			</tr>
			<tr>
				<td class="align-right">
					<label for="go-category-filter">GO category filter</label>
				</td>
				<td>
					<select id="go-category-filter">
						<option value="Biological process" selected>Biological process</option>
						<option value="Molecular function">Molecular function</option>
						<option value="Cellular component">Cellular component</option>
					</select>
				</td>
			</tr>
		</table>

		<table id="go-table" class="dataTable">
			<thead>
				<tr>
					<th>GO term</th>
					<th>Category</th>
					<th>Description</th>
					<th>FDR</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr>
					<th>GO term</th>
					<th>Category</th>
					<th>Description</th>
					<th>FDR</th>
				</tr>
			</tfoot>
		</table>
	</section>

	<section id="motif-pane" class="hidden small">
		<table>
			<tr>
				<td class="align-right"><label for="motif-p-th">FDR threshold</label></td>
				<td>
					<select id="motif-p-th">
						<option value="0.01">&lt;0.01</option>
						<option value="0.05" selected>&lt;0.05</option>
						<option value="0.1">&lt;0.10</option>
						<option value="0.2">&lt;0.20</option>
						<option value="0.3">&lt;0.30</option>
						<option value="0.4">&lt;0.40</option>
						<option value="0.5">&lt;0.50</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="motif-q-th">FIMO q-value threshold</label>
				</td>
				<td>
					<select id="motif-q-th">
						<option value="0.01">&lt;0.01</option>
						<option value="0.05">&lt;0.05</option>
						<option value="0.10">&lt;0.10</option>
						<option value="0.15" selected>&lt;0.15</option>
						<option value="0.20">&lt;0.20</option>
						<option value="0.25">&lt;0.25</option>
						<option value="0.30">&lt;0.30</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="align-right">
					<label for="central-motifs">Central motifs</label>
				</td>
				<td>
					<input id="central-motifs" type="checkbox" checked>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button id="start-motif-enrichment">Calculate</button>
				</td>
			</tr>
		</table>

		<table id="motif-table" class="dataTable">
			<thead>
				<tr>
					<th>Motif</th>
					<th>Length</th>
					<th>Central</th>
					<th>FDR</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr>
					<th>Motif</th>
					<th>Length</th>
					<th>Central</th>
					<th>FDR</th>
				</tr>
			</tfoot>
		</table>
	</section>
</section>

<script type="text/javascript" src="<?php echo base_url(array('assets', 'js', 'enrichment.js')); ?>"></script>
