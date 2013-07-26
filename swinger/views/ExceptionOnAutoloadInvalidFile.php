<!-- File <?php echo __FILE__; ?>  -->
	
	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

	<?php $path = View::$params['path']; ?>

	<div id="c">
		<p>The content of the file "<code><?php echo $path; ?></code>" is not valid.</p>
		<p>The file may be corrupted.</p>
		<p>To fix this problem, just remove the file.</p>

		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
  </div>

<!-- End of file <?php echo __FILE__; ?>  -->