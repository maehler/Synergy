<h2>Motif search results</h2>

<section>
<?php if ($is_gone): ?>
	<p>No results found...</p>
<?php elseif ($is_ready): ?>
	<p>Your results are ready! These results will be stored for 24 hours.</p>
	<ul>
		<li><a href="<?php echo $url; ?>/tomtom.html">TOMTOM (HTML)</a></li>
		<li><a href="<?php echo $url; ?>/tomtom.txt">TOMTOM (txt)</a></li>
	</ul>
<?php else: ?>
	<p>Your results are being calculated. You can bookmark this page and return
	later.</p>
	<p>This page will refresh automatically every 30th second.
	</p>
<?php endif ?>
</section>