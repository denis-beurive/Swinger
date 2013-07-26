<?php

/**
 * \file Session.php
 * This file implements some sessions' utilities.
 */

/**
 * \class Session
 * \brief This class implements some sessions' utilities.
 */
class Session
{
	private function __construct() {}
		
	/**
	 * \brief Initialize the sessions' manager.
	 * \param[in] $in_opt_handler Optional handler manager.
	 *            This value must be an object.\n
	 *            Example of object:
	 *            \code{.php}
	 *            new SessionMySql(...)
	 *            \endcode
	 * \exception Exception
	 *            The directory used to store sessions' is not usable, then the method throws an exception.
	 */
	public static function init($in_opt_handler=null)
	{
		if (! is_null($in_opt_handler))
		{
			session_set_save_handler(array($in_opt_handler, 'open'),
			                         array($in_opt_handler, 'close'),
			                         array($in_opt_handler, 'read'),
			                         array($in_opt_handler, 'write'),
			                         array($in_opt_handler, 'destroy'),
			                         array($in_opt_handler, 'gc'));
			register_shutdown_function('session_write_close');
		}
		else
		{
			// Make sure that the session directory is usable.
			// session_save_path('/tmp');
			$session_dir = session_save_path();
			if (! isDirectoryReadWrite($session_dir))
			{
				$e = new Exception("Sessions' directory \"$session_dir\" is not usable.");
				throw $e;
			}			
		}
		
		session_start();
	}
	
	
}

?>