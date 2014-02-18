		</main>
		<div class="push"></div>
	</div> <!-- end wrapper -->
	<footer class="page-footer align-center">
		<p>&copy; <a href="mailto:niklas.mahler@nmbu.no?subject=Synergy">Niklas Mähler</a> 2012-2014</p>
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
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-48169113-1', 'plantgenie.org');
      ga('send', 'pageview');

    </script>
</body>
</html>
