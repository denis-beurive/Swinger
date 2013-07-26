<!-- File <?php echo __FILE__; ?>  -->
	
	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

	<?php $path = View::$params['path']; ?>

	<div id="c">
		<p>Can not load the classes' index file "<code><?php echo $path; ?></code>"</p>
		
		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
  	</div>

<!-- End of file <?php echo __FILE__; ?>  -->