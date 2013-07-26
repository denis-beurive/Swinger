<!-- File <?php echo __FILE__; ?>  -->
	
	<div class="page">
		<h2>Oops... Wrong note!</h2>
 	</div>

 	<div id="c">
	
		<p>Exception thrown from file "<code><?php echo View::$params->getFile(); ?></code>",
		on line <?php echo View::$params->getLine(); ?> (code <?php echo View::$params->getCode(); ?>).</p>
	
		<br/><br/><br/>
	
		<table>
		
		<!-- --------------------------------- -->
	
			<tr class="yellow top"><td colspan="2">Exception</td></tr>
			<tr>
				<td>Message</td>
				<td><?php echo View::$params->getMessage(); ?></td>
			</tr>
			
			<?php $n = 1; ?>
			<?php foreach (View::$params->getTrace() as $trace) { ?> 
				
				<?php
					if (1 == $n)
					{ $file = View::$params->getFile(); $line = View::$params->getLine(); }
					else
					{ $file = $trace['file']; $line = $trace['line']; }
				?>
				
				<tr>
				<td>Trace <?php echo "#$n"; $n++; ?></td>
				<td>
					File: <?php echo $file; ?><br/>
					Line: <?php echo $line; ?><br/>
					Function: <?php echo $trace['function']; ?><br/>
				</td>
				</tr>
			<?php } ?>
	
		<!-- --------------------------------- -->
	
			<?php include Env::get('public_dir') . DIRECTORY_SEPARATOR . 'swinger' . DIRECTORY_SEPARATOR . 'information.php'; ?>
	
		</table>	
	
	</div>

<!-- End of file <?php echo __FILE__; ?>  -->