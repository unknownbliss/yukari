<?php
/**
 *
 *===================================================================
 *
 *  Yukari
 *-------------------------------------------------------------------
 * @category    Yukari
 * @package     lib
 * @author      Damian Bushong
 * @copyright   (c) 2009 - 2011 -- Damian Bushong
 * @license     MIT License
 * @link        https://github.com/damianb/yukari
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace Yukari\Connection;

/**
 * Yukari - Hostmask class,
 * 	    Used as an object for housing hostmask data
 *
 *
 * @category    Yukari
 * @package     lib
 * @author      Damian Bushong
 * @license     MIT License
 * @link        https://github.com/damianb/yukari
 */
class Hostmask implements \ArrayAccess
{
	/**
	 * @var string - The host of the hostmask
	 */
	protected $host = '';

	/**
	 * @var string - The nick of the hostmask
	 */
	protected $nick = '';

	/**
	 * @var string - The username of the hostmask
	 */
	protected $username = '';

	/**
	 * Get a new instance of the hostmask object.
	 * @return \Yukari\Connection\Hostmask - Provides a fluent interface.
	 */
	public static function newInstance()
	{
		return new static();
	}

	/**
	 * Get the host for the current hostmask.
	 * @return string - The current host for the hostmask.
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * Set the host for the current hostmask
	 * @param string $host - The host to set.
	 * @return \Yukari\Connection\Hostmask - Provides a fluent interface.
	 */
	public function setHost($host)
	{
		$this->host = (string) $host;
		return $this;
	}

	/**
	 * Get the username for the current hostmask.
	 * @return string - The current username for the hostmask.
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set the username for the current hostmask
	 * @param string $username - The host to set.
	 * @return \Yukari\Connection\Hostmask - Provides a fluent interface.
	 */
	public function setUsername($username)
	{
		$this->username = (string) $username;
		return $this;
	}

	/**
	 * Get the nick for the current hostmask.
	 * @return string - The current nick for the hostmask.
	 */
	public function getNick()
	{
		return $this->nick;
	}

	/**
	 * Set the nick for the current hostmask
	 * @param string $nick - The nick to set.
	 * @return \Yukari\Connection\Hostmask - Provides a fluent interface.
	 */
	public function setNick($nick)
	{
		$this->nick = (string) $nick;
		return $this;
	}

	/**
	 * Parses a string containing the entire hostmask into a new instance of this class.
	 * @param string $hostmask - Entire hostmask including the nick, username, and host components
	 * @return \Yukari\Connection\Hostmask - New object instance populated with the data parsed from the provided hostmask string
	 */
	public static function load($hostmask)
	{
		if(!preg_match('/^([^!@]+)!(?:[ni]=)?([^@]+)@([^ ]+)/', $hostmask, $match))
			throw new \LogicException(sprintf('Invalid hostmask "%s" specified', $hostmask));

		list(, $nick, $username, $host) = $match;
		$self = new static();
		$self->setHost($host)->setUsername($username)->setNick($nick);
		return $self;
	}

	/**
	 * Returns the hostmask for the originating server or user.
	 * @return string - The full hostmask desired
	 */
	public function __toString()
	{
		return $this->nick . '!' . $this->username . '@' . $this->host;
	}

	/**
	 * Checks for a match against this hostmask
	 * @param string $regex - The regex pattern to check.
	 * @return boolean - Does this hostmask match our pattern?
	 */
	public function checkMatch($regex)
	{
		return (preg_match('#^' . str_replace('*', '.*', $regex) . '$#i', (string) $this) > 0) ? true : false;
	}

	/**
	 * ArrayAccess stuff
	 */

	/**
	 * Check if an "array" offset exists in this object.
	 * @param mixed $offset - The offset to check.
	 * @return boolean - Does anything exist for this offset?
	 */
	public function offsetExists($offset)
	{
		$dispatcher->trigger(\Yukari\Event\Instance::newEvent('ui.message.debug')
			->setDataPoint('message', sprintf('Deprecation notice: Using ArrayAccess to access offset "%1$s" in \\Yukari\\Connection\\Hostmask', $offset)));
		return property_exists($this, $offset);
	}

	/**
	 * Get an "array" offset for this object.
	 * @param mixed $offset - The offset to grab from.
	 * @return mixed - The value of the offset, or null if the offset does not exist.
	 */
	public function offsetGet($offset)
	{
		$dispatcher->trigger(\Yukari\Event\Instance::newEvent('ui.message.debug')
			->setDataPoint('message', sprintf('Deprecation notice: Using ArrayAccess to access offset "%1$s" in \\Yukari\\Connection\\Hostmask', $offset)));
		return property_exists($this, $offset) ? $this->$offset : NULL;
	}

	/**
	 * Set an "array" offset to a certain value, if the offset exists
	 * @param mixed $offset - The offset to set.
	 * @param mixed $value - The value to set to the offset.
	 * @return void
	 *
	 * @throws \RuntimeException
	 */
	public function offsetSet($offset, $value)
	{
		$dispatcher->trigger(\Yukari\Event\Instance::newEvent('ui.message.debug')
			->setDataPoint('message', sprintf('Deprecation notice: Using ArrayAccess to access offset "%1$s" in \\Yukari\\Connection\\Hostmask', $offset)));
		if(!property_exists($this, $offset))
			throw new \RuntimeException('Attempt to access an invalid property in a hostmask object failed');

		$this->$offset = $value;
	}

	/**
	 * Unset an "array" offset.
	 * @param mixed $offset - The offset to clear out.
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		$dispatcher->trigger(\Yukari\Event\Instance::newEvent('ui.message.debug')
			->setDataPoint('message', sprintf('Deprecation notice: Using ArrayAccess to access offset "%1$s" in \\Yukari\\Connection\\Hostmask', $offset)));
		$this->$offset = NULL;
	}
}