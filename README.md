


# WARNING

NOTE: This framework is under construction. Heavy changes are made. Do not use it.

The following documentation is not valid.



# General description

Swinger is inspired from the Ruby framework Sinatra.
This is a "VC"  (View Controller) framework for PHP.
Swinger is dead simple and extremely lightweight, in comparison to ZF of Symphony.

You can use it to create:

*   Simple WEB sites.
*   Technical user interfaces.
*   WEB sites for low power infrastructure.
*   Provisioning interfaces.
*   ...

For these kinds of applications, using ZF of Symphony is like using a sledge-hammer to crack a nut.

Swinger provides:

*   Basic layout/view support. Swinger does not integrate fancy template engines, since PHP is already suited for this application. A layout, or a view, is just a PHP file.
*   Controller. The organisation of URLs is totally free. You don't have to follow the "controller/action" rule. The URL associated to a controller can be defined as a regular expression.
*   Basic, but powerful auto-load management. Auto-load management is really basic, but it offers the best performance in terms of speed, when the application runs in "production mode".
*   Registry.
*   Nice error message in case you made an error with the framework's API.

# Installation

The installation involves two steps:

*   Step 1: install the framework.
*   Step 2: configuration your WEB server.

## Installing the framework

In this document, we assume that you put your WEB application in the directory "`/home/www/my_application`".
"`/home/www/my_application`" is called the "application's directory".

Just uncompress the Swinger's archive in your application's directory (that is "`/home/www/my_application`").

You should have the following entries:

