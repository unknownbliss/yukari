<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 * @version     3.0.0 DEV
 * @category    Failnet
 * @package     event
 * @author      Damian Bushong
 * @copyright   (c) 2009 - 2010 -- Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/Failnet3
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace Failnet\Event\IRC;
use Failnet as Root;
use Failnet\Event as Event;

/**
 * Failnet - IRC Event base class,
 * 	    Base class that all IRC events must extend.
 *
 *
 * @category    Failnet
 * @package     event
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/Failnet3
 */
abstract class IRCBase extends Event\EventBase implements Event\EventInterface
{
	/**
	 * @var Failnet\Lib\Hostmask - The hostmask for the originating server or user
	 */
	public $origin;

	/**
	 * @var string - The raw buffer of the event
	 */
	public $buffer = '';

	/**
	 * @var string - The channel that originated the event, if event was recieved from a channel perspective.
	 */
	public $channel = '';

	/**
	 * Get the "originator" of this event.
	 * @return Failnet\Lib\Hostmask - The hostmask object for the event's originator.
	 */
	public function getSource()
	{
		return $this->origin;
	}

	/**
	 * Get the raw buffer
	 * @return string - Raw IRC buffer for the event
	 */
	public function getBuffer()
	{
		return $this->buffer;
	}

	abstract public function buildCommand();

	/**
	 * Check to see if the event was recieved from a channel.
	 * @return boolean - True if event is from a channel, false if otherwise.
	 */
	public function fromChannel()
	{
		if(isset($this->channel) && in_array($this->channel[0], array('#', '&'))) // @todo update with all known channel prefixes
			return true;
		return false;
	}
}