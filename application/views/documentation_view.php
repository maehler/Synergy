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
		<li><a href="#network">Network</a></li>
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
	for genes in <i>Synechocystis</i> that you are particulary interested in. In the
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
		look at the precompiled gene lists that are available. Four different categories
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
</section>

<section id="network">
	<h3>Network</h3>
</section>

<section id="regtools">
	<h3>Regulation tools</h3>
</section>

