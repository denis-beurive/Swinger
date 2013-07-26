<?php

/**
 * \file Registry.php
 * This file contains the implementation of a registry that can be used to store globally accessible variables.
 */

/**
 * \class Registry
 * \brief This class implements the registry.
 */
class Registry
{
	private static $__data     = array();
	private function __construct() {}

	/**
	 * \brief Put a value in the registry.
	 * \param[in] $in_name Name of the value.
	 * \param[in] $in_value Value.
	 */
	public static function set($in_name, $in_value)
	{ self::$__data[$in_name] = $in_value; }
	
	/**
	 * \brief Retrieve a value from the registry.
	 * \param[in] $in_name Name of the value to retrieve.
	 * \return The method returns the value.
	 * \exception ExceptionOnRegistry
	 *            If the given name is not recorded in the registry, then the method raises an exception.
	 * \note If you need to test if a value is recorded in the registry, then you should use the method exists().
	 */
	public static function get($in_name)
	{
		if (array_key_exists($in_name, self::$__data)) { return self::$__data[$in_name]; }
		$e = new ExceptionOnRegistry();
		$e->data = array('name' => $in_name);
		throw $e;
	}
	
	/**
	 * \brief Test if a value is recorded in the registry.
	 * \param[in] $in_name Name of the value.
	 * \return If the value is recorded in the registry, then the method returns the value TRUE.
	 *         Otherwise, it returns the value FALSE.
	 */
 	public static function exists($in_name) { return array_key_exists($in_name, self::$__data); }
}

?>