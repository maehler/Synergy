<h2>Documentation</h2>

<h3>Contents</h3>

<nav>
	<ul>
		<li>
			<a href="#general">General</a>
			<ul>
				<li><a href="#citing">Citing <i>Syn</i>ergy</a></li>
				<li><a href="#general-requirements">Requirements</a></li>
				<li><a href="#general-cookies">Cookies</a></li>
			</ul>
		</li>
		<li>
			<a href="#workflow">Typical workflows</a>
			<ul>
				<li><a href="#workflow-de">Differentially expressed genes</a></li>
				<li><a href="#workflow-motif">Regulatory motifs</a></li>
				<li><a href="#workflow-function">Functional category</a></li>
				<li><a href="#workflow-case-study">Case study 2 video</a></li>
			</ul>
		</li>
		<li>
			<a href="#search">Gene search</a>
			<ul>
				<li><a href="#search-upload">Upload a gene list</a></li>
				<li><a href="#search-genelists">Gene lists</a></li>
			</ul>
		</li>
		<li><a href="#motifsearch">Motif search</a></li>
		<li><a href="#basket">Basket</a></li>
		<li>
			<a href="#network">Network</a>
			<ul>
				<li><a href="#network-controls">Controls</a></li>
				<li><a href="#network-overview">Overview</a></li>
			</ul>
		</li>
		<li>
			<a href="#enrichment">Enrichment tools</a>
			<ul>
				<li><a href="#enrichment-go">GO enrichment</a></li>
				<li><a href="#enrichment-motif">Motif enrichment</a></li>
			</ul>
		</li>
		<li><a href="#expressionplot">Gene expression plot</a></li>
		<li><a href="#errors">Error reporting</a></li>
	</ul>
</nav>

<section id="general">
	<h3>General</h3>
	
	<section id="citing">
		<h4>Citing <i>Syn</i>ergy</h4>
		<p>If you use <i>Syn</i>ergy for publication, please cite:</p>
		<blockquote>
			Niklas Mähler, Otilia Cheregi, Christiane Funk, Sergiu Netotea and
			Torgeir R. Hvidsten (2014): <i>Syn</i>ergy: A web resource for
			exploring gene regulation in <i>Synechocystis</i> sp. PCC6803, Submitted
		</blockquote>
	</section>

	<section id="general-requirements">
		<h4>Requirements</h4>
		<p><span class="italic">Syn</span>ergy is highly dependent on JavaScript.
		Without it, you basically can't use the tool. Sorry about that. If you
		would like to use this tool, <a href="http://enable-javascript.com">
		please enable JavaScript</a>.
		</p>
		<p>Furthermore, some of the tools on this site uses features from 
		the HTML5 specification. This means that some older browsers won't
		display things as intended. <span class="italic">Syn</span>ergy uses
		the JavaScript library <a href="http://modernizr.com/">Modernizr</a> 
		to detect what features your browser supports. If support for an 
		essential feature cannot be detected, the site will inform you to 
		update your browser.
		</p>
		<figure class="right">
			<img src="<?php echo base_url(array('assets', 'img',
				'old_browser_warn.png')) ?>" alt="Incompatible browser warning">
			<figcaption>
				<p>Message shown if your browser is not supported.</p>
			</figcaption>
		</figure>
		<p>Recommended browsers are:</p>
		<ul>
			<li><a href="http://chrome.google.com">Google Chrome</a></li>
			<li><a href="http://firefox.com">Firefox</a></li>
			<li><a href="http://www.apple.com/safari">Safari</a></li>
			<li><a href="http://www.opera.com/">Opera</a></li>
			<li><a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">Internet Explorer 11+</a></li>
		</ul>
	</section>

	<section id="general-cookies">
		<h4>Cookies</h4>
		<span class="italic">Syn</span>ergy uses cookies to keep track of your
		selected genes and settings on the site. No personal information is 
		stored. The site will not work properly if cookies are not allowed.
		By using the site, you agree to allow cookies.
	</section>
