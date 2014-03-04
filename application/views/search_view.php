<h2>Gene search</h2>

<div class="box narrow"><span id="select-count">0</span> genes selected</div>
<table id="gene-table" class="dataTable small">
	<thead>
		<tr>
			<th></th>
			<th>ORF</th>
			<th>Accession</th>
			<th>Symbol</th>
			<th>Category</th>
			<th>Definition</th>
			<th>Regulatory</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th>ORF</th>
			<th>Accession</th>
			<th>Symbol</th>
			<th>Category</th>
			<th>Definition</th>
			<th>Regulatory</th>
		</tr>
	</tfoot>
</table>

<div class="clear-fix small">
	<button id="load-example">Load example gene set</button>
	<button id="clear-selection">Clear selection</button>
	<button id="select-all">Select all</button>
</div>

<section>
	<h3>Upload gene list</h3>
	<?php echo form_open_multipart('search/upload', array('id' => 'file-upload', 'class' => 'narrow')); ?>
		<fieldset>
			<ol>
				<li>
					<label for="gene-file">Gene file</label>
					<input type="file" name="gene-file" id="gene-file">
				</li>
			</ol>
		</fieldset>
		<input type="submit" value="Upload">
	<?php echo form_close(); ?>
</section>

<section>
	<h3>Gene lists</h3>
	<p>Here are four categories of pre-computed gene lists. For each gene
	list, GO and motif enrichmen has been pre-calculated using the 
	<a href="<?php echo base_url('documentation#enrichment') ?>">default
	settings</a>.
	</p>
	<div id="list-wrapper" class="small tabs">
		<ul>
			<li><a href="#go-tab">GO</a></li>
			<li><a href="#motif-tab">Motifs</a></li>
			<li><a href="#coexp-tab">Co-expression</a></li>
			<li><a href="#tf-tab">Regulatory genes</a></li>
		</ul>

		<div id="go-tab">
			<p>Each of these gene lists consist of genes associated to a
			given GO term.
			</p>
			<p class="bold">Filter on FDR &lt; 0.05</p>
			<input class="list-filter" id="golist-gofilter"
            	type="checkbox" title="GO enrichment FDR &lt; 0.05"
            	checked />
            <label for="golist-gofilter" title="GO enrichment FDR &lt; 0.05">GO</label>
            
            <input class="list-filter" id="golist-motiffilter"
            	type="checkbox" title="Motif enrichment FDR &lt; 0.05"
            	checked />
            <label for="golist-motiffilter" title="Motif enrichment FDR &lt; 0.05">Motif</label>

			<table class="list-table dataTable" id="go-list-table">
				<thead>
					<tr>
						<th>GO</th>
						<th>Description</th>
						<th>Genes</th>
						<th>GO enr. FDR &lt; 0.05</th>
						<th>Min GO enr. FDR</th>
						<th>Motif enr. FDR &lt; 0.05</th>
						<th>Min motif enr. FDR</th>
						<th></th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th>GO</th>
						<th>Description</th>
						<th>Genes</th>
						<th>GO enr. FDR &lt; 0.05</th>
						<th>Min GO enr. FDR</th>
						<th>Motif enr. FDR &lt; 0.05</th>
						<th>Min motif enr. FDR</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>

		<div id="motif-tab">
			<p>Each of these gene lists consist of genes that have a given motif
			in their promoter regions.
			</p>

			<p class="bold">Filter on FDR &lt; 0.05</p>
			<input class="list-filter" id="motiflist-gofilter"
            	type="checkbox" title="GO enrichment FDR &lt; 0.05"
            	checked />
            <label for="motiflist-gofilter" title="GO enrichment FDR &lt; 0.05">GO</label>
            
            <input class="list-filter" id="motiflist-motiffilter"
            	type="checkbox" title="Motif enrichment FDR &lt; 0.05"
            	checked />
            <label for="motiflist-motiffilter" title="Motif enrichment FDR &lt; 0.05">Motif</label>

			<table class="list-table dataTable" id="motif-list-table">
				<thead>
					<tr>
						<th>Motif</th>
						<th>Genes</th>
						<th>GO enr. FDR &lt; 0.05</th>
						<th>Min GO enr. FDR</th>
						<th>Motif enr. FDR &lt; 0.05</th>
						<th>Min motif enr. FDR</th>
						<th></th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th>Motif</th>
						<th>Genes</th>
						<th>GO enr. FDR &lt; 0.05</th>
						<th>Min GO enr. FDR</th>
						<th>Motif enr. FDR &lt; 0.05</th>
						<th>Min motif enr. FDR</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>

		<div id="coexp-tab">
			<p>Each of these gene lists consist of genes associated to a given
			co-expression cluster. The network used for the clustering was the
			complete co-expression network at a CLR threshold of 4. An inflation
			value of 2.0 was used for MCL.
			</p>

			<p class="bold">Filter on FDR &lt; 0.05</p>
			<input class="list-filter" id="coexplist-gofilter"
            	type="checkbox" title="GO enrichment FDR &lt; 0.05"
            	checked />
            <label for="coexplist-gofilter" title="GO enrichment FDR &lt; 0.05">GO</label>
            
            <input class="list-filter" id="coexplist-motiffilter"
            	type="checkbox" title="Motif enrichment FDR &lt; 0.05"
            	checked />
            <label for="coexplist-motiffilter" title="Motif enrichment FDR &lt; 0.05">Motif</label>

			<table class="list-table dataTable" id="coexp-list-table">
				<thead>
					<tr>
						<th>Cluster</th>
						<th>Genes</th>
						<th>GO enr. FDR &lt; 0.05</th>
						<th>Min GO enr. FDR</th>
						<th>Motif enr. FDR &lt; 0.05</th>
						<th>Min motif enr. FDR</th>
						<th></th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th>Cluster</th>
						<th>Genes</th>
						<th>GO enr. FDR &lt; 0.05</th>
						<th>Min GO enr. FDR</th>
						<th>Motif enr. FDR &lt; 0.05</th>
						<th>Min motif enr. FDR</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>

		<div id="tf-tab">
			<p>Each of these gene lists consist of genes that are immediate 
			co-expression neighbors to a given regulatory gene. The network
			used for this study was the complete co-expression network at a
			CLR threshold of 4. The regulatory gene is included in each gene
			list, but it was <em>not</em> considered when performing the 
			enrichment analysis.
			</p>

			<p class="bold">Filter on FDR &lt; 0.05</p>
			<input class="list-filter" id="tflist-gofilter"
            	type="checkbox" title="GO enrichment FDR &lt; 0.05"
            	checked />
            <label for="tflist-gofilter" title="GO enrichment FDR &lt; 0.05">GO</label>
            
            <input class="list-filter" id="tflist-motiffilter"
            	type="checkbox" title="Motif enrichment FDR &lt; 0.05"
            	checked />
            <label for="tflist-motiffilter" title="Motif enrichment FDR &lt; 0.05">Motif</label>

			<table class="list-table dataTable" id="tf-list-table">
				<thead>
					<tr>
						<th>Gene</th>
						<th>Genes</th>
						<th>GO enr. FDR &lt; 0.05</th>
						<th>Min GO enr. FDR</th>
						<th>Motif enr. FDR &lt; 0.05</th>
						<th>Min motif enr. FDR</th>
						<th></th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th>Gene</th>
						<th>Genes</th>
						<th>GO enr. FDR &lt; 0.05</th>
						<th>Min GO enr. FDR</th>
						<th>Motif enr. FDR &lt; 0.05</th>
						<th>Min motif enr. FDR</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</section>

<script type="text/javascript">
	var selected = <?php echo json_encode($basket); ?>
</script>
