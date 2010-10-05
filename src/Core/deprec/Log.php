<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 * @version     3.0.0 DEV
 * @category    Failnet
 * @package     core
 * @author      Damian Bushong
 * @copyright   (c) 2009 - 2010 -- Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/Failnet3
 *
 * @deprecated  since 3.0.0 - will be removed soon
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace Failnet\Core;
use Failnet as Root;

/**
 * Failnet - Logging handling class,
 * 	    Used as Failnet's logging handler.
 *
 *
 * @category    Failnet
 * @package     core
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/Failnet3
 */
class Log extends Base
{
	/**
	 * Queue of logs to be written
	 * @var array
	 */
	private $log = array();

	/**
	 * @ignore
	 */
	public function __construct()
	{
		Bot::core('db')->armQuery('logs', 'create', 'INSERT INTO Logs ( sender, location, type, event, log_time ) VALUES ( :sender, :where, :type, :data, :time )');
		Bot::core('db')->armQuery('logs', 'lastsaid', 'SELECT * FROM Logs WHERE preg_match()');
		//PDO::sqliteCreateFunction
		// @todo table schema

		$this->add('--- Starting Failnet ---', true);
	}

	/**
	 * Build a log message...
	 * @param string $log - The message/action to log
	 * @param string $who - Who sent the message?
	 * @param mixed $where - What was the recipient? A channel, or ourselves (as in, /msg)
	 * @param boolean $is_action - Is this an action?
	 * @return void
	 */
	public function log($log, $who, $where = false, $is_action = false)
	{
		if(preg_match('/^IDENTIFY (.*)/i', $log)) $log = 'IDENTIFY ***removed***';
		$log = (preg_match('/' . PHP_EOL . '(| )$/i', $log)) ? substr($log, 0, strlen($log) - 1) : $log;
		if(!$is_action)
		{
			$this->add(date('D m/d/Y - h:i:s A') . " - <{$who}" . (($where) ? '/' . $where : false) . "> {$log}");
		}
		else
		{
			$this->add(date('D m/d/Y - h:i:s A') . " - <{$who}" . (($where) ? '/' . $where : false) . "> *** {$who} {$log}");
		}
	}

	/**
	 * Add an entry to the queue of user logs...
	 * @param string $msg - The entry to add
	 * @param boolean $dump - Should we immediately dump all log entries into the log file after adding this to the quue?
	 * @return void
	 */
	public function add($msg, $dump = false)
	{
		$this->log[] = $msg;
		if($dump === true || sizeof($this->log) > failnet::core()->config('log_queue'))
		{
			$log_msg = '';
			$log_msg = implode(PHP_EOL, $this->log). PHP_EOL;
			$this->log = array();
			$this->write('user', time(), $log_msg);
		}
	}

	/**
	 * Directly add an entry to the logs.  Useful for if we want to write to the error logs. ;)
	 * @param string $type - The type of log to write to
	 * @param integer $time - The current UNIX timestamp
	 * @param string $msg - The message to write
	 * @return boolean - Whether the write was successful or not.
	 */
	public function write($type, $time, $msg)
	{
		return file_put_contents(FAILNET . "logs/{$type}_log_" . date('m-d-Y', $time) . '.log', $msg, FILE_APPEND | LOCK_EX);
	}

	/**
	 * Nuke the log file!
	 * @param string $type - The type of log file to remove
	 * @param integer $time - The timestamp for the day of the log file
	 * @return boolean - Was the delete successful?
	 */
	public function wipe($type, $time)
	{
		return @unlink(FAILNET . "logs/{$type}_log_" . date('m-d-Y', $time) . '.log');
	}
}