</section>

<section id="workflow">
	<h3>Typical workflows</h3>
	<p><i>Syn</i>ergy is a flexible application, and there are multiple ways
	of starting an analysis. Here we will briefly explain a couple of common
	user scenarios.
	</p>

	<section id="workflow-de">
		<h4>Differentially expressed genes</h4>
		<figure class="right width25">
			<img src="<?php echo base_url(array('assets', 'img',
				'genelist_upload.png')) ?>" alt="Gene list upload">
			<figcaption>
				<p>Uploading a gene list on the gene search page.</p>
			</figcaption>
		</figure>
		<p>If you have a list of differentially expressed genes from some
		experimental setup, one easy way of getting started is simply to upload
		the genes using the <a href="#search-upload">Gene search</a> tool. All
		genes will then be added to the <a href="#basket">Gene basket</a> and
		you can continue from there. For example, you could look at the
		<a href="#network">Co-expression Network</a> of those genes, and also
		perform <a href="#enrichment">motif or GO enrichment</a> on that set of
		genes to see if the genes have any regulatory motif in common or if they
		belong to the same functional category.
		</p>
	</section>

	<section id="workflow-motif">
		<h4>Regulatory motifs</h4>
		<p>If you have a regulatory motif identified in <i>Synechocystis</i>
		(or any other species), you can search for similar motifs in
		<i>Synechocystis</i> using the <a href="#motifsearch">Motif search</a>
		tools. From these results, you will find motif matches, and for each
		motif you will find the genes to which these motifs are associated.
		</p>
		<figure class="right width25">
			<img src="<?php echo base_url(array('assets', 'img',
				'tomtom_results.png')) ?>" alt="TOMTOM example results">
			<figcaption>Example of results output from TOMTOM.</figcaption>
		</figure>
	</section>

	<section id="workflow-function">
		<h4>Functional category</h4>
		<p>If you are interested in a certain functional category of genes,
		or a Gene Ontology term, you can use this information to find genes in
		<i>Syn</i>ergy. From the <a href="#search-genelist">Gene lists</a> on 
		the <a href="#search">Gene search</a> page you can search for a Gene
		Ontology term to find the genes associated with it. Simply click the
		"Load" button next to the term you are interested in to add that gene
		set to the <a href="#basket">Gene basket</a>.
		</p>

		<p>In some cases, the functional categorization can be a bit blurry.
		For this, you can use the gene search table on the Gene search page.
		Just search for the term you are interested in, e.g. "photosystem", and
		all genes that are annotated as photosystem in any way will show up.
		To add the genes to the gene basket, simply click the checkbox for each
		gene. If you want to add all genes to the basket, click the "Select all"
		button.
		</p>

		<p>For an example on how an analysis based on functional categories,
		take a look at the <a href="#workflow-case-study">video for Case study 
		2</a> from our publication.
		</p>

		<figure class="">
			<img src="<?php echo base_url(array('assets', 'img',
				'gene_lists.png')) ?>" alt="Gene lists">
			<figcaption>Precompiled gene lists available on the gene search page.</figcaption>
		</figure>
	</section>

	<section id="workflow-case-study">
		<h4>Case study 2 video</h4>
		<p>Below is a video describing how to replicate the results from
		Case study 2 from our publication.
		</p>
		<iframe class="youtube" width="560" height="315" src="//www.youtube.com/embed/rAfmhwuWw-U" frameborder="0" allowfullscreen></iframe>
	</section>
</section>

<section id="search">
	<h3>Gene search</h3>
	<p>In the <a href="<?php echo base_url('search'); ?>">Gene search</a>, you can search
	for genes in <i>Synechocystis</i> that you are particularly interested in. In the
	first table, you can do free text searches for ORF names and annotations. Whenever
	something is entered in the search field, the table is filtered on the fly.
	To add genes to your <a href="#basket">gene basket</a>, just check the checkbox in
	the leftmost column. If you want to select all genes in the filtered table, just
	click the "Select all" button. Your gene basket is updated automatically whenever
	your selection changes.
	</p>

	<section id="search-upload">
		<h4>Upload a gene list</h4>
		<p>You can also upload a file with genes and load that into your gene basket.
		When the file is parsed, only the first word in each line is taken into account.
		For example, consider the following example:
		</p>
		<pre>gene1 gene2
