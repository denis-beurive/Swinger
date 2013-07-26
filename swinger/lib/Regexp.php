<?php

/**
 * \file Regexp.php
 * This file implements a simple component for regular expression.
 */

/**
 * \class Regexp
 * \brief This class implements a regular expression object. This object must be used within controllers declarations.
 * \note  Controller declaration is done through the following functions:
 *        <ul>
 *            <li>get</li>
 *            <li>post</li>
 *            <li>all</li>
 *        </ul>
 *        Example: Controller::register('get', new Regexp('/\/command\/ull/'), 'ull');
 */
class Regexp
{
	private $__expression = null;
	
	/**
	 * \brief Captured matches.
	 * \note See documentation for PHP function "preg_match".
	 */
	public $captures = array();
	
	public function __construct($in_rexpression)
	{
		$this->__expression = $in_rexpression;
	}
	
	/**
	 * \brief Compare a given string to the defined regular expression.
	 * \param[in] $in_string String to compare.
	 * \return If the given string matches the regular expression, then the method returns the value TRUE.
	 *         Otherwize, the method returns the value FALSE.
	 * \exception ExceptionOnRegexp
	 *            If the given pattern is not valid, the the method throws an exception.
	 */	
	public function match($in_string)
	{
		$res = preg_match($this->__expression, $in_string, $this->captures);
		
		if (FALSE === $res)
		{
			$e = new ExceptionOnRegexp();
			$e->data = array('expression' => $this->__expression, 'errstr' => Error::$errstr, 'errfile' => Error::$errfile, 'errline' => Error::$errline);			
			throw $e;
		}
		return $res > 0;
	}
	
	/**
	 * \brief Return the number of captured strings.
	 * \return The method returns the number of captured parentheses.
	 * \note See documentation for PHP function "preg_match".
	 */
	public function count() { return count($this->captures); }
}

?>