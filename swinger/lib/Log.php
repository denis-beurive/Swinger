<?php

/**
 * \file Log.php
 * This file implements the LOG's writer.
 */

/**
 * \class Log
 * \brief This class implements the LOG writer. The writer inserts messages into the LOG file.
 *
 * A message is made of the following elements:
 * \code{.txt}
 * Date Session Level LinearizationFlag Message
 * \endcode
 * With:
 * <ul>
 *     <li>Date: YYYYMMDDHHMMSS (YearMonthDayHourMinuteSecond)</li>
 *     <li>Session: a tag that is used to group messages.</li>
 *     <li>Level: the message's level. It can be: "D" (for DEBUG), "I" (for INFO), "W" (for WARNING), "E" (for ERROR), "F" (for FATAL).</li>
 *     <li>LinearizationFlag: indicate whether the message is linearized or not. It can be: "R" (for "ROW" - the message has not been linearized) or "L" (for "LINEARIZED")</li>
 *     <li>Message: the massage.</li>
 * </ul>
 * For example:
 * \code{.txt}
 * 20130717120656 tCNO I R Starting with PID 499
 * 20130717120656 tCNO E L A%20provisioning%20error%20occurred%20on%20the%20VOIP%20flow.%0ANumber%3A0123456789%0AId%3A37
 * \endcode
 * The session is very useful when you analyse the LOG's messages (probably because you want to diagnose a problem).
 * You can use "grep" (eventually with "awk") in order to easily isolate some lines of LOG.
 * This LOG writer presents a functionality you may not find in other frameworks: message's linearization.\n
 * Parsing a LOG file which all messages are single lines is very easy. You can use commonly used tools such as "grep" or "awk".\n
 * But parsing a LOG file which messages that may span over multiple line may be impossible (unless you enforce rules and you develop a specific parser).\n
 * Linearization ensures that all messages are single lines.\n
 * To do that the writer applies the following transformation to the message: rawurlencode($message).\n
 * Linearization can be activated "globally" (for all messages) or for specific messages (when you know that a specific message may span over multiple lines).
 * For example:
 * \code{.php}
 * Log::setLevel(Log::INFO);
 * Log::info("Starting with PID " . getmypid());
 * \endcode
 * Will produce:
 * \code{.txt}
 * 20130717115903 1M5k I R Starting with PID 483
 * \endcode
 * For example (please notice the "\n"):
 * \code{.php}
 * Log::setLevel(Log::INFO);
 * Log::info("Starting with PID " . getmypid());
 * Log::error("A provisioning error occurred on the VOIP flow.\nNumber:0123456789\nId:37");
 * \endcode
 * Will produce:
 * \code{.txt}
 * 20130717120656 tCNO I R Starting with PID 499
 * 20130717120656 tCNO E L A%20provisioning%20error%20occurred%20on%20the%20VOIP%20flow.%0ANumber%3A0123456789%0AId%3A37
 * \endcode
 * If you need to parse a line of LOG you should use the method Log::parse($in_line);
 * See script "log_parser.php" for an example.
 */
class Log
{
	const FATAL                 = 0;
	const ERROR                 = 1;
	const WARNING               = 2;
	const INFO                  = 3;
	const DEBUG                 = 4;
	const WINDOWS               = 0;
	const UNIX                  = 1;
	private static $__level     = self::WARNING;
	private static $__file      = null;
	private static $__linearize = TRUE;
	private static $__session   = null;
	private static $__new_line  = "\n";
	
	/**
	 * \brief Set the LOG's level.
	 * \param[in] $in_level The LOG's level.
	 *            <ul>
	 *                <li>Log::DEBUG</li>
	 *                <li>Log::INFO</li>
	 *                <li>Log::WARNING</li>
	 *                <li>Log::ERROR</li>
	 *                <li>Log::FATAL</li>
	 *            </ul>
	 * \note If you set the level to WARNING, then:
	 *       <ul>
	 *           <li>A message which level is DEBUG or INFO will not be written in the LOG file.</li>
	 *           <li>A message which level is WARNING, ERROR or FATAL will be written in the LOG file.</li>
	 *       </ul>
	 */
	public static function setLevel($in_level) { self::$__level = $in_level; }
	
	/**
	 * \brief Set path to the LOG file.
	 * \param[in] $in_path Path to the LOG file.
	 */
	public static function setLogFile($in_path) { self::$__file = $in_path; }
	
	/**
	 * \brief Get the path to the LOG file.
	 * \return The method returns the path to the LOG file.
	 */
	public static function getLogFile() { return self::$__file; }
	
	/**
	 * \brief Set the string that represents the line separator, for a given OS.
	 * \param[in] $in_os Value that represents the OS. Value can be:
	 *            <ul>
	 *               <li>Log::WINDOWS: line separator will be "\r\n".</li>
	 *               <li>Log::UNIX: line separator will be "\n".</li>
	 *            </ul>
	 * \note By default the line separator is "\n" (that is, for UNIX).
	 */
	public static function setNewLineFor($in_os)
	{ 
		if (self::WINDOWS == $in_os) { self::$__new_line = "\r\n"; return; }
		if (self::UNIX == $in_os)    { self::$__new_line = "\n";   return; }
	}
	
	/**
	 * \brief Deactivate the linearization.
	 *        When the linearization is activated, the LOG writer will ensure that all messages is represented by a single line.
	 * \note By default linearization is activated.
	 */
	public static function deactivateLinearization() { self::$__linearize = FALSE; }

	/**
	 * \brief "Globally" activate the linearization.
	 *        When the linearization is activated, the LOG writer will ensure that all message is represented by a single line.
	 * \note By default linearization is activated.
	 * \note Linearization can be activated only for a specific message (when you know that a specific message may span over multiple lines).
	 *       See the follwong methods:
	 *       <ul>
	 *           <li>debug()</li>
	 *           <li>warning()</li>
	 *           <li>info()</li>
	 *           <li>error()</li>
	 *           <li>fatal()</li>
	 *       </ul>
	 */
	public static function activateLinearization() { self::$__linearize = TRUE; }
	
	/**
	 * \brief Set LOG' session.
	 *        A session is a string used to group messages.
	 *        For example, you can set a session for all messages of a script's instance.
	 * \param[in] $in_session The LOG's session.
	 * \note The session is very useful when you analyse the LOG file (probably because you want to diagnose a problem).
	 *       You can use "grep" (eventually with "awk") in order to easily isolate some messages.
	 */
	public static function setSession($in_session) { self::$__session = $in_session; }
	
	/**
	 * \brief Get the LOG' session.
	 *        A session is a string used to group lines of LOGs.
	 *        For example, you can set a session for all lines of LOGs of a script's instance.
	 * \return The method returns the LOG' session.
	 * \note The session is very useful when you analyse the LOG file (probably because you want to diagnose a problem).
	 *       You can use "grep" (eventually with "awk") in order to easily isolate some messages.
	 */
	public static function getSession() { return self::$__session; }
	
	/**
	 * \brief Write a DEBUG message in the LOG file.
	 * \param[in] $in_message Message to write.
	 * \param[in] $in_linearize Should we linearize the message?
	 *            When the linearization is activated, the LOG writer will ensure that the message is represented by a single line.
	 *            To do that the writer applies the following transformation to the message: rawurlencode($message).
	 */
	public static function debug($in_message, $in_linearize=FALSE)
	{
		if (self::$__level < self::DEBUG) { return; }
		$l = 'R';
		
		if ((self::$__linearize || $in_linearize) && (! self::__isSingleLine($in_message)))
		{
			$l = 'L';
			$in_message = self::__linearize($in_message);
		}
		
		file_put_contents(	self::$__file,
							sprintf('%s %s D %s %s%s', self::__now(), self::$__session, $l, $in_message, self::$__new_line),
							FILE_APPEND);
	}
	
	/**
	 * \brief Write a INFO message in the LOG file.
	 * \param[in] $in_message Message to write.
	 * \param[in] $in_linearize Should we linearize the message?
	 *            When the linearization is activated, the LOG writer will ensure that the message is represented by a single line.
	 *            To do that the writer applies the following transformation to the message: rawurlencode($message).
	 */
	public static function info($in_message, $in_linearize=FALSE)
	{
		if (self::$__level < self::INFO) { return; }
		$l = 'R';
		
		if ((self::$__linearize || $in_linearize) && (! self::__isSingleLine($in_message)))
		{
			$l = 'L';
			$in_message = self::__linearize($in_message);
		}
		
		file_put_contents(	self::$__file,
							sprintf('%s %s I %s %s%s', self::__now(), self::$__session, $l, $in_message, self::$__new_line),
							FILE_APPEND);		
	}
	
	/**
	 * \brief Write a WARNING message in the LOG file.
	 * \param[in] $in_message Message to write.
	 * \param[in] $in_linearize Should we linearize the message?
	 *            When the linearization is activated, the LOG writer will ensure that the message is represented by a single line.
	 *            To do that the writer applies the following transformation to the message: rawurlencode($message).
	 */
	public static function warning($in_message, $in_linearize=FALSE)
	{
		if (self::$__level < self::WARNING) { return; }
		$l = 'R';
		
		if ((self::$__linearize || $in_linearize) && (! self::__isSingleLine($in_message)))
		{
			$l = 'L';
			$in_message = self::__linearize($in_message);
		}
		
		file_put_contents(	self::$__file,
							sprintf('%s %s W %s %s%s', self::__now(), self::$__session, $l, $in_message, self::$__new_line),
							FILE_APPEND);		
	}
	
	/**
	 * \brief Write a ERROR message in the LOG file.
	 * \param[in] $in_message Message to write.
	 * \param[in] $in_linearize Should we linearize the message?
	 *            When the linearization is activated, the LOG writer will ensure that the message is represented by a single line.
	 *            To do that the writer applies the following transformation to the message: rawurlencode($message).
	 */
	public static function error($in_message, $in_linearize=FALSE)
	{
		if (self::$__level < self::ERROR) { return; }
		$l = 'R';
		
		if ((self::$__linearize || $in_linearize) && (! self::__isSingleLine($in_message)))
		{
			$l = 'L';
			$in_message = self::__linearize($in_message);
		}
		
		file_put_contents(	self::$__file,
							sprintf('%s %s E %s %s%s', self::__now(), self::$__session, $l, $in_message, self::$__new_line),
							FILE_APPEND);		
	}
	
	/**
	 * \brief Write a FATAL message in the LOG file.
	 * \param[in] $in_message Message to write.
	 * \param[in] $in_linearize Should we linearize the message?
	 *            When the linearization is activated, the LOG writer will ensure that the message is represented by a single line.
	 *            To do that the writer applies the following transformation to the message: rawurlencode($message).
	 */
	public static function fatal($in_message, $in_linearize=FALSE)
	{
		$l = 'R';
		
		if ((self::$__linearize || $in_linearize) && (! self::__isSingleLine($in_message)))
		{
			$l = 'L';
			$in_message = self::__linearize($in_message);
		}
		
		file_put_contents(	self::$__file,
							sprintf('%s %s F %s %s%s', self::__now(), self::$__session, $l, $in_message, self::$__new_line),
							FILE_APPEND);		
	}
	
	/**
	 * \brief Delinearize a string.
	 * \param[in] $in_text String to delinearize.
	 * \return The method return a string. This string may span upon multiple lines.
	 */
	public static function delinearize($in_text) { return rawurldecode($in_text); }
	
	/**
	 * \brief Parse a line of LOG and return an associative array that contains data extracted from the given line.
	 * \param[in] $in_line Line to parse.
	 * \return The method returns an associative array that contains the following keys:
	 *         <ul>
	 *             <li>timestamp: the timestamp in the form "YYYYMMDDHHMMSS".</li>
	 *             <li>datetime: an instance of class DateTime.</li>
	 *             <li>year: the yeau ("YYYY").</li>
	 *             <li>month: the month ("MM").</li>
	 *             <li>day: the day ("DD").</li>
	 *             <li>hour: the hour ("HH").</li>
	 *             <li>minute: the minute ("MM").</li>
	 *             <li>seconde: the second ("SS").</li>
	 *             <li>session: the session.</li>
	 *             <li>level: the message's level ("D", "I", "W", "E" or "F").</li>
	 *             <li>encoding: the message's format ("R" or "L").</li>
	 *             <li>message: the message (you may need to call Log::delinearize(), if the message's format is "L").</li>
	 *         </ul>
	 *         If the line os not valid, then the method returns the value FALSE.
	 * \note See script "log_parser.php" for an example.
	 */
	public static function parse($in_line)
	{
		$result   = array();
		$elements = explode(' ', $in_line, 5);
		
		// Check the number of elements in the line.
		if (count($elements) < 4)  { return FALSE; }
		if (count($elements) == 4) { $elements[] = ''; }
		
		// Check the date.
		$matches = array();
		if (! preg_match('/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', $elements[0], $matches)) { return FALSE; }
		$year   = $matches[1];
		$month  = $matches[2];
		$day    = $matches[3];
		$hour   = $matches[4];
		$minute = $matches[5];
		$second = $matches[6];
		
		$date = new DateTime();
		$date->setDate($year, $month, $day);
		$date->setTime($hour, $minute, $second);
		
		// Check the encoding.
		if (($elements[3] != 'R') && ($elements[3] != 'L')) { return FALSE; }
		
		$result['timestamp'] = $elements[0];
		$result['datetime']  = $date;
		$result['year']      = $year;
		$result['month']     = $month;
		$result['day']       = $day;
		$result['hour']      = $hour;
		$result['minute']    = $minute;
		$result['second']    = $second;
		$result['session']   = $elements[1];
		$result['level']     = $elements[2];
		$result['encoding']  = $elements[3];
		$result['message']   = $elements[4];
		
		return $result;
	}
	
	/**
	 * \brief Return the current date in the form "YYYYMMDDHHMMSS".
	 * \return The method returns the current date.
	 */
	private static function __now() { return strftime('%Y%m%d%H%M%S'); }
	
	/**
	 * \brief Test whether a message is a single line or not.
	 * \param[in] $in_message Message to test.
	 * \return If the message is a single line, then the method returns the value TRUE.
	 *         Otherwise, it returns the value FALSE.
	 */
	private static function __isSingleLine($in_message) { return 0 === preg_match('/\n|\r/', $in_message); }
	
	/**
	 * \brief Linearise a string.
	 * \param[in] $in_text String to linearize.
	 * \return The method return a string. This string is a single line.
	 */	
	private static function __linearize($in_text) { return rawurlencode($in_text); }
	
}

?>