gene3 gene4
gene5</pre>
		<p>In this case, only <code>gene1</code>, <code>gene3</code> and <code>gene5</code>
		will be searched for, the others will be ignored.
		</p>
		<p>By uploading genes, any genes previously in your gene basket will
		be removed and replaced by the uploaded list of genes.
		</p>
	</section>

	<section id="search-genelists">
		<h4>Gene lists</h4>
		<p>Another, more explorative, way of finding genes of interest is to take a 
		look at the precomputed gene lists that are available. Four different categories
		of gene lists are available; genes associated to Gene Ontology terms, genes
		associated to motifs, genes in co-expression network clusters and genes in the
		co-expression neighborhood of regulatory genes. By clicking the "Load"
		button in the last column, the genes will replace the genes currently in
		your basket.
		</p>
	</section>
</section>

<section id="motifsearch">
	<h3>Motif search</h3>
	<p>On the motif search page you can check whether your favorit motif is present
	in <i>Syn</i>ergy. You can perform the search using a IUPAC motif, a position
	specific probability matrix (PSPM), or the name of a <i>Syn</i>ergy motif.</p>

	<p>The IUPAC motifs can contain any of the <a 
	href="http://en.wikipedia.org/wiki/Nucleic_acid_notation#IUPAC_notation">
	IUPAC one-letter ambiguity codes for nucleic acids</a>. It also supports 
	square brackets for setting up simple regular expressions.
	</p>

	<p>The PSPMs should have 4 columns and <i>n</i> rows. Each column corresponds
	to each of the four nucleotides A, C, G, T, in that order. Each row corresponds
	to the position in the motif.
	</p>
</section>

<section id="basket">
	<h3>Basket</h3>
	In the gene basket all genes that you have previously selected are stored.
	You can manage your basket and export it as a tab delimited text file, which
	is compatible with the <a href="#search-upload">upload function</a> in the
	search page. Perhaps more interesting are the the <a href="#enrichment">enrichment
	tools</a> and the <a href="#expressionplot">gene expression plot</a>.
</section>

<section id="network">
	<h3>Network</h3>
	<p>On the network page you can explore co-expression patterns among your genes
		of interest. The network is visualized using nodes (genes) and edges
		(co-expression) usin the JavaScript library <a 
		href="http://cytoscape.github.io/cytoscape.js/">Cytoscape.js</a>. The 
		width of an edge is correlated with the co-expression of the gene pair.
	</p>

	<p>When visiting the network view, the application will find all co-expression
	links among the genes in your gene basket. The genes that are currently in
	your gene basket have a green background in the network view. To explore
	more interactions than you have in your basket, you can expand the network.
	Do this by right-clicking a node and select "Expand". This will search for 
	new nodes that are co-expressed with the current node using the "Expansion
	threshold" as a cutoff. The genes and edges that are found will be added to 
	the network, and they will initially be gray. This means they are <em>not</em>
	part of the gene basket yet. To add them to the basket, either right click
	each gene and select "Toggle basket" or select all of the genes and click 
	"Add to basket" in the menu.
	</p>

	<div class="block-center">
		<figure class="narrow">
			<img src="<?php echo base_url(array('assets', 'img', 'node_tf.png')); ?>"
				alt="Regulatory gene node">
			<figcaption class="center-caption">
				<p>Regulatory gene</p>
			</figcaption>
		</figure>
	
		<figure class="narrow">
			<img src="<?php echo base_url(array('assets', 'img', 'node_selected.png')); ?>"
				alt="Selected node">
			<figcaption class="center-caption">
				<p>Selected gene</p>
			</figcaption>
		</figure>
	
		<figure class="narrow">
			<img src="<?php echo base_url(array('assets', 'img', 'node_nobasket.png')); ?>"
				alt="Node not in gene basket">
			<figcaption class="center-caption">
				<p>Gene not in gene basket</p>
			</figcaption>
		</figure>
		<div class="clear-fix"></div>
	</div>

	<section id="network-controls" class="clear-fix">
		<h4>Controls</h4>		
		<p>Left-click nodes to select them. By holding the <kbd>shift</kbd> key, 
				multiple genes can be selected. By clicking and dragging on the
				background, multiple genes can be selected. A gene is selected
				if it has a red outline. By clicking and waiting for one second, 
				the network view can be panned.</p>
		<p>Right clicking a node brings up the node context menu.
		</p>
	</section>

	<section id="network-overview">
		<h4>Overview</h4>
		<figure class="wide">
			<img src="<?php echo base_url(array('assets', 'img', 'network.png')); ?>"
				alt="Network view explained">
		</figure>

		<ol>
			<li><b>Network type:</b> choose either the complete or the subset 
			co-expression networks.</li>
			<li><b>Correlation threshold: </b>Only draw edges with a co-expression
			above this threshold.</li>
			<li><b>Expansion threshold: </b>When expanding a neighborhood, only
			draw edges with a co-expression above this threshold.</li>
			<li><b>Layout expansion: </b>When expanding a neighborhood, redo the 
			layout of the network. If not, just put the expanded nodes in a circle
			around the original node.</li>
			<li><b>Node labels:</b> Choose what should be used as node labels.</li>
			<li><b>Redraw: </b>Redraw the network. Only nodes in your gene basket
			will be drawn.</li>
			<li>
				<b>Selection tools:</b>
				<ul>
					<li><b>Select all:</b> Select all genes in the network.</li>
					<li><b>Select none:</b> Deselect all genes in the network.</li>
					<li><b>Invert selection:</b> Select all deselected genes and
					vice versa.</li>
					<li><b>Grow selection:</b> Select the neighbors of the current
					selection.</li>
					<li><b>Delete selection:</b> Remove the genes from the network.
					<strong>Note:</strong> Does not remove the genes from the gene
					basket.</li>
				</ul>
			</li>
			<li>
				<b>Basket tools:</b>
				<ul>
					<li><b>Add to basket:</b> Add the gene(s) to your gene basket
					(make the node(s) green).</li>
					<li><b>Delete from basket:</b> Remove the gene(s) from your 
					gene basket (make the node(s) gray).</li>
				</ul>
			</li>
			<li>
				<b>Export tools</b>
				<ul>
					<li><b>Export GML:</b> Export the network in 
					<a href="http://en.wikipedia.org/wiki/Graph_Modelling_Language">
					Graph Modelling Language</a> (GML) format for import in other
					software.</li>
					<li><b>Export PNG:</b> Export an image of the network as PNG.</li>
					<li><b>Export PDF:</b> Export an image of the network as PDF.</li>
				</ul>
			</li>
			<li><b>Search:</b> Search for a gene in the network. Useful when you are
			interested in a certain gene in the network, but can't find it. If the
			gene exists, it is selected.</li>
			<li><b>Pan and zoom:</b> Pan and zoom the network. Not available on touch
			devices.</li>
			<li>
				<b>Node context menu:</b> Right click a node to show the menu.
				<ul>
					<li><b>Expand:</b> Search for new neighbors of the current gene
					using the threshold in 3.</li>
					<li><b>Delete:</b> Remove the gene from the network.
					<strong>Note:</strong> Does not remove the gene from the gene
					basket.</li>
					<li><b>Toggle basket:</b> If the gene is in your gene basket,
					remove it from the basket (make it gray) or vice versa (make
					it green).</li>
				</ul>
			</li>
		</ol>
	</section>
</section>

<section id="enrichment">
	<h3>Enrichment tools</h3>
	<p>The enrichment tools are available in the gene basket and in the network
		view. With the enrichment tools, GO and motif enrichment can be calculated.</p>
	
	<section id="enrichment-go">
		<h4>GO enrichment</h4>
		<p>GO enrichment requires just two parameters; a set of genes and an FDR
		threshold. The definition of the set of genes depends on the context.
		In the network view, select genes in the network by clicking and
		dragging. In the gene basket, check the checkboxes of the genes that
		you want to use.</p>
		<p>The "GO category filter" does not affect the calculation of the 
		results. This is just a filtering after the results have been 
		calculated.
		</p>
		<p>The "Stats" column in the resulting table shows the numbers behind
		the calculation on the form <code>a/b:c/d</code>. <code>a</code> is the
		number of genes in your selection that are annotated to the current term,
		<code>b</code> is the number of genes in your selection annotated to 
		the current top category (biological process, molecular function or 
		cellular process). <code>c</code> is the number of genes annotated
		to the current GO term, but are not part of your selection. Finally, 
		<code>d</code> is the number of genes <em>not</em> annotated to the
		current GO term and <em>not</em> part of your selection.
		</p>
		<p>The default settings used for GO enrichment are:
		</p>
		<ul>
			<li><b>FDR threshold:</b> &lt;0.05</li>
		</ul>
	</section>

	<section id="enrichment-motif">
		<h4>Motif enrichment</h4>
		<p>The motif enrichment takes three parameters; an FDR threshold, 
		a FIMO <i>q</i>-value threshold and whether or not to use central
		motifs. The FDR threshold is the false discovery rate of the 
		enrichment results, the FIMO <i>q</i>-value threshold is a threshold
		that decides how significant a motif must be to be considered in the
		enrichment calculation. By using central motifs, a subset of motifs is
		used for the enrichment. This subset was determined by creating a 
		motif network where each edge represented the degree of similarity 
		between a pair of motifs. The network was clustered, and the most
		central motif in each cluster was considered representative of that
		cluster, and thus classified as a central motif.
		</p>
		<p>Similarly to the GO enrichment table, the motif enrichment table 
		has a "Stats" column. In this column the numbers behind the calculation
		are shown on the form <code>a/b:c/d</code>. <code>a</code> is the number
		of genes in your selection that has the motif in their promoters (at
		the given threshold), <code>b</code> is the number of genes in your
		selection, <code>c</code> is the number of genes <em>not</em> in your
		selection with the motif and <code>d</code> is the number of genes that
		does <em>not</em> have the motif and is <em>not</em> a part of your
		selection.
		</p>
		<p>The default settings used for motif enrichment are:
		</p>
		<ul>
			<li><b>FDR threshold:</b> &lt;0.05</li>
			<li><b>FIMO <i>q</i>-value threshold:</b> &lt;0.15</li>
			<li><b>Central motifs:</b> yes</li>
		</ul>
	</section>
</section>

<section id="expressionplot">
	<h3>Gene expression plot</h3>	
	<p>In the network view, the gene basket and on the gene pages, an 
	expression plot can be generated. On the gene pages it gets generated
	automatically, but for the network view and the gene basket, it can 
	be generated from a selection of genes. By hovering with the cursor over
	the different series, more information on the experiments will be shown.
	You can also zoom in plots by clicking and dragging. To reset the zoom 
	level, just click the "Reset zoom" button. The plots can be exported as 
	PNG or PDF.
	</p>

	<p>Since it will be very difficult to see annotations and labels when 
	plotting many genes, labels and annotations will be disabled if you are
	plotting more than 30 genes. Because of memory limitations, the maximum 
	number of genes that can be plotted is limited to 300.
	</p>
</section>

<section id="errors">
	<h3>Error reporting</h3>
	<p>If you find something that doesn't work as it should, you can check if
	this is something we are already aware of, or report a new issue through
	our <a href="https://github.com/maehler/Synergy/issues"> issue tracker on
	Github</a>.
	</p>
</section>
