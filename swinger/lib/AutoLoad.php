<?php

/**
 * \file Autoload.php
 * This file implements the mechanism used to build the class index.
 * This index is used whenever a class could not be instancied.
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . "Utils.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "SwingerExceptions.php";

/**
 * \class AutoLoad
 * \brief This class implements the mechanism used to build the class' index.
 */
class AutoLoad
{
	private static $__top_level_pathes = array();
	private static $__index            = array();  // Ex: 'Date_Operation' => '/path/to/repository/Date/Date_Operation.php'
	private static $__production_mode  = FALSE;
	
	private function __construct() {}

	/**
	 * \brief Return the path to the file that contains a class' implementation.
	 * \param[in] $in_class_name Name of the class.
	 * \return Return the path of the file that contains the class' implémentation.
	 *         If such a file does not exist, then the method returns FALSE.
	 */
	public static function getClassPath($in_class_name)
	{
		if (array_key_exists($in_class_name, self::$__index))
		{ return self::$__index[$in_class_name]; }
		return FALSE;
	}

	/**
	 * \brief Build the classes' index from a list of directories.
	 * \param[in] $in_directories Array of paths to the directories where the classes reside.
	 * \return The method returns the classes' index.
	 */
	public static function getIndex($in_directories)
	{
		foreach ($in_directories as $directory)
		{ self::$__top_level_pathes[] = realpath($directory); }
	
		if (self::$__production_mode)
		{
			// If the register file does not exist, then create it.
			if (! file_exists(Env::get('class_register_path')))
			{
				self::__registerClasses();        // Genarate the index.
				self::__createClassesRegister();  // Save in into a file.
			}
			self::__loadClassesRegister();
		}
		else
		{
			// Generate the index (that is: self::$__index).
			self::__registerClasses();
		}

		return self::$__index;
	}
	
	/**
	 * \brief Activate the production mode.
	 * \note  In this mode, if the file that contains the classes' index exists, then it is simply loaded. 
	 *        In other words: the index is not created. This saves time.
	 */
	public static function activateProductionMode() { self::$__production_mode = TRUE; }
	
	/**
	 * \brief This method scans all the class' repositories and lists all class files.
	 * \note  It creates the attribute self::$__index.
	 */
	private static function __registerClasses()
	{
		$tree   = array();
		$loaded = array();
		
		foreach (self::$__top_level_pathes as $tlp)
		{
			// Make sure that the repository has not already been registered.
			if (array_key_exists($tlp, $loaded)) { continue; }
			
			$prefix_length = strlen($tlp);
			$files = fsFind($tlp, '/^.+\.php$/i');
			foreach ($files as $file)
			{
				// Get the base name and strip the extension ".php".
				// Ex: /path/to/top/Date/Date_Operation.php => Date_Operation
				$basename = substr(basename($file), 0, -4); 
	
				// Date_Operation => /Date/Date_Operation
				// Date_Duration_Operation => /Date/Duration/Date_Operation
				$basename_to_path = self::baseNameToPath($basename);
		
				// /path/to/top/Date/Date_Operation.php => /Date/Date_Operation.php
				$relative_path = substr($file, $prefix_length, -4);
		
				if ($basename_to_path !== $relative_path)
				{
					$e = new ExceptionOnAutoloadInvalidFileName();
					$e->data = array('file' => $file, 'pathes' => Swing::getRepos());
					throw $e;
				}
				$tree[$basename] = $file;			
			}
			
			// OK, $tpl has been loaded
			$loaded[$tlp] = true;
		}
		
		self::$__index = $tree;
	}

	/**
	 * \brief Given a file's base name (without the extension), the method generates the relative path that corresponds to the given name.
	 * \param[in] $in_base_name File's base name (without the extension).
	 * \return The method returns a string that represents the relative path.
	 * \note Example: My_Date => My/My_Date
	 */
	public static function baseNameToPath($in_base_name)
	{
		$tags = explode('_', $in_base_name);
		array_pop($tags);
		$tags[] = $in_base_name;
		return DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $tags);
	}

	/**
	 * \brief Create the file used to store the classes' register.
	 */
	private static function __createClassesRegister()
	{
		$path = Env::get('class_register_path');
		$fd = fopen($path, 'w');
				
		if (FALSE === $fd)
		{
			$e = new ExceptionOnAutoloadCreateIndexFile();
			$e->data = array('path' => $path, 'errstr' => Error::$errstr);
			throw $e;
		}
		fprintf($fd, "%s", serialize(self::$__index));
		fclose($fd);
	}
	
	/**
	 * \brief Load the file that contains the classes' register.
	 */
	private static function __loadClassesRegister()
	{
		$path  = Env::get('class_register_path');
		$index = file_get_contents($path);
		
		if (FALSE === $index)
		{
			$e = new ExceptionOnAutoloadLoadIndexFile();
			$e->data = array('path' => $path);
			throw $e;				
		}
		self::$__index = unserialize($index);
		if (FALSE === self::$__index)
		{
			$e = new ExceptionOnAutoloadInvalidFile();
			$e->data = array('path' => $path);
			throw $e;
		}
	}
}

// Set the autoload handler.
function __autoload($in_class_name)
{
	$path = AutoLoad::getClassPath($in_class_name);
	if (FALSE === $path)
	{
		$e = new ExceptionOnClassLoad();
		$e->data = array('name' => $in_class_name, 'expected' => AutoLoad::baseNameToPath($in_class_name));
		throw $e;		
	}
	include "$path";
}

?>