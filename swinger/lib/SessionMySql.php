<?php

/**
 * \file SessionMySql.php
 * This file implements a session manager. It should be used to store session's data into a MySql database.
 */

/**
 *  \class SessionMySql
 *  \brief This class implements a session manager. It should be used to store session's data into a MySql database.
 *
 *  This class supposes that you use the following DB schema:
 *  \code{.txt}
 *        CREATE TABLE sessions
 *        (
 *           `id` VARCHAR(255) NOT NULL,
 *           `data` TEXT NOT NULL,
 *           `timestamp` DATETIME NOT NULL,
 *           INDEX (`id`),
 *           INDEX (`timestamp`),
 *           UNIQUE (`id`)
 *        )  ENGINE=MyISAM;
 *  \endcode
 * 
 * Example: the following should be inserted in the configuration file "config.php".
 *
 * \code{.php}
 * require_once 'Session.php';
 * require_once 'SessionMySql.php';
 * Session::init(new SessionMySql('test', '127.0.0.1:3306', 'root', 'toor'));
 * \endcode
 *
 * \note Please note that the technique in use is not bullet proof, since sessions' IDs are created independently by all PHP processes.
 *       In other words, there is no guarantee that two processes won't generate the same ID.
 *       But the probability of such event to happen is pretty low.
 *       If you don't build a highly critical service, it's OK.
 *       Of course, if you are building a front-end for a bank, then you should adopt another strategy.
 */
class SessionMySql
{
    private $__dbh;
	private $__mysql_database;
	private $__mysql_host;
	private $__mysql_user;
	private $__mysql_password;
	private $__sessions_table_name;

	/**
	 * \brief Create the session manager.
	 * \param[in] $in_mysql_database Name of the database that holds the tables "sessions".
	 * \param[in] $in_mysql_host Host name of the server that runs the MySql server.
	 * \param[in] $in_mysql_user MySql user.
	 * \param[in] $in_mysql_password Password for the MySql user.
	 * \param[in] $in_opt_table_name Name of the table used to store session's data.
	 */
	public function __construct($in_mysql_database, $in_mysql_host, $in_mysql_user, $in_mysql_password, $in_opt_table_name='sessions')
	{
		$this->__mysql_database      = $in_mysql_database;
		$this->__mysql_host          = $in_mysql_host;
		$this->__mysql_user          = $in_mysql_user;
		$this->__mysql_password      = $in_mysql_password;
		$this->__sessions_table_name = mysql_real_escape_string($in_opt_table_name);
	}

	/**
	 * \brief Open a session.
	 * \return Upon successful completion, the method returns the value TRUE.
	 *         Otherwise, the method returns the value FALSE.
	 * \exception Exception
	 *            If the method can not access the sessions' table, then the method throws an exception.
	 * \note See PHP documentation: http://php.net/manual/fr/function.session-set-save-handler.php\n
	 *       The documentation says that in case of error, the method should return FALSE.
	 *       In you do that, if the initialization fails, then no alert is thrown. It fails silently.
	 */
	public function open()
	{
		$this->__dbh = mysql_connect($this->__mysql_host, $this->__mysql_user, $this->__mysql_password);
		if (FALSE === $this->__dbh)
		{
			throw new Exception("Can not connect to the sessions' database.");
		}
		if (FALSE === mysql_select_db($this->__mysql_database, $this->__dbh))
		{
			throw new Exception("Can not select database " . $this->__mysql_database);
		}
		return TRUE;
    }

	/**
	 * \brief Close a session.
	 * \return Upon successful completion, the method returns the value TRUE.
	 *         Otherwise, the method returns the value FALSE.
	 */
	public function close()
	{
		return mysql_close($this->__dbh);
	}

	/**
	 * \brief Read a session's data.
	 * \param[in] $in_session_id The session ID.
	 * \return If data are available for the given session, then the method returns a non empty string.
	 *         Otherwise, the method returns an empty string.
	 * \note See http://php.net/manual/fr/function.session-set-save-handler.php\n
	 *       There is no way to signal an error.
	 */
	public function read($in_session_id)
	{
		$count         = null;
		$record        = null;
		$in_session_id = mysql_real_escape_string($in_session_id);
		$sql           = sprintf("SELECT `data` FROM `%s` WHERE id='%s'", $this->__sessions_table_name, $in_session_id);
		$result        = mysql_query($sql, $this->__dbh);
		
		// If an error occurred, we should signal an error...
		if (FALSE === $result) { return ''; }
		$count = mysql_num_rows($result);
		// If an error occurred, we should signal an error...
		if ((FALSE === $count) || (0 === $count)) { return ''; }
		$record = mysql_fetch_assoc($result);
		return $record['data'];
    }

	/**
	 * \brief Write a session.
	 * \param[in] $in_session_id The session ID.
	 * \param[in] $in_data The data to write.
     * \return Upon successful completion, the method returns the value TRUE.
	 *         Otherwise, the method returns the value FALSE.
	 * \note See http://php.net/manual/fr/function.session-set-save-handler.php\n
	 *       There is no way to signal an error.
     */
	public function write($in_session_id, $in_data)
	{
		$in_session_id = mysql_real_escape_string($in_session_id);
		$in_data       = mysql_real_escape_string($in_data);
		
		$sql = sprintf("REPLACE INTO `%s` SET id='%s', data='%s', `timestamp`=NOW();", $this->__sessions_table_name, $in_session_id, $in_data);
		return mysql_query($sql, $this->__dbh);
	}

    /**
     * \brief Destroy a session
     * \param[in] $in_session_id The session ID.
     * \return Upon successful completion, the method returns the value TRUE.
	 *         Otherwise, the method returns the value FALSE.
     */
	public function destroy($in_session_id)
	{
		$in_session_id = mysql_real_escape_string($in_session_id);
		$sql           = sprintf("DELETE FROM `%s` WHERE `id`='%s'", $this->__sessions_table_name, $in_session_id);
		return mysql_query($sql, $this->__dbh);
	}

	/**
	 * \brief Garbage Collector.
	 * \param[in] $in_max_life_time Session life time (in sec).
	 * \return Upon successful completion, the method returns the value TRUE.
	 *         Otherwise, the method returns the value FALSE.
	 * \note \code{.txt}
	 *       session.gc_divisor      100
	 *       session.gc_maxlifetime 1440
	 *       session.gc_probability    1
	 *       execution rate 1/100 (session.gc_probability/session.gc_divisor)
	 *       \endcode
	 */
	public function gc($in_max_life_time)
	{
		$in_max_life_time = mysql_real_escape_string($in_max_life_time);
        $sql = sprintf("DELETE FROM `%s` WHERE `timestamp` < DATE_SUB(NOW(), INTERVAL %s SECOND)", $this->__sessions_table_name, $in_max_life_time);
        return mysql_query($sql, $this->__dbh);
    }

}