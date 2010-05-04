<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 * @version		2.1.0 DEV
 * @category	Failnet
 * @package		core
 * @author		Failnet Project
 * @copyright	(c) 2009 - 2010 -- Failnet Project
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @link		http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
 *
 *===================================================================
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://opensource.org/licenses/gpl-2.0.php>.
 *
 */


/**
 * Failnet - Exception class,
 * 		Extension of the default Exception class, adapted to suit Failnet's needs.
 *
 *
 * @category	Failnet
 * @package		core
 * @author		Failnet Project
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @link		http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
 */
class failnet_exception extends Exception
{
	private $translations = array();

	const ERR_NO_CONFIG = 1;
	const ERR_INVALID_PREP_QUERY = 2;

	/**
	 * Exception setup method, loads the error messages up for translation and also performs additional setup if necessary
	 * @return void
	 */
	public function setup()
	{
		$this->translations = array(
			self::ERR_NO_CONFIG => 'Specified Failnet configuration file not found',
			self::ERR_INVALID_PREP_QUERY => 'The specified prepared PDO query was not found',
		);
		// if we extend this class and want to define additional exception messages
		if(method_exists($this, 'extraSetup'))
			$this->extraSetup();
	}

	/**
	 * Error translation method, takes them pesky error numbers and gives you something you can actually use!
	 * @return object returns itself (i.e. $this) for use with the method Exception::__toString()
	 */
	public function translate()
	{
		if(!sizeof($this->translations))
			$this->setup();

		if(isset($this->code))
			$message = $this->code;
		$this->code = (int) $this->message;
		$this->message = (isset($message)) ? sprintf($this->translations[$this->message], $message) : $this->translations[$message];

		// We return $this so that one may make use of Exception::__toString() directly after calling this method
		// so, pretty much... echo failnet_exception::translate() should work nicely
		return $this;
	}
}
