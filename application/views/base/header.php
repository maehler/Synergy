<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="utf-8">
	<title><?php echo !empty($title_prefix) ? "$title_prefix | " : ""; ?>Synergy</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(array("assets", "css", "normalize.css")) ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(array("assets", "css", "style.css")) ?>">
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,500' rel='stylesheet' type='text/css'>
	<?php if (isset($ssheets)): ?>
	<?php foreach ($ssheets as $ss): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $ss; ?>">
	<?php endforeach ?>
	<?php endif ?>
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
						<a id="nav-basket" class="<?php echo $current_pane == "basket" ? "active" : "" ?>" href="<?php echo base_url("basket") ?>">Gene basket</a>
					</li><li>
						<a id="nav-network" class="<?php echo $current_pane == "network" ? "active" : "" ?>" href="<?php echo base_url("network") ?>">Network</a>
					</li><li>
						<a id="nav-regtools" class="<?php echo $current_pane == "regtools" ? "active" : "" ?>" href="<?php echo base_url("regtools") ?>">Regulation tools</a>
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
				<?php echo form_open(base_url(array('home', 'accept_cookies', $this->uri->segment(1, '')))); ?>
				<p>This site uses <a href="<?php echo base_url('documentation#general-cookies'); ?>">cookies</a>
				to function properly. <input type="submit" name="submit" value="I accept cookies"></p>
				<?php echo form_close(); ?>
			</div>
		<?php endif ?>
