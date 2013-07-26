<?php

/**
 * \file Controller.php
 * This file implements the controllers' manager. 
 * The controllers' manager determines the controller to execute, according to the current URL.
 */

/**
 * \brief This exception is used to interrupt the execution of a controller.
 */

class __InterruptController extends Exception { }

/**
 * \class Controller
 * \brief The class implements the controllers' manager. 
 */
class Controller
{
	private static $__expected_methods   = array('ALL' => null, 'GET'  => null, 'POST' => null);
	private static $__expected_outputs   = array('TEXT' => null, 'JSON' => null, 'SCREEN' => null);
	private static $__controllers_list   = array();
	private static $__controllers_data   = array();
	private static $__default_controller = null;
	private static $__default_format     = null;
	private static $__jump_to            = null;

	/**
	 * \brief Set the default controller. 
	 * \param[in] $in_name Name of the default controller.
	 * \param[in] $in_opt_format View's output format ("TEXT", "SCREEN" or "JSON").
	 */
	public static function selDefault($in_name, $in_opt_format='TEXT')
	{
		$in_opt_format = strtoupper($in_opt_format);
		if (! array_key_exists($in_opt_format, self::$__expected_outputs))
		{
			$e = new ExceptionOnViewUnexpectedOutputFormat();
			$e->data = array('format' => $in_opt_format);
			throw $e;
		}
		self::$__default_controller = $in_name;
		self::$__default_format     = $in_opt_format;
	}

	/**
	 * \brief Register a controller.
	 * \param[in] $in_method Possible values:
	 *            <ul>
	 *               <li>"GET"</li>
	 *               <li>"POST"</li>
	 *               <li>"ALL" or NULL (that is: "GET" or "POST")</li>
	 *            </ul>
	 * \param[in] $in_expression A pattern that represents the URL's associated with the controller.
	 *            This parameter can be:
	 *            <ul>
	 *               <li>A string (for example: "/command/check").</li>
	 *               <li>An instance of class Regexp (for example: \code{.php}new Regexp('/^commands?$/'))\endcode</li>
	 *            </ul>
	 * \param[in] $in_function Name of the function that implements the controller.
	 * \param[in] $in_opt_expected View's output format ("TEXT", "SCREEN" or "JSON").
	 */
	public static function register($in_method, $in_expression, $in_function, $in_opt_expected="TEXT")
	{
		$in_method       = is_null($in_method) ? 'ALL' : strtoupper($in_method);
		$in_opt_expected = strtoupper($in_opt_expected);
		
		if (! array_key_exists($in_opt_expected, self::$__expected_outputs))
		{
			$e = new ExceptionOnViewUnexpectedOutputFormat();
			$e->data = array('format' => $in_opt_expected);
			throw $e;
		}
		
		if (! array_key_exists($in_method, self::$__expected_methods))
		{
			$e = new ExceptionOnControllerUnexpectedMethod();
			$e->data = array('method' => $in_method); 
			throw $e;
		}
		
		if (array_key_exists($in_function, self::$__controllers_data))
		{
			$e = new ExceptionOnControllerRedeclaredFunction();
			$e->data = array('controller' => $in_function);
			throw $e;
		}
		
		$entry = array('method' => $in_method, 'expression' => $in_expression, 'function' => $in_function, 'output' => $in_opt_expected);
		array_push(self::$__controllers_list, $entry);
		self::$__controllers_data[$in_function] = $entry;
	}
	
	/**
	 * \brief Interrupt the execution of the current controller and start executing a new one.
	 * \param[in] $in_controller_name Name of the new controller to execute.
	 * \note The fonction should be used within the controller.
	 */
	public static function jumpTo($in_controller_name)
	{		
		self::$__jump_to = $in_controller_name;
		throw new __InterruptController();
	}
	
	/**
	 * \brief Start the controllers' namager.
	 */
	public static function run()
	{
		$output = null;
				
		foreach (self::$__controllers_list as $controller)
		{
			if (('ALL' !== $controller['method']) && ($controller['method'] !== Request::get('request_method'))) { continue; }

			$expression     = $controller['expression'];
			$name           = $controller['function'];
			$format         = $controller['output'];
						
			if (Request::match($expression))
			{
				$captures = is_a($expression, 'Regexp') ? $expression->captures : array();
				
				// Execute the controller.
				try { $output = self::__exec($name, $captures, $format); }
				catch (__InterruptController $a) { }
				
				// Should we execute other controllers?
				while (! is_null(self::$__jump_to))
				{
					$name            = self::$__jump_to;
					self::$__jump_to = null;
					
					// Execute the controller.
					// Note that the next controller may not be declared.
					// In this case, the output format is "TEXT".
					$format = array_key_exists($name, self::$__controllers_data) ? self::$__controllers_data[$name]['output'] : 'TEXT';
					try { $output = self::__exec($name, $captures, $format); }
					catch (__InterruptController $a) { }
				}
				
				if (is_null($output))
				{
					$e = new ExceptionOnControllerNoReturn();
					$e->data = array('controller' => $name);
					throw $e;
				}
				return $output;
			}
		}
		
		// Execute default controller.
		if (is_null(self::$__default_controller))
		{
			return self::__default_controller();
		}
		else
		{
			return self::__exec(self::$__default_controller, array(), self::$__default_format);
		}
	}
	
	/**
	 * \brief Implementation of the default controller.
	 * \note It is possible to set a custom default controller. See method selDefault().
	 * \see selDefault().
	 */
	private static function __default_controller()
	{
		Layout::setLayoutDir(Env::get('swinger_layout_dir'));
		Layout::setLayoutName(Env::get('layout_name'));
		View::setViewDir(Env::get('swinger_view_dir'));
		View::setViewName('DefaultControllerView.php');
		return View::load();
	}
	
	/**
	 * \brief Execute a controller.
	 * \param[in] $in_function Name of the function to execute.
	 * \param[in] $in_captures An array that contains the elements extracted from the URL's analysis.
	 *            <ul>
	 *               <li>If the URL's pattern is a string, then this array is empty.
	 *               <li>If the URL's pattern is an instance of Regexp, then this array may contain elements.
	 *            </ul>
	 * \param[in] $in_output_format View's output format ("TEXT", "SCREEN" or "JSON").
	 * \return The method ruturns a string.
	 */
	private static function __exec($in_function, $in_captures, $in_output_format)
	{
		$output = null;	
		
		self::__resetView($in_function);	
		
		if ('SCREEN' === $in_output_format)
		{
			ob_start();
			call_user_func($in_function, $in_captures);
			$output = ob_get_contents();
			ob_end_clean();
		}
		
		if ('TEXT' === $in_output_format)
		{
			$output = call_user_func($in_function, $in_captures);
		}
				
		if ('JSON' === $in_output_format)
		{
			$output = json_encode(call_user_func($in_function, $in_captures));
		}	
		
		return $output;		
	}
	
	/**
	 * \brief Set the current view's environment :
	 *        <ul>
	 *           <li>Path to the layout.
	 *           <li>Path to the view.
	 *           <li>View's data.
	 *        </ul>
	 * \param[in] $in_view Name of the file that contains the view for the current controller.
	 *            By default, this is the name of the function that implements the controller.
	 */
	private static function __resetView($in_view)
	{
		Layout::setLayoutDir(Env::get('layout_dir'));
		Layout::setLayoutName(Env::get('layout_name'));
		View::setViewDir(Env::get('view_dir'));
		View::setViewName("${in_view}.php");
		View::$params = array();
	}
	
}

?>