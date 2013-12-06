<!doctype html>
<html lang="en">
<head>
	<meta charset="utf8">
	<title><?php echo !empty($title_prefix) ? "$title_prefix | " : ""; ?>Synergy</title>
</head>
<body>
	<noscript>
		<div id="noscript-padding"></div>
	</noscript>
	<div id="wrapper">
		<header id="pagehead">
			<h1>Synergy</h1>
			<nav>
				<ul>
					<li>
						<a id="home" class="<?php echo $current_pane == "home" ? "active" : "" ?>" href="<?php echo base_url() ?>">Home</a>
						<a id="search" class="<?php echo $current_pane == "search" ? "active" : "" ?>" href="<?php echo base_url("search") ?>">Gene search</a>
						<a id="basket" class="<?php echo $current_pane == "basket" ? "active" : "" ?>" href="<?php echo base_url("basket") ?>">Gene basket</a>
						<a id="network" class="<?php echo $current_pane == "network" ? "active" : "" ?>" href="<?php echo base_url("network") ?>">Network</a>
						<a id="regtools" class="<?php echo $current_pane == "regtools" ? "active" : "" ?>" href="<?php echo base_url("regtools") ?>">Regulation tools</a>
						<a id="documentation" class="<?php echo $current_pane == "documentation" ? "active" : "" ?>" href="<?php echo base_url("documentation") ?>">Documentation</a>
					</li>
				</ul>
			</nav>
		</header>
	</div>