*   `/home/www/my_application/README.md`: This is the GitHub's README file. It uses GitHub Flavored Markdown (GFM). You can delete this file.
*   `/home/www/my_application/config.php`: This is where you configure the framework and where you (should) put your application's configuration.
*   `/home/www/my_application/data`: Swinger uses this directory to put temporary files.
*   `/home/www/my_application/doxygen.conf`: This is the [Doxygen](http://www.stack.nl/~dimitri/doxygen/index.html "Doxygen")'s configuration file. Doxygen is used to produce documentation from the source code. You can delete this file.
*   `/home/www/my_application/index.php`: This is the entry point for your application.
*   `/home/www/my_application/layouts`: This directory is used to store the layouts.
*   `/home/www/my_application/lib`: This directory is used to store your PHP code.
*   `/home/www/my_application/logs`: This directory should be used to put LOG files.
*   `/home/www/my_application/public`: This directory is used to store any files other that PHP files (images, CSS, javascript...).
*   `/home/www/my_application/views`: This directory is used to store the views.

**WARNING**

You must check the files' permissions.

*   `/home/www/my_application/data`: Make sure that your WEB user has "execute", "read" and "write" permissions on this directory.
*   `/home/www/my_application/layouts`: Make sure that your WEB user has "execute" and "read" permissions on this directory and sub-directories.
                                        Make sure that your WEB user has "read" permissions on all PHP files under this directory.
*   `/home/www/my_application/lib`: Make sure that your WEB user has "execute" and "read" permissions on this directory and sub-directories.
                                    Make sure that your WEB user has "read" permission on all PHP files under this directory.
*   `/home/www/my_application/logs`: If you plan to put LOG files under this directory, then make sure that your WEB user has "execute", "read" and "write" permission on this directory.
*   `/home/www/my_application/public`: Make sure that your WEB user has "execute" and "read" permissions on this directory and sub-directories.
                                       Make sure that your WEB user has "read" permission on all files under this directory.
*   `/home/www/my_application/views`: Make sure that your WEB user has "execute" and "read" permissions on this directory and sub-directories.
									  Make sure that your WEB user has "read" permission on all PHP files under this directory.


## Configuring the WEB server

### Using Apache's virtual host

#### Template

Here is a basic virtual host definition for Swinger.

This example assumes that:
*   You put your Swinger application in the directory "`/home/www/my_application`". You must change it according to your environment.
*   The host name for the virtual host is "my_application.com". You must change it according to your environment.

		<VirtualHost *:80>
		
			# Replace "/home/www/my_application" by whatever you need.
		   	DocumentRoot "/home/www/my_application"
	
		   	<Directory />
		       	AllowOverride All
		       	Order Allow,Deny
		       	Allow from all
		   	</Directory>
	
			# Replace "my_application.com" by whatever you need.
		   	ServerName my_application.com
		
		   	Options +FollowSymLinks 
		   	RewriteEngine on
	
			# First rule
			# If you use other type of files than those listed below (.html, .ico, ...), you must add them.
			RewriteCond %{REQUEST_URI} \.(html|ico|bmp|svg|jpg|jpeg|gif|png|js|css|swf|zip|tar|gz)$ [nocase]
			RewriteRule ^(.*)$ /public/$1 [last]
	
			# Second rule
		   	RewriteCond %{DOCUMENT_ROOT}/$1 !-f
		   	RewriteRule ^(.*)$ /lib/System/Swing.php/$1 [last,qsappend]
	
		</VirtualHost>

#### Notes

The first rewrite rule is:

	RewriteCond %{REQUEST_URI} \.(html|ico|bmp|svg|jpg|jpeg|gif|png|js|css|swf|zip|tar|gz)$ [nocase]
	RewriteRule ^(.*)$ /public/$1 [last]

It means: Any URL that points to a file which extension is included in the given list (.html, .ico...) is treated as a static file and will be searched under the directory "`/home/www/my_application/public/`".

For example: "`http://my_application.com/photo.png`" refers to the file "`/home/www/my_application/public/photo.png`".

For example: "`http://my_application.com/swinger/main.css`" refers to the file "`/home/www/my_application/public/swinger/main.css`".

The second rewrite rule is:

	RewriteCond %{DOCUMENT_ROOT}/$1 !-f
	RewriteRule ^(.*)$ /lib/System/Swing.php/$1 [last,qsappend]
	
It means: any HTTP request (other that those which match the first rewrite rule) will be "redirected" to the script "`/lib/System/Swing.php`".
	
For example: "`http://my_application.com/foo/bar`" will execute the script "`/home/www/my_application/lib/System/Swing.php`". 

# Quick start

In the section we assume that your application is accessible through the URL "http://my_application.com".
This depends on the configuration of your WEB server.

## The basic "Hello World"

Edit the file "index.php" (in the application's directory).

	<?php
	
	// <=> Controller::register('all',  '/', 'index', 'TEXT');
	Controller::register('all',  '/', 'index');
	
	function index() { return "Hello world!"; }
	
	?>

This means: any type of request ("GET" or "POST") on the URL "http://my_application.com" will execute the function "index()".

The example above is equivalent to the example below. 

	<?php
	
	// Notice the last parameter "SCREEN".
	Controller::register('all',  '/', 'index', 'SCREEN');
	
	// Notice that we perform an "echo".
	function index() { echo "Hello world!"; }
	
	?>

And what if you want to return some JSON data ? 

	<?php
	
	// Notice the last parameter "JSON".
	Controller::register('all',  '/', 'index', 'JSON');
	
	// Notice that we just return the value.
	function index() { return array('text' => 'Hello world!'); }
	
	?>

## GET or POST ?

Now, you want "Hello world" to be printed only on a GET request.

	<?php
	
	Controller::register('get',  '/', 'index');
	
	function index() { return "Hello world!"; }
	
	?>

But I want to do something if I receive a POST request! No problem :

	<?php
	
	Controller::register('get',  '/', 'index');
	Controller::register('post',  '/', 'calculate', 'JSON');
	
	function index() { return "Hello world!"; }
	function calculate() { return array('data' => 0); }
	
	?>

## More complex URLs

So far, the application only treats requests on the top level URL. Let's register other URLs.

	<?php
	
	Controller::register('get',  '/', 'index');
	Controller::register('get',  '/meteo', 'meteo', 'SCREEN');
	Controller::register('get',  '/meteo/next', 'tomorrow', 'TEXT');
	
	function index() { return "Hello world!"; }
	function meteo() { echo "Sun"; }
	function tomorrow() { return "Rain"; }
	
	?>

GET http://my_application.com => execute the function index().

GET http://my_application.com/meteo => execute the function meteo().

GET http://my_application.com/meteo/next => execute the function tomorrow().

## Even more complex URL: regular expression

	<?php
	
	Controller::register('get',  new Regexp('/^\/jump(\/(left|right)?)?$/'), 'jumper');
	
	function jumper($captures)
	{
		$where = count($captures) > 2 ? $captures[2] : 'nowhere';
		return "Jump to $where";
	}
	
	?>

GET http://my_application.com/jump => will print "Jump to nowhere".

GET http://my_application.com/jump/left => will print "Jump to left";

GET http://my_application.com/jump/right => will print "Jump to right";

	<?php
	
	Controller::register('get',  new Regexp('/^\/jump(\/([a-z0-9]+)?)?$/'), 'jumper');
	
	function jumper($captures)
	{
		$where = count($captures) > 2 ? $captures[2] : 'nowhere';
		return "Jump to $where";
	}
	
	?>

GET http://my_application.com/jump => will print "Jump to nowhere".

GET http://my_application.com/jump/toto => will print "Jump to toto".

GET http://my_application.com/jump/foo123 => will print "Jump to foo123".

...

## A catch-all controller

	<?php
	
	Controller::register('get',  '/', 'index');
	Controller::selDefault('defaultController', 'TEXT');
	
	function index() { return "Hello world!"; }
	function defaultController() { return 'This is the default controller!'; }
	
	?>

GET http://my_application.com => will print "Hello world!".

Any other request will print "This is the default controller!".

# Getting information about the request

See [class Request](https://github.com/denis-beurive/Swinger/blob/master/lib/System/Request.php "code").

## Request::get($in_name)

Any value available from the PHP "superglobal" $_SERVER is accessible through the method <code>Request::get($in_name)</code>.

**$in_name** Name of the value to retrieve.

The method returns the searched value, or null if no value is found.

For example, you want to know the client's IP address :

	$client_ip = Request::get('remote_addr');
	
	// or
	
	$client_ip = Request::get('REMOTE_ADDR');
	
	// or
	
	$client_ip = Request::get('Remote_Addr');
	
	if (is_null($client_ip)) { ... }

## Request::values()

All values sent by the client is accessible through the method <code>Request::values()</code>.

For example :

	// $vars is just $_GET or $_POST (see PHP documentation).
	// $vars is an associative array.
	$vars = Request::values(); 
	foreach ($vars as $name => $value) { ... }

## Request::value($in_name)

If you only need to retrieve a single value, just use the method <code>Request::value($in_name)</code>.

**$in_name** Name of the value to retrieve.

If the value is set, then the method returns it. Otherwise, the method returns the value FALSE.

For example :

	$login = Request::value('login');
	if (FALSE === $login) { ... }

# The controller's API in details

See [class Controller](https://github.com/denis-beurive/Swinger/blob/master/lib/System/Controller.php "code").

The class Controller exports three methods :
*   register(): bind a controller to a given URL ans a HTTP method (defined by a string or by a regular expression).
*   selDefault(): set a "catch all" controller.
*   jumpTo(): stop the execution of the current controller and start executing a new one.

## Controller::register($in_method, $in_expression, $in_function, $in_opt_expected="TEXT")

Bind a controller to a URL and a HTTP method.

**$in_method** Name of the HTTP method.

It can be:
*   "GET"
*   "POST"
*   "ALL" (or null). "ALL" means "GET or POST".

**$in_expression** Expression that represents the controller.

It can be:
*   A string. For example: "/user/login" or "/command/map/ul".
*   A regular expression: For example: new Regexp('/^\/jump(\/(left|right)?)?$/').

**$in_function** The name of the function that will be executed.

The function's signature is: 

	function myController([$capture]) { ... }

The argument "$capture" is optional.
*   If you define the URL through a string, then this argument is useless. 
*   If you define the URL through a regular expression, then this argument may be necessary. It depends if your regular expression includes capture parenthesis. If you're the regular expression contains capture parenthesis, then you need a way to retrieve the captured strings. You do so through the argument, which is an array. Please see the documentation for preg_match().

**$in_opt_expected** The expected controller's output.

It could be:
*   "TEXT": the controller must return a string. This is the default value.
*   "SCREEN": the controller must echo text to the standard output.
*   "JSON": the controller must return an array.

For examples :

	// URL is a simple string.
	
	Controller::register('get',  '/command/hello', 'getHello');
	Controller::register('post', '/command/hello', 'postHello', 'SCREEN');
	Controller::register('all',  '/command/bye',   'allBye', 'JSON');
	
	function getHello()  { ... return $string; }
	function postHello() { ... echo $string; }
	function allBye()    { ... return array('status' => OK); }
	
	// URL is a regular expression.
	
	Controller::register('get',  new Regexp('/^\/jump(\/(left|right)?)?$/'), 'jumper');
	
	function jumper($captures)
	{
		$where = count($captures) > 2 ? $captures[2] : 'nowhere';
		return "You want to go to $where!";
	}

See [index.php](https://github.com/denis-beurive/Swinger/blob/master/index.php "code") for more examples.

## Controller::selDefault($in_name, $in_opt_format='TEXT')

Register a default controller that will be executed if no specific controller has been registered for the current URL.

**$in_name** Name of the function to execute.

**$in_opt_format** The expected controller's output.

It could be:
*   "TEXT": the controller must return a string. This is the default value.
*   "SCREEN": the controller must echo text to the standard output.
*   "JSON": the controller must return an array.

For example:

	Controller::selDefault('defaultController', 'TEXT');
	
	function defaultController()
	{
		return 'This is the default controller!';
	}
	
## Controller::jumpTo($in_controller_name)

Stop the execution of the current controller and start executing a new one.

**$in_controller_name** Name of the controller to execute.

For example:

	Controller::register('get',  new Regexp('/^\/jump(\/(left|right)?)?$/'), 'jumper');
	
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

# The registry

The registry is just a convenient place where you can store global variables.

See [class Registry](https://github.com/denis-beurive/Swinger/blob/master/lib/System/Registry.php "code").

Note that the class' code should be very clear.

## Registry::set($in_name, $in_value)

Store a value in the registry.

**$in_name** Name of the value.

**$in_value** The value.

For example:

	Registry::set('level', 0);

## Registry::get($in_name)

Retrieve a value from the registry.

**$in_name** Name of the value to retrieve.

If the requested value is not recorded in the registry, then the method raises an exception.

For example:

	Registry::set('level', 0);
	// ...
	$level = Registry::get('level');

ls
If the requested value is already recorded in the registry, then the method raises an exception.
	
## Registry::exists($in_name)

Test if a value is recorded in the registry.

**$in_name** Name of the value.

If the value is recorded in the registry, then the method returns the value TRUE.
Otherwise, it returns the value FALSE.

For example:

	if (FALSE !== Registry::exists('level'))
	{
		// The value 'level' is recorded in the registry.
	}



