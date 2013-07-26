<!-- File <?php echo __FILE__; ?>  -->
	
	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

	<?php 	$file   = View::$params['file'];
			$pathes = View::$params['pathes']; ?>

	<div id="c">
		<p>Invalid name for the file "<code><?php echo $file; ?></code>".</p>
		<p>Files in the auto load classes' repositories should follow some naming rules.</p>
		<p>Paths to the auto load classes' repositories:</p>
		
		<ul>
			<?php foreach ($pathes as $path) { ?>
				<li><code><?php echo $path; ?></code></li>
			<?php } ?>
		</ul>
		
		<p><b>For example:</b></p>
		<p>You want to put the class "Vehicle" in the directory "<code><?php echo $pathes[0]; ?></code>".</p>
		<p>The name of the file is "<code>Vehicle.php</code>". The path to the file is "<code><?php echo $pathes[0] . DIRECTORY_SEPARATOR . 'Vehicle.php'; ?></code>".
		<pre>
		// File "<?php echo '<b>' . $pathes[0] . '</b>' . DIRECTORY_SEPARATOR . 'Vehicle.php'; ?>"
		class Vehicle
		{
			// ...
		}
		
		// ...
		$v = new Vehicle(...);
		</pre>

		<p><b>For example:</b></p>
		<p>You want to put the class "Plane" in the directory "<code><?php echo $pathes[0] . DIRECTORY_SEPARATOR . '<font color="#FF0000">Vehicle</font>'; ?></code>".</p>
		<p>The name of the class must be "<code><font color="#FF0000">Vehicle</font>_Plane</code>".</p>
		<p>The name of the file is "<code><font color="#FF0000">Vehicle</font>_Plane.php</code>". The path to the file is "<code><?php echo $pathes[0] . DIRECTORY_SEPARATOR . 'Vehicle' . DIRECTORY_SEPARATOR . '<font color="#FF0000">Vehicle</font>_Plane.php'; ?></code>".
		<pre>
		// File "<?php echo '<b>' . $pathes[0] . '</b>' . DIRECTORY_SEPARATOR . 'Vehicle' . DIRECTORY_SEPARATOR . 'Vehicle_Plane.php'; ?>"
		class Vehicle_Place
		{
			// ...
		}
		
		// ...
		$p = new Vehicle_Place(...);
		</pre>

		<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
 	</div>

<!-- End of file <?php echo __FILE__; ?>  -->