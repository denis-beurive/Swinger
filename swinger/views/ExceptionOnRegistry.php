<!-- File <?php echo __FILE__; ?>  -->
	
 	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

	<?php $name = View::$params['name']; ?>

	<div id="c">
		<p>An attempt has been made to retrieve the value "<?php echo $name; ?>" from the registry. Unfortunately this value is not recorded in the registry.</p>
		<pre><code>Registry::get('<?php echo $name; ?>');</code></pre>
		<p>If you need to test if a value is recorded in the registry, you should use <code>Registry::exists('<i>name_of_the_value</i>').</code></p>
			
		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
	</div>

<!-- End of file <?php echo __FILE__; ?>  -->