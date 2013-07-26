<?php

/**
 * \file Request.php
 * This file implements the class used to store the request's data.
 */

require_once 'Regexp.php';

/**
 * \class Request
 * \brief This class is used to store the request's data.
 */
class Request
{
	private function __construct() {}
	
	/**
	 * \brief Get HTTP data.
	 * \param[in] $in_name Name of the data.
	 * \return The method returns the searched value, or null if no value is found.
	 */
	public static function get($in_name)
	{
		$entry = strtoupper($in_name);
		if (array_key_exists($entry, $_SERVER)) { return $_SERVER[$entry]; }
		return null;
	}

	/**
	 * \brief Compares the current URL with a given patern.
	 * \param[in] $in_expression The pattern. This can be:
	 *            <ul>
	 *                <li>A string (ex: '/command/viex').</li>
	 *                <li>An instance of the class Regexp (ex: new Regexp('^([0-9]+)step$'))</li>
	 *            </ul>
	 * \return If the current URL matches the given pattern, then the method returns the value TRUE.
	 *         Otherwize, the method returns the value FALSE.
	 * \exception Exception
	 *            If the given pattern is not a string or an instance of the class "Regexp", the the method throws an exception.
	 */	
	public static function match($in_expression)
	{
		if (is_string($in_expression))       { return $_SERVER['SCRIPT_URL'] === $in_expression; }
		if (is_a($in_expression, 'Regexp'))  { return $in_expression->match($_SERVER['SCRIPT_URL']); }
		throw new Exception("Invalid expression (Should be a string or an instance of Regexp)");
	}
	
	/**
	 * \brief Returns the values sent by the client to the server.
	 * \return The method returns an associative array.
	 */
	public static function values()
	{
		if (count($_GET) > 0)  { return $_GET; }
		if (count($_POST) > 0) { return $_POST; }
		return array();
	}
	
	/**
	 * \brief Returns a value sent by the client to the server.
	 * \param[in] $in_name Name of the value.
	 * \return If the value is set, then the method returns its value.
	 *         Otherwise, the method returns the value FALSE.
	 */
	public static function value($in_name)
	{
		$vars = self::values();
		return array_key_exists($in_name, $vars) ? $vars[$in_name] : FALSE;
	}
}

?>