		</main>
		<div class="push"></div>
	</div> <!-- end wrapper -->
	<footer class="page-footer align-center">
		<p>&copy; <a href="mailto:niklas.mahler@nmbu.no?subject=Synergy">Niklas Mähler</a> 2012-2013</p>
	</footer>

	<noscript>
        <section id="noscript-warn"><em>Syn</em>ergy works best with
        JavaScript enabled</section>
    </noscript>
    <script type="text/javascript" src="<?php echo base_url(array('assets', 'js', 'jquery-1.10.2.min.js')); ?>"></script>
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
