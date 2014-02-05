		</main>
		<div class="push"></div>
	</div> <!-- end wrapper -->
	<footer class="page-footer align-center">
		<p>&copy; <a href="mailto:niklas.mahler@nmbu.no?subject=Synergy">Niklas Mähler</a> 2012-2014</p>
	</footer>

    <section id="noscript-warn"><em>Syn</em>ergy works best with JavaScript enabled. <a href="<?php echo base_url('documentation#general-requirements') ?>">More information</a>.</section>
    <section id="old-browser-warn">Your browser is old and dusty. Try <a href="http://chrome.google.com">Google Chrome</a>!</section>
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
