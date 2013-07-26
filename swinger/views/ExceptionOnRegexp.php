<!-- File <?php echo __FILE__; ?>  -->
	
	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

	<?php	$exp     = View::$params['expression'];
			$errstr  = View::$params['errstr'];
			$errfile = View::$params['errfile'];
			$errline = View::$params['errline']; ?>

	<div id="c">
		<p>It looks like you used a bad regular expression. Look out for this:</p>
		<pre>new Regexp('<b><?php echo $exp; ?></b>')</pre>
		<p>Message: <font color="#FF0000"><?php echo $errstr; ?></font></p>

		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>	
 	</div>

<!-- End of file <?php echo __FILE__; ?>  -->