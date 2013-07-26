<?php $hostname = View::$params['IP'];
      $counter  = View::$params['counter'];
?>


<p>What you see is the view file "<code><?php echo __FILE__; ?></code>".</p>

<p>Welcome to Swinger. Running on "<code><?php echo $hostname; ?></code>" (View::$params['IP']).</p>

<p>See file "<code><?php echo Env::get('base_dir') . DIRECTORY_SEPARATOR . 'index.php'; ?></code>" for details.</p>

<p>Your version of PHP: <?php echo phpversion(); ?></p>

<p>Session counter: "<?php echo $counter; ?>".</p>

<p>Session path is: <?php echo session_save_path(); ?>.</p>

<p>HTTP: <?php echo Request::get('ORIG_PATH_INFO'); ?></p>

<p>Test links</p>

<table>
	<tr class="yellow top">
		<td>Method</td>
		<td>Url</td>
		<td>Test it</td>
		<td>Declaration</td>
		<td>Remark</td>
	</tr>
	<tr>
		<td>GET</td>
		<td>/</td>
		<td>
			<a href="/">Go</a>
		</td>
		<td><code>Controller::register('all', '/', 'index');</code></td>
		<td>This page</td>
	</tr>
	<tr>
		<td>GET</td>
		<td>/command/hello</td>
		<td>
			<a href="/command/hello">Go</a>
		</td>
		<td><code>Controller::register('get', '/command/hello', 'getHello');</code></td>
		<td> </td>
	</tr>
	<tr>
		<td>POST</td>
		<td>/command/hello</td>
		<td>
			<form id="postHello" action="/command/hello" method="post"><a href="javascript: document.forms['postHello'].submit();">Go</a></form>
		</td>
		<td><code>Controller::register('post', '/command/hello', 'postHello');</code></td>
		<td> </td>
	</tr>
	<tr>
		<td>GET</td>
		<td>/command/bye</td>
		<td>
			<a href="/command/bye">Go</a>
		</td>
		<td><code>Controller::register('all', '/command/bye',   'allBye');</code></td>
		<td> </td>
	</tr>
	<tr>
		<td>POST</td>
		<td>/command/bye</td>
		<td>
			<form id="postBye" action="/command/bye" method="post"><a href="javascript: document.forms['postBye'].submit();">Go</a></form>
		</td>
		<td><code>Controller::register('all', '/command/bye',   'allBye');</code></td>
		<td> </td>
	</tr>
	<tr>
		<td>GET</td>
		<td>/a/b/c</td>
		<td>
			<a href="/a/b/c">Go</a>
		</td>
		<td><code>Controller::register(NULL, '/a/b/c', 'abc');</code></td>
		<td>Handle GET and POST</td>
	</tr>
	<tr>
		<td>POST</td>
		<td>/a/b/c</td>
		<td>
			<form id="postAbc" action="/a/b/c" method="post"><a href="javascript: document.forms['postAbc'].submit();">Go</a></form>
		</td>
		<td><code>Controller::register(NULL, '/a/b/c', 'abc');</code></td>
		<td>Handle GET and POST</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/steper123</td>
		<td>
			<a href="/step123">Go</a>
		</td>
		<td><code>Controller::register('all', new Regexp('/^\/step(\d+)$/'), 'stepper');</code></td>
		<td>Illustrate the use of regular expression.</td>
	</tr>	
	<tr>
		<td>POST</td>
		<td>/steper123</td>
		<td>
			<form id="postSteper" action="/step123" method="post"><a href="javascript: document.forms['postSteper'].submit();">Go</a></form>
		</td>
		<td><code>Controller::register('all', new Regexp('/^\/step(\d+)$/'), 'stepper');</code></td>
		<td>Illustrate the use of regular expression.</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/jump</td>
		<td>
			<a href="/jump">Go</a>
		</td>
		<td><code>Controller::register('get',  new Regexp('/^\/jump(\/(left|right)?)?$/'), 'jumper');</code></td>
		<td>Illustrate the use of Controller::jumpTo(...).</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/jump/left</td>
		<td>
			<a href="/jump/left">Go</a>
		</td>
		<td><code>Controller::register('get',  new Regexp('/^\/jump(\/(left|right)?)?$/'), 'jumper');</code></td>
		<td>Illustrate the use of Controller::jumpTo(...).</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/jump/right</td>
		<td>
			<a href="/jump/right">Go</a>
		</td>
		<td><code>Controller::register('get',  new Regexp('/^\/jump(\/(left|right)?)?$/'), 'jumper');</code></td>
		<td>Illustrate the use of Controller::jumpTo(...).</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/undeclared</td>
		<td>
			<a href="/undeclared">Go</a>
		</td>
		<td><code>Controller::selDefault('defaultController', 'TEXT');</code></td>
		<td>Illustrate the use of Controller::selDefault(...).</td>
	</tr>
	<tr>
		<td>POST</td>
		<td>/foo/bar/bla/bla</td>
		<td>
			<form id="fooBar" action="/foo/bar/bla/bla" method="post"><a href="javascript: document.forms['fooBar'].submit();">Go</a></form>
		</td>
		<td><code>Controller::selDefault('defaultController', 'TEXT');</code></td>
		<td>Illustrate the use of Controller::selDefault(...).</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/to/json</td>
		<td>
			<a href="/to/json">Go</a>
		</td>
		<td><code>Controller::register('all', '/to/json', 'jsoner', 'JSON');</code></td>
		<td>Show how to return JSON.</td>
	</tr>
	<tr>
		<td>POST</td>
		<td>/to/json</td>
		<td>
			<form id="toJson" action="/to/json" method="post"><a href="javascript: document.forms['toJson'].submit();">Go</a></form>
		</td>
		<td><code>Controller::register('all', '/to/json', 'jsoner', 'JSON');</code></td>
		<td>Show how to return JSON.</td>
	</tr>
	<tr>
		<td>GET</td>
		<td>/params?a=1&b=2</td>
		<td>
			<a href="/params?a=1&b=2">Go</a>
		</td>
		<td><code>Controller::register('all', '/params', 'showParams', 'SCREEN');</code></td>
		<td>Show the use of Request::values().</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/test_layout</td>
		<td>
			<a href="/test_layout">Go</a>
		</td>
		<td><code>Controller::register('all', '/test_layout', 'testLayout');</code></td>
		<td>Show the use of Layout::setLayoutName(...).</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/test_view</td>
		<td>
			<a href="/test_view">Go</a>
		</td>
		<td><code>Controller::register('all', '/test_view', 'testView');</code></td>
		<td>Show the use of View::setViewName(...).</td>
	</tr>	
	<tr>
		<td>GET</td>
		<td>/show/login?login=toto</td>
		<td>
			<a href="/show/login?login=toto">Go</a>
		</td>
		<td><code>Controller::register('all', '/show/login', 'showLogin');</code></td>
		<td>Show the use of Request::value(...).</td>
	</tr>	
	
	
</table>

