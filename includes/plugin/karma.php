<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC bot
 *-------------------------------------------------------------------
 *	Script info:
 * Version:		2.0.0 Alpha 1
 * Copyright:	(c) 2009 - Failnet Project
 * License:		GNU General Public License - Version 2
 *
 *===================================================================
 * 
 */

/**
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
 */
 

/**
 * Failnet - Karma plugin,
 * 		This allows users to increase or decrease something's karma, and find out its karma. 
 * 
 *
 * @package plugins
 * @author Obsidian
 * @copyright (c) 2009 - Failnet Project
 * @license GNU General Public License - Version 2
 */
class failnet_plugin_karma extends failnet_plugin_common
{
	public function cmd_privmsg()
	{
		// Check for karma changes first
		$text = $this->event->get_arg('text');
		if($this->failnet->karma->check_word($text))
		{
			$term = strtolower(trim($text));
			$karma_type = substr($term, -2, 2);
			if($karma_type == '++')
			{
				$results = $this->failnet->karma->set_karma(substr($term, 0, strlen($term) - 2), failnet_karma::KARMA_INCREASE);
				return;
			}
			elseif($karma_type == '--')
			{
				$results = $this->failnet->karma->set_karma(substr($term, 0, strlen($term) - 2), failnet_karma::KARMA_DECREASE);
				return;
			}
		}

		// Process the command
		if(!$this->prefix($text))
			return;

		$cmd = $this->purify($text);
		$sender = $this->event->nick;
		switch ($cmd)
		{
			case 'karma':
				// Make sure we're getting the karma for SOMETHING.
				if(empty($text))
				{
					$this->call_privmsg($this->event->source(), $this->event->nick . ': You know you DO need to specify something, right?');
					return;
				}

				// Okay, let's get that karma.
				$term = strtolower(trim($text));
				if($this->failnet->karma->check_word($text))
				{
					return;
				}

				$karma = $this->failnet->karma->get_karma($term);
				if(!is_int($karma))
				{
					$this->call_privmsg($this->event->source(), $karma);
				}
				else
				{
					$this->call_privmsg($this->event->source(), 'They have a karma of ' . $karma . '.');
				}
			break;
		}
	}
}

?>