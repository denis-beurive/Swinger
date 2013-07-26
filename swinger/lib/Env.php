<?php

/**
 * \file Env.php
 * This file implements the class used to store the Swinger's environment configuration.
 * \note This class is used internally only by the Swinger's framework.
 */

/**
 * \class Env
 * \brief The class is used to store the Swinger's environment configuration.
 */
class Env
{
	private static $__data     = array();
	private static $__writable = true;
	private function __construct() {}
	
	/**
	 * \brief Freezes the environment's configuration.
	 */
	public static function freeze() { self::$__writable = false; }
	
	/**
	 * \brief Define a new environment variable.
	 * \param[in] $in_name Name of the variable.
	 * \param[in] $in_value Value.
	 * \exception Exception
	 *            If the environment container is not writable (ie: freezed), then the method throws an exception.
	 */
	public static function set($in_name, $in_value)
	{			
		if (! self::$__writable) { throw new Exception("Environement registry is not writable."); }
		self::$__data[$in_name] = $in_value;
	}
	
	/**
	 * \brief Get the value of an environment variable.
	 * \param[in] $in_name Name of the environment variable.
	 * \return The method returns the value associated with the given variable's name.
	 * \exception Exception
	 *            If the given value's name is not registered, then the method throws an exception.
	 */
	public static function get($in_name)
	{
		if (array_key_exists($in_name, self::$__data)) { return self::$__data[$in_name]; }
		throw new Exception("Entry $in_name does not exist in the ENV registry.");
	}
}

?>