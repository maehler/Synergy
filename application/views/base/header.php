<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="utf-8">
	<?php if (isset($auto_refresh_time)): ?>
	<meta http-equiv="refresh" content="<?php echo $auto_refresh_time; ?>">		
	<?php endif ?>
	<link rel="shortcut icon" href="<?php echo base_url(array('assets', 'favicon.ico')) ?>" type="image/x-icon">
	<title><?php echo !empty($title_prefix) ? "$title_prefix | " : ""; ?>Synergy</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(array("assets", "css", "normalize.css")) ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(array("assets", "css", "style.css")) ?>">
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,500' rel='stylesheet' type='text/css'>
	<?php if (isset($ssheets)): ?>
	<?php foreach ($ssheets as $ss): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $ss; ?>">
	<?php endforeach ?>
	<?php endif ?>
	<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-48169113-1', 'plantgenie.org');
      ga('send', 'pageview');

    </script>
</head>
<body>
	<?php if (ENVIRONMENT == 'development'): ?>
		<?php $this->output->enable_profiler(TRUE); ?>
	<?php endif ?>
	<noscript>
		<div id="noscript-padding"></div>
		<div id="noscript-warn"><em>Syn</em>ergy works best with JavaScript enabled. <a href="<?php echo base_url('documentation#general-requirements') ?>">More information</a>.</div>
	</noscript>
	<div id="old-browser-padding"></div>
    <div id="old-browser-warn">Your browser is old and dusty. Try <a href="http://chrome.google.com">Google Chrome</a>!</div>
	<div class="wrapper">
		<header class="page-header">
			<h1><span class="italic">Syn</span>ergy</h1>
			<nav>
				<ul>
					<li>
						<a id="nav-home" class="<?php echo $current_pane == "home" ? "active" : "" ?>" href="<?php echo base_url() ?>">Home</a>
					</li><li>
						<a id="nav-search" class="<?php echo $current_pane == "search" ? "active" : "" ?>" href="<?php echo base_url("search") ?>">Gene search</a>						
					</li><li>
						<a id="nav-motifsearch" class="<?php echo $current_pane == "motifsearch" ? "active" : "" ?>" href="<?php echo base_url("motifsearch") ?>">Motif search</a>
					</li><li>
						<a id="nav-basket" class="<?php echo $current_pane == "basket" ? "active" : "" ?>" href="<?php echo base_url("basket") ?>">Gene basket</a>
					</li><li>
						<a id="nav-network" class="<?php echo $current_pane == "network" ? "active" : "" ?>" href="<?php echo base_url("network") ?>">Network</a>
					</li><li>
						<a id="nav-documentation" class="<?php echo $current_pane == "documentation" ? "active" : "" ?>" href="<?php echo base_url("documentation") ?>">Documentation</a>
					</li>
				</ul>
			</nav>
		</header>
		<main>
		<?php if (isset($error_message)): ?>
			<div class="error-message">
				<?php echo $error_message; ?>
			</div>
		<?php endif ?>
		<?php if ($cookie_disclaimer): ?>
			<div class="cookie">
				<?php echo form_open(base_url(array('home', 'accept_cookies?redir='.current_url()))); ?>
				<p>This site uses <a href="<?php echo base_url('documentation#general-cookies'); ?>">cookies</a>
				to function properly. <input type="submit" name="submit" value="I accept cookies"></p>
				<?php echo form_close(); ?>
			</div>
		<?php endif ?>
