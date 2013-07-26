<!DOCTYPE html>
<html>
<head>

	<link rel="stylesheet" href="/swinger/swinger.css" type="text/css" />

</head>
<body>
	
	<div class="page">
		<p>This is the default layout.</p>
		<p>Path (to the layout file): <code><?php echo __FILE__; ?></code></p>
		<p>Please edit this file.</p>
		<p><i>In the layout file</i>, in order to render a view:</p>
		<code>&lt;?php echo Layout::$view_content; ?&gt;</code>
	</div>
	<br/><br/><br/>

	<!-- The only important line -->
	<?php echo Layout::$view_content; ?>

</body>