<!-- File <?php echo __FILE__; ?>  -->
	
	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

	<?php 	$name     = View::$params['name'];
			$expected = View::$params['expected']; ?>

	<div id="c">
		<p>Can not load the PHP file that implements the class "<code><?php echo $name; ?></code>".</p>
		<p>Please note that the expected path for this file is "<code><?php echo $expected; ?>.php</code>", relatively to one of the following directories:</p>

		<?php $pathes = Swing::getRepos(); ?>
		<ul>
			<?php foreach ($pathes as $path) { ?>
				<li><code><?php echo $path; ?></code></li>
			<?php } ?>
		</ul>
		
		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
  </div>

<!-- End of file <?php echo __FILE__; ?>  -->