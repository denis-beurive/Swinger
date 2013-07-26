<!-- --------------------------------- -->

	<?php if (Error::$isset) { ?>
		<tr class="yellow top"><td colspan="2">PHP</td></tr>	
		<tr>
			<td>Massage</td>
			<td><?php echo Error::$errstr; ?></td>
		</tr>		
		<tr>
			<td>Errno</td>
			<td><?php echo Error::$errno; ?></td>
		</tr>		
		<tr>
			<td>File</td>
			<td><?php echo Error::$errfile; ?></td>
		</tr>		
		<tr>
			<td>Line</td>
			<td><?php echo Error::$errline; ?></td>
		</tr>		
	<?php } ?>

<!-- --------------------------------- -->

	<tr class="yellow top"><td colspan="2">HTTP</td></tr>
	<?php foreach ($_SERVER as $name => $value) { ?>
		<tr>
			<td><?php echo $name; ?></td>
			<td><?php echo $value; ?></td>
		</tr>
	<?php } ?>	

<!-- --------------------------------- -->

	<?php if (count($_ENV) > 0) { ?>
		<tr class="yellow"><td colspan="2">ENV</td></tr>
		<?php foreach ($_ENV as $name => $value) { ?>
			<td><?php echo $name; ?></td>
			<td><?php echo $value; ?></td>
		<?php } ?>	
	<?php } ?>

<!-- --------------------------------- -->

	<tr class="yellow"><td colspan="2">Request's header</td></tr>
	<?php foreach (apache_request_headers() as $name => $value) { ?>
		<tr>
			<td><?php echo $name; ?></td>
			<td><?php echo $value; ?></td>
		</tr>			
	<?php } ?>
