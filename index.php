<?php

// File index.php
// This file is the entry point for your application.
// Please note that some options are set in the file "config.php"

function begin()
{
	// The code in this function will be executed first.
	
	if (isset($_SESSION['counter'])) {
	    $_SESSION['counter'] += 1;
	} else {
	    $_SESSION['counter'] = 1;
	}
	
	// Log::deactivateLinearization();
	Log::setLevel(Log::INFO);
	Log::info("Starting with PID " . getmypid());
	Log::error("A provisioning error occurred on the VOIP flow.\nNumber:0123456789\nId:37");
}

// Here, you should declare the controllers.
// A controller is bound to an URL, and an output format ("TEXT", "SCREEN" or "JSON"). 
//
// Example 1:
//     Controller::register('get', '/command/hello', 'getHello');
//     The function getHello() is executed when the server receives a "GET /command/hello".
// Example 2:
//     Controller::register('post', '/command/hello', 'postHello');
//     The function postHello() is executed when the server receives a "POST /command/hello".
// Example 3:
//     Controller::register('all', '/command/hello', 'allHello');
//     The function allHello() is executed when the server receives a "GET /command/hello"
//     _OR_ a "POST /command/hello"
// Example 4:
//     Controller::register('all', '/', 'index');
//     The function index() is executed when the server receives a "GET /" _OR_ or "POST /".
// Example 5:
//     Controller::register('all', '/a/b/c', 'abc');
//     The function abc() is executed when the server receives a "GET /a/b/c"
//     _OR_ or "POST /a/b/c".
// Example 6:
//     Controller::register('get',  new Regexp('/^\/step(\d+)$/'), 'stepper');
//     The function stepper($captures) is executed when the server receives:
//     "GET /step0" or "POST /step0"
//     "GET /step00" or "POST /step00"
//     "GET /step01" or "POST /step01"
//     ...
// Example 7:
//     Controller::register('get',  new Regexp('/^\/jump(\/(left|right)?)?$/'), 'jumper');
//     The function jumper() is executed when the server receives:
//     "GET /jump"
//     "GET /jump/left"
//     "GET /jump/right"
// Example 8:
//     Controller::register('get',  '/to/screen', 'screener', 'SCREEN');
//     The function screener() is executed when the server receives:
//     "GET /to/screen"
//     The controller uses "echo()" to send the output to the client.
// Example 9:
//     Controller::register('all', '/to/json', 'jsoner', 'JSON');
//     The function jsoner() is executed when the server receives:
//     "GET /to/json" or "POST /to/json"
//     The controller returns a PHP variable.
// Example 10:
//     Controller::register('all', '/params', 'showParams', 'SCREEN');
//     The function showParams() is executed when the server receives:
//     "GET /params?name=value&..."
//     The controller uses "echo()" to send the output to the client.
//
// You can define a default controller that will be executed if no controller is set for the current URL.
// Use the method Controller::serDefault(...).
//
// Example: Controller::setDefault('defaultController', 'TEXT');
//          or
//          Controller::setDefault('defaultController');

Controller::register('all',  '/',                                        'index');
Controller::register('get',  '/command/hello',                           'getHello');
Controller::register('post', '/command/hello',                           'postHello');
Controller::register('all',  '/command/bye',                             'allBye');
Controller::register(NULL,   '/a/b/c',                                   'abc');
Controller::register('all',  new Regexp('/^\/step(\d+)$/'),              'stepper');
Controller::register('get',  new Regexp('/^\/jump(\/(left|right)?)?$/'), 'jumper');

Controller::selDefault('defaultController', 'TEXT');

Controller::register('get',  '/to/screen',   'screener',   'SCREEN');
Controller::register('all',  '/to/json',     'jsoner',     'JSON');
Controller::register('all',  '/params',      'showParams', 'SCREEN');
Controller::register('all',  '/test_layout', 'testLayout');
Controller::register('all',  '/test_view',   'testView');

Controller::register('get',  '/show/vars',   'showVars', 'SCREEN');
Controller::register('get',  '/show/login',  'showLogin');


// Below, we declare the functions used as controllers.

function index()
{
	// This controller is associated with a view.
	// The view is located in the file "views/index.php".
	// To pass values to the view, just use the array View::$params.
	// For example: View::$params['IP'] = gethostname();
	View::$params['IP']      = gethostname();
	View::$params['counter'] = $_SESSION['counter'];
	
	return View::load();
}

function getHello()
{
	return "Executing getHello()";
}

function postHello()
{
	return "Executing postHello()";
}

function allBye()
{
	return "Executing allBye()";
}

function abc()
{
	return "Executing abc()";
}

function stepper($captures)
{
	// $captures is an array that contains the captured matches.
	// $captures[0] will contain the text that matched the full pattern,
	// $captures[1] will have the text that matched the first captured parenthesized subpattern,
	// and so on.
	// For example: new Regexp('/^\/step(\d+)$/')
	//              If the URL is "step12"
	//              then $capture[0] is "step12"
	//              and  $capture[1] is "12".
	return "Executing stepper() on step " . $captures[1];
}

function jumper($captures)
{
	// This controller will just give control to the controller "nextStep".
	$where = count($captures) > 2 ? $captures[2] : 'nowhere';
	Registry::set('destination', $where);
	Controller::jumpTo('nextStep');
	
	// The following code will __NOT__ be executed !!!
	exit(-1);   // __NOT__ executed !!!
}

function nextStep()
{
	// Please note that this controller is _NOT_ declared (Controller::register(...)).
	// Therefore, the output format is "TEXT".
	return 'Executing nextStep() on "' . Registry::get('destination') . '"';
}

function defaultController()
{
	return 'This is the default controller!';
}

function screener()
{
	// This controller illustrates the use of echo() to return data to the WEB server.
	echo 'Executing screener(). This controller echos text to the standard output.';
}

function jsoner()
{
	// This controller illustrates the return of JSON value.
	return array('ok' => 1);
}

function showParams()
{
	// This controller illustrates the use of Request::vars().
	echo 'List of values:';
	echo '<ul>';
	foreach (Request::values() as $name => $value) { echo "<li>$name = $value</li>"; }
	echo '</ul>';
}

function testLayout()
{
	// This controller illustrates the way to change the layout.
	Layout::setLayoutName('test'); // or Layout::setLayoutName('test.php');
	return View::load();
}

function testView()
{
	// This controller illustrates the way to change the layout.
	Layout::setLayoutName('test'); // or Layout::setLayoutName('test.php');
	// This controller illustrates the way to change the view.
	View::setViewName('test');
	return View::load();
}

function showLogin()
{
	$login = Request::value('login');
	$login = FALSE === $login ? 'Unknown user' : $login;
	return "Login is: $login";
}

?>