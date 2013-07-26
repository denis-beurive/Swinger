<?php

/**
 * \file Error.php
 * This file implements a container used to store information about the current error.
 */

/**
 * \brief Error handler for the application.
 * \note See PHP documentation at http://php.net/manual/fr/function.set-error-handler.php
 */
function error_handler($errno, $errstr, $errfile, $errline)
{
	Error::$errno   = $errno;
	Error::$errstr  = $errstr;
	Error::$errfile = $errfile;
	Error::$errline = $errline;
	Error::$isset   = true;
}

/**
 * \class Error
 * \brief This class holds the data associated to the current error.
 */

class Error
{
	public static $isset = false;
	public static $errno;
	public static $errstr;
	public static $errfile;
	public static $errline;
}

// Set the error handler.
set_error_handler('error_handler');

?>