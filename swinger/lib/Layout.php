<?php

/**
 * \file Layout.php
 * This file contains the implementation of the layout's handler.
 */

/**
 * \class Layout
 * \brief This class implements the layout's handler.
 */
class Layout
{
	public  static $view_content  = null;
	private static $__layout_name = null;
	private static $__layout_dir  = null;
	
	private function __construct() {}
		
	/**
	 * \brief Set the path to the directory that contains the layout.
	 * \param[in] $in_dir Path to the directory.
	 */
	public static function setLayoutDir($in_dir) 
	{
		self::$__layout_dir = $in_dir;
	}
	
	/**
	 * \brief Return the path to the directory that contains the layout.
	 * \return The method returns the path to the directory that contains the layout.
	 */
	public static function getLayoutDir()
	{ return self::$__layout_dir; }
	
	/**
	 * \brief Set the name of the file that contains the layout.
	 * \param[in] $in_name Name of the file.
	 */
	public static function setLayoutName($in_name)
	{
		if (! preg_match('/\.php$/i', $in_name)) { $in_name .= '.php'; }
		self::$__layout_name = $in_name;
	}
	
	/**
	 * \brief Return the name of the file that contains the layout.
	 * \return The method returns the name of the file that contains the layout.
	 */
	public static function getLayoutName()
	{ return self::$__layout_name; }
	
	/**
	 * \brief Return the absolute path to the layout.
	 * \return The method returns the absolute path to the layout.
	 */
	public static function getLayoutPath()
	{ return self::$__layout_dir . DIRECTORY_SEPARATOR . self::$__layout_name; }
	
	/**
	 * \brief Load the layout.
	 * \return The method returns a string.
	 * \exception ExceptionOnLayoutLoad
	 *            If the method cannot load the layout path, then it throws an error.
	 */
	public static function load()
	{
		$path = self::getLayoutPath();
		$text = getIncludeContents($path);
		if (FALSE === $text)
		{
			$e = new ExceptionOnLayoutLoad();
			$e->data = array('layout' => $path);
			throw $e;
		}
		return $text;
	}
}

?>