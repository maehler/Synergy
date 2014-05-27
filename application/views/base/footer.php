		</main>
		<div class="push"></div>
	</div> <!-- end wrapper -->
	<footer class="page-footer align-center">
		<p>&copy; 2012-<?php echo date("Y"); ?> | <a href="https://github.com/maehler/Synergy/issues">Report an issue</a></p>
	</footer>

    <script type="text/javascript" src="<?php echo base_url(array('assets', 'js', 'jquery-1.11.0.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(array('assets', 'js', 'modernizr.custom.js')); ?>"></script>
    <?php if (isset($scripts) && $scripts !== NULL): ?>
    <?php foreach ($scripts as $js): ?>
    <script type="text/javascript" src="<?php echo $js ?>"></script> 
    <?php endforeach ?>
    <?php endif ?>
    <?php if (isset($inlinejs) && !empty($inlinejs)): ?>
    <script type="text/javascript">
    <?php echo $inlinejs ?>
    </script>
    <?php endif ?>
</body>
</html>
