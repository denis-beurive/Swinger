<?php

/**
 * \file Swing.php
 * This file implements the Swinger's bootstrap sequence.
 */

/**
 * \brief The constant represents the minimum PHP version for Swinger.
 */
define('PHP_MIN_VERSION', '5.1.0');

/**
 * \class Swing
 * \brief This class is responsible for the application's initialization.
 */
class Swing
{
	private static $__CLASS_REPOS = null;

	private function __construct() {}
		
	/**
	 * \brief This method is the first code executed is the application.
	 * \note By default, application's specific classes should be placed into the directory "lib".
	 *       If you want to add another directory, you should add elements to the array $CLASS_REPOS.
	 */
	public static function boot()
	{
		// Application's environment.		
		$ROOT = $_SERVER['DOCUMENT_ROOT'];
		self::$__CLASS_REPOS = array($ROOT . DIRECTORY_SEPARATOR . 'lib' => null);
		
		// Swingers's environment.
		$ROOT_SWINGER = dirname(__DIR__);
		
		// Set PHP include path to Swinger's classes.
		set_include_path(get_include_path() . PATH_SEPARATOR . $ROOT_SWINGER . DIRECTORY_SEPARATOR . 'lib');
		
		// TO DO: check for the time zone.
		
		// Load all systems' classes, so they will be avalaible later.
		// Please note that the load order is very important.
		require_once 'Error.php';
		require_once 'SwingerExceptions.php';
		require_once 'Registry.php';
		require_once 'Env.php';
		require_once 'Request.php';
		require_once 'Regexp.php';
		require_once 'Utils.php';
		require_once 'Layout.php';
		require_once 'View.php';
		require_once 'Controller.php';
		require_once 'Autoload.php';
		require_once 'Log.php';

		// Set the environment for the application.
		Env::set('base_dir',            $ROOT);
		Env::set('view_dir',            $ROOT . DIRECTORY_SEPARATOR . 'views');
		Env::set('layout_dir',          $ROOT . DIRECTORY_SEPARATOR . 'layouts');
		Env::set('public_dir',          $ROOT . DIRECTORY_SEPARATOR . 'public');
		Env::set('data_dir',            $ROOT . DIRECTORY_SEPARATOR . 'data');
		Env::set('log_file',            $ROOT . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'application.log');
		Env::set('class_register_path', Env::get('data_dir') . DIRECTORY_SEPARATOR . 'classes.idx');

		// Set the environment for Swinger.
		Env::set('swinger_base_dir',   $ROOT_SWINGER);
		Env::set('swinger_view_dir',   $ROOT_SWINGER . DIRECTORY_SEPARATOR . 'views');
		Env::set('swinger_layout_dir', $ROOT_SWINGER . DIRECTORY_SEPARATOR . 'layouts');
		
		// Common environment.
		Env::set('layout_name', 'default.php');
		
		Env::freeze();
		
		// Load the configuration file.
		include $ROOT . DIRECTORY_SEPARATOR . 'config.php';

		// Initialize the LOG file.
		// Please note that the path to the LOG file and the session can be set in the
		// configuration file (config.php).
		if (is_null(Log::getLogFile())) { Log::setLogFile(Env::get('log_file')); }
		if (is_null(Log::getSession())) { Log::setSession(getRandomString(4)); }
		
		// Register the default application's class' repository.
		// Warning: file "config.php" may set the a new class repository.
		//          Therefore, the file "config.php" should be loaded before calling AutoLoad::getIndex().
		AutoLoad::getIndex(array_keys(self::$__CLASS_REPOS));
	}
	
	/**
	 * \brief This method returns the list of paths to the classes' repository.
	 * \return The method returns an array of strings.
	 * \remark This method is used by the error reporting system.
	 */
	public static function getRepos()
	{ return array_keys(self::$__CLASS_REPOS); }
	
	/**
	 * \brief Add a new classes' repository to the list of repositories.
	 * \param[in] $in_repos_path Path to the new repository.
	 */
	public static function addClassesRepository($in_repos_path)
	{
		if (! array_key_exists($in_repos_path, self::$__CLASS_REPOS))
		{ self::$__CLASS_REPOS[$in_repos_path] = null; } 
	}
};



// Swing...
try
{	
	// Initialize the application.
	Swing::boot();
	
	// Make sure that we don't use a very old version of PHP...
	if (version_compare(PHP_VERSION, PHP_MIN_VERSION) < 0)
	{ throw new ExceptionPhpTooOld(); }
	
	// Load the application.
	$head = null;
	include Env::get('base_dir') . DIRECTORY_SEPARATOR . 'index.php';
	
	ob_start();
	if (function_exists('begin')) { begin(); }
	$head = ob_get_clean();
	
	$output = Controller::run();
	if (function_exists('before_screen')) { before_screen($output); }
	echo "${head}${output}\n";
}
catch (SwingerException $e)
{
	// Show a nice error page.
	Layout::setLayoutDir(Env::get('swinger_layout_dir'));
	Layout::setLayoutName('default.php');	
	View::setViewDir(Env::get('swinger_view_dir'));
	View::setViewName(get_class($e) . '.php');
	View::$params = $e->data;
	echo View::load();
}
catch (Exception $e)
{
	// Show the nice error page.
	Layout::setLayoutDir(Env::get('swinger_layout_dir'));
	Layout::setLayoutName('default.php');
	View::setViewDir(Env::get('swinger_view_dir'));
	View::setViewName('Exception.php');
	View::$params = $e;
	echo View::load();
}

?>