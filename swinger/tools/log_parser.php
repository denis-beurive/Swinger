<?php

// \file log_parser.php
// This file implements a LOG parser for Swinger's applications.
// For help, type: php log_parser.php -h
// 
// Examples:
//
// Lets consider the following LOG:
//
//   20130718153303 bmpq I R Starting with PID 1565
//   20130718153303 bmpq E L A%20provisioning%20error%20occurred%20on%20the%20VOIP%20flow.%0ANumber%3A0123456789%0AId%3A37
//   20130718153305 DWQz I R Starting with PID 1565
//   20130718153305 DWQz E L A%20provisioning%20error%20occurred%20on%20the%20VOIP%20flow.%0ANumber%3A0123456789%0AId%3A37
//
// [1] Convert the URL encoded messages into clear text.
//     php log_parser.php -x < ../../logs/application.log
//     php log_parser.php --expand < ../../logs/application.log
//
// [2] Keep only the line associated with the session "DWQz".
//     php log_parser.php -sDWQz < ../../logs/application.log
//     php log_parser.php --session=DWQz < ../../logs/application.log
//
// [3] Keep only error messages:
//     php log_parser.php -lE < ../../logs/application.log 
//     php log_parser.php --level=E < ../../logs/application.log 
//
// [4] Keep only error messages and show clear text:
//     php log_parser.php -lE -x < ../../logs/application.log 
//     php log_parser.php --level=E --expand < ../../logs/application.log

date_default_timezone_set("Europe/Dublin");

// Set PHP configuration.
$ROOT   = dirname(__DIR__);
$SYSTEM = $ROOT . DIRECTORY_SEPARATOR . 'lib';
set_include_path(get_include_path() . PATH_SEPARATOR . $SYSTEM);
require_once "Log.php";

// Parse the commande line.
$short_opt = 's::l::xh';
$long_opt  = array('session::', 'level::', 'expand', 'help'); 
$options   = getopt($short_opt, $long_opt);
if (FALSE === $options) { help(); exit(1); }
try {
       $o_session = getOptValue($options, 's', 'session');
       $o_level   = getOptValue($options, 'l', 'level');
       $o_expand  = getOptValue($options, 'x', 'expand');
       $o_help    = getOptValue($options, 'h', 'help');
    }
catch (Exception $e)
{
	file_put_contents('php://stderr', $e->getMessage() . "\n"); 
	exit (1);
}

if (! is_null($o_help)) { echo help(); exit(0); }

// Process standard input.

while ($line = fgets(STDIN))
{
	$data = Log::parse($line);
	if (FALSE === $data)
	{
		file_put_contents('php://stderr', "Invalid line:\n" . $line);
		continue;
	}
	
	if (! is_null($o_session))
	{ if ($data['session'] != $o_session) { continue; } }
	
	if (! is_null($o_level))
	{ if ($data['level'] != $o_level) { continue; } }
	
	if (! is_null($o_expand))
	{ if ('L' == $data['encoding']) { $data['message'] = Log::delinearize($data['message']); } }
	
	printLog($data);
}

exit(0);


/**
 * \brief Print the help.
 */
function help()
{
	echo "Usage\n\n";
	echo "Using short options:\n";
	echo "php log_parser.php [-s<session>] [-l<D|I|W|E|F>] [-x] [-h]\n\n";
	echo "Using long options:\n";
	echo "php log_parser.php [--session=<session>] [--level=<D|I|W|E|F>] [--expand] [--help]\n\n";	
}

/**
 * \brief Print a line of LOG.
 * \param[in] $in_log Associative array returned by the method Log::parser().
 * \see method Log::parser().
 */ 
function printLog($in_log)
{
	echo $in_log['timestamp'] . ' ' .
	     $in_log['session']   . ' ' .
	     $in_log['level']     . ' ' .
	     $in_log['encoding']  . ' ' .
	     $in_log['message'];
}

/**
 * \brief Get an option's value from the command line.
 * \param[in] $in_options Associative array returned by the fonction getopt().
 * \param[in] $in_short Short option's tag (ex: h, l, x, s)
 * \param[in] $in_long Long option's tag (ex: help, level, expand, session).
 * \return If the option is set, then the function returns its value.
 *         Otherwise, the function returns NULL.
 */
function getOptValue($in_options, $in_short, $in_long=null)
{	
	if (array_key_exists($in_short, $in_options))
	{
		if (! is_null($in_long))
		{
			if (array_key_exists($in_long, $in_options))
			{ throw new Exception("Options -$in_short and --$in_long can not be used together."); }
		}
		return $in_options[$in_short];
	}
	else
	{
		if (! is_null($in_long))
		{
			if (array_key_exists($in_long, $in_options)) { return $in_options[$in_long]; }
		}
	}
	return null;
}


?>