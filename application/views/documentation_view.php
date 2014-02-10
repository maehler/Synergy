<h2>Documentation</h2>

<nav>
	<ul>
		<li>
			<a href="#general">General</a>
			<ul>
				<li><a href="#general-requirements">Requirements</a></li>
				<li><a href="#general-cookies">Cookies</a></li>
			</ul>
		</li>
		<li>
			<a href="#search">Search</a>
			<ul>
				<li><a href="#search-upload">Upload a gene list</a></li>
				<li><a href="#search-genelists">Gene lists</a></li>
			</ul>
		</li>
		<li><a href="#basket">Basket</a></li>
		<li>
			<a href="#network">Network</a>
			<ul>
				<li><a href="#network-controls">Controls</a></li>
				<li><a href="#network-overview">Overview</a></li>
			</ul>
		</li>
		<li><a href="#regtools">Regulation tools</a></li>
	</ul>
</nav>

<section id="general">
	<h3>General</h3>
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

<section id="search">
	<h3>Search</h3>
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
		(co-expression). The width of an edge is correlated with the co-expression
		of the gene pair.</p>

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

<section id="regtools">
	<h3>Regulation tools</h3>
</section>

