<section>
	<h3>Expression profile</h3>
	<button id="draw-expression" class="small">Draw expression profile</button>
	<div id="loading-plot" class="hidden load-message">
		<span class="loading"></span>Loading plot...
	</div>
	<div class="flot-overview"></div>
	<div class="flot-buttons small">
		<button id="plot-reset">Reset zoom</button>
	</div>
	<div class="flot-legend"></div>
	<div id="flot-expression" class="flot with-legend"></div>
</section>

<script type="text/javascript" src="<?php echo base_url(array('assets', 'js', 'expressionplot.js')); ?>"></script>
