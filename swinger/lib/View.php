<?php

/**
 * \file View.php
 * This file implements the view manager.
 */

/**
 * \class View
 * \brief This class implements the view manager.
 */
class View
{
	private static $__view_name = null;
	private static $__view_dir  = null;
	public  static $params      = array();

	private function __construct() {}
	
	/**
	 * \brief Set the name of the view to render.
	 * \param[in] $in_name Name of the view (including the ".php" extension).
	 * \note Please note that the view is a PHP file.
	 */
	public static function setViewName($in_name)
	{
		if (! preg_match('/\.php$/i', $in_name)) { $in_name .= '.php'; }
		self::$__view_name = $in_name;
	}
	
	/**
	 * \brief Get the name of the view to render.
	 * \return The method returns the name of the view to render.
	 */
	public static function getViewName($in_name) { return self::$__view_name; }
	
	/**
	 * \brief Set the path to the directory that contains the PHP file that defines the view to render.
	 * \param[in] $in_dir Path to the directory.
	 */
	public static function setViewDir($in_dir) { self::$__view_dir  = $in_dir;  }
	
	/**
	 * \brief Get the path to the directory that contains the PHP file that defines the view to render.
	 * \return The method returns the path to the directory that contains the PHP file that defines the view to render.
	 */
	public static function getViewDir($in_dir) { return self::$__view_dir;  }
	
	/**
	 * \brief Get the absolute path to the PHP file that defines the view to render.
	 * \return The method returns the absolute path to the PHP file that defines the view to render.
	 */
	public static function getViewPath() { return  self::$__view_dir . DIRECTORY_SEPARATOR . self::$__view_name; }
	
	/**
	 * \brief Load the view and return a string that represents the content that would have been printed to the standard output.
	 * \return The method returns a string that represents the content that would have been printed to the standard output.
	 * \throw if the view can not be loaded, then the method raises an exception.
	 */
	public static function load()
	{
		$path = self::getViewPath();
		$view_content = getIncludeContents($path);
		if (FALSE === $view_content)
		{
			$e = new ExceptionOnViewLoad();
			$e->data = array('view' => $path);
			throw $e;
		}
		
		Layout::$view_content = $view_content;
		return Layout::load();
	}
}

?>