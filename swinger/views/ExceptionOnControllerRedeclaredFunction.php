<!-- File <?php echo __FILE__; ?>  -->
	
	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

	<?php $name = View::$params['controller']; ?>

	<div id="c">
		<p>It looks like you redeclared the controller "<?php echo $name; ?>". Look out for this:</p>
<pre>
Controller::register(..., ..., '<b><?php echo $name; ?></b>');
...
Controller::register(..., ..., '<b><?php echo $name; ?></b>');	
</pre>

		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
		
	</div>

<!-- End of file <?php echo __FILE__; ?>  -->