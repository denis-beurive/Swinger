<!-- File <?php echo __FILE__; ?>  -->

 	<div class="page">
		<h2>Oops... Wrong note!</h2>
 	</div>

	<?php $name = View::$params['layout']; ?>

 	<div id="c">
		<p>Can not load the layout file "<?php echo $name; ?>".</p>
		
		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
 	</div>

<!-- End of File <?php echo __FILE__; ?>  -->