<!-- File <?php echo __FILE__; ?>  -->
	
	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

	<?php $path   = View::$params['path'];
	      $errstr = View::$params['errstr']; ?>

	<div id="c">
		<p>Can not create the classes' index file "<code><?php echo $path; ?></code>"</p>
		<p>Message: <font color="#FF0000"><?php echo $errstr; ?></font></p>
		<p>WARNING! Make sure that the following directory and file are accessible by the Apache user (check the permissions).</p>
		<ul>
			<li>Directory: <code><?php echo Env::get('data_dir');?></code></li>
			<li>File: <code><?php echo Env::get('class_register_path');?></code></li>
		</ul>
		
		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
  </div>

<!-- End of file <?php echo __FILE__; ?>  -->