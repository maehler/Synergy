<!doctype html>
<html lang="en">
<head>
	<meta charset="utf8">
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
	</noscript>
	<div class="wrapper">
		<header class="page-header">
			<h1><span class="italic">Syn</span>ergy</h1>
			<nav>
				<ul>
					<li>
						<a id="home" class="<?php echo $current_pane == "home" ? "active" : "" ?>" href="<?php echo base_url() ?>">Home</a>
					</li><li>
						<a id="search" class="<?php echo $current_pane == "search" ? "active" : "" ?>" href="<?php echo base_url("search") ?>">Gene search</a>						
					</li><li>
						<a id="basket" class="<?php echo $current_pane == "basket" ? "active" : "" ?>" href="<?php echo base_url("basket") ?>">Gene basket</a>
					</li><li>
						<a id="network" class="<?php echo $current_pane == "network" ? "active" : "" ?>" href="<?php echo base_url("network") ?>">Network</a>
					</li><li>
						<a id="regtools" class="<?php echo $current_pane == "regtools" ? "active" : "" ?>" href="<?php echo base_url("regtools") ?>">Regulation tools</a>
					</li><li>
						<a id="documentation" class="<?php echo $current_pane == "documentation" ? "active" : "" ?>" href="<?php echo base_url("documentation") ?>">Documentation</a>
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
