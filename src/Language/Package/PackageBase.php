<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 * @version     3.0.0 DEV
 * @category    Failnet
 * @package     language
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

namespace Failnet\Language\Package;
use Failnet\Bot as Bot;
use Failnet\Lib as Lib;
use Failnet\Language as Language;

/**
 * Failnet - Language package base class,
 *      Defines common methods and properties for langauge packages to use.
 *
 *
 * @category    Failnet
 * @package     language
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/Failnet3
 */
class PackageBase implements PackageInterface
{
	protected $entries = array();

	/**
	 * Build the JSON string for our language entries.
	 * @return mixed - Either the JSON string for our language entries, or false if we have no entries in this package.
	 */
	public function buildJSON()
	{
		if(!$this->entries)
			return false;
		return Lib\JSON::encode($this->entries);
	}

	/**
	 * Countable methods
	 */

	/**
	 * Get the number of entries in this language package.
	 * @return integer - The number of entries.
	 */
	public function count()
	{
		return sizeof($this->entries);
	}

	/**
	 * Iterator methods
	 */

	/**
	 * Iterator method, rewinds the array back to the first element.
	 * @return void
	 */
	public function rewind()
	{
		return reset($this->entries);
	}

	/**
	 * Iterator method, returns the key of the current element
	 * @return scalar - The key of the current element.
	 */
	public function key()
	{
		return key($this->entries);
	}

	/**
	 * Iterator method, checks to see if the current position is valid.
	 * @return boolean - Whether or not the current array position is valid.
	 */
	public function valid()
	{
		return (!is_null(key($this->entries)));
	}

	/**
	 * Iterator method, gets the current element
	 * @return Failnet\Lib\UserInterface - The current session of focus.
	 */
	public function current()
	{
		return current($this->entries);
	}

	/**
	 * Iterator method, moves to the next session available.
	 * @return void
	 */
	public function next()
	{
		next($this->entries);
	}
}
