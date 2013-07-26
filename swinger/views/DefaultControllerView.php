<!-- File <?php echo __FILE__; ?>  -->

	<div class="page">
		<h2>Oops... Wrong note!</h2>
	</div>

 	<div id="c">

		<p>This is the default view.</p>
	
		<p>You should customize this page.</p>
	
		<p>To do that, you declare a default controller. That is: you declare a controller that will be executed if no appropriate controller is found.</p>
		
		<form>
			<textarea class="code_150" wrap="off" readonly>
Controller::selDefault('your_default_controller');

function your_default_controller()
{
	// Optional:
	// If you store the layout for this controller in another directory than the default one.
	// By default: "<?php echo Env::get('layout_dir');?>".
	Layout::setLayoutDir('your_special_directory');
	
	// Optional:
	// If you store the layout for this controller in another file that the defaule one.
	// By default: "<?php echo Env::get('layout_name');?>".
	Layout::setLayoutName('your_special_layout');
	
	// => Default layout is:
	// "<?php echo Env::get('layout_dir') . DIRECTORY_SEPARATOR . Env::get('layout_name');?>".
	
	// Optional:
	// If you store the view for this controller in another directory that the defaule one.
	// By default: "<?php echo Env::get('view_dir');?>".
	View::setViewDir(Env::get('swinger_view_dir'));
	
	// Optionnal:
	// By default, the name of the file that represents the view is the name of the current function (that is "your_default_controller").
	// If you want to change this:
	View::setViewName('your_special_name_for_the_view_file');
	
	// => Default view is:
	// "<?php echo Env::get('view_dir') . DIRECTORY_SEPARATOR . 'your_default_controller';?>".
	
	// Mandatory:
	echo View::load();
}
			</textarea>
		</form>
			
	<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>

  </div>

<!-- End of file <?php echo __FILE__; ?>  -->