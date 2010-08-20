<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 *	Script info:
 * Version:		3.0.0 DEV
 * Copyright:	(c) 2009 - 2010 -- Damian Bushong
 * License:		MIT License
 *
 *===================================================================
 *
 */

/**
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */


/**
 * Failnet - Connection status detection plugin,
 * 		Used to ping the server periodically to ensure that the client connection has not been dropped.
 *
 *
 * @package plugins
 * @author Obsidian
 * @copyright (c) 2009 - 2010 -- Damian Bushong
 * @license MIT License
 */
class failnet_plugin_ping extends failnet_plugin_common
{
	/**
	 * Timestamp for the last instance in which an event was received
	 *
	 * @var int
	 */
	private $last_event;

	/**
	* Timestamp for the last instance in which a PING was sent
	*
	* @var int
	*/
	private $last_ping;

	/**
	* Initialize event timestamps upon connecting to the server.
	*
	* @return void
	*/
	public function call_connect()
	{
		$this->last_event = time();
		$this->last_ping = NULL;
	}

	/**
	* Updates the timestamp since the last received event when a new event
	* arrives.
	*
	* @return void
	*/
	public function pre_event()
	{
		$this->last_event = time();
	}

	/**
	* Clears the ping time if a reply is received.
	*
	* @return void
	*/
	public function cmd_pingreply()
	{
		$this->last_ping = NULL;
	}

	/**
	* Performs a self ping if the event threshold has been exceeded or
	* issues a termination command if the ping theshold has been exceeded.
	*
	* @return void
	*/
	public function tick()
	{
		$time = time();

		if(!empty($this->last_ping) && $time - $this->last_ping > $this->failnet->config('ping_timeout'))
		{
			$this->failnet->ui->ui_system('-!- Ping timeout, restarting Failnet');
			$this->failnet->log->add('--- Ping timeout, restarting Failnet ---');
			$this->failnet->terminate(true);
		}
		elseif($this->last_event && (($time - $this->last_event) > $this->failnet->config('ping_wait')))
		{
			$this->last_ping = time();
			$this->failnet->ui->ui_system('Pinging server to maintain connection...');
			$this->call_ping($this->failnet->config('nick'), $this->last_ping);
		}
	}
}