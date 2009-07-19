<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 *	Script info:
 * Version:		2.0.0
 * SVN ID:		$Id$
 * Copyright:	(c) 2009 - Obsidian
 * License:		http://opensource.org/licenses/gpl-2.0.php  |  GNU Public License v2
 *
 *===================================================================
 * 
 */

// @todo Rewrite with plugin!
// @todo Delete user
// @todo Delete hostmasks from access list
// @todo Add hostmask to access list
// @todo View access list for a user
// @todo Link users?

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
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
 * @ignore
 */
if(!defined('IN_FAILNET')) exit(1);


/**
 * Failnet - User authorization handling class,
 * 		Used as Failnet's authorization handler. 
 * 
 * 
 * @author Obsidian
 * @copyright (c) 2009 - Obsidian
 * @license http://opensource.org/licenses/gpl-2.0.php | GNU Public License v2
 */
class failnet_auth extends failnet_common
{
	/**
	 * phpass hashing handler
	 * @var object
	 */
	public $hash;
	
	/**
	 * Specialized init function to allow class construction to be easier.
	 * @see includes/failnet_common#init()
	 * @return void
	 */
	public function init()
	{
		display('=== Loading Failnet password hashing system');
			$this->hash = new failnet_hash(8, false);
	}
	
	/**
	 * Attempt to authenticate a user..
	 * @param string $sender - The sender's nick.
	 * @param string $password - The password the sender specified.
	 * @return mixed - True if password is correct, false if password is wrong, NULL if no such user.
	 */
	public function auth($hostmask, $password)
	{
		// First, we want to parse the user's hostmask here.
		parse_hostmask($hostmask, $nick, $user, $host);
		
		// Now, let's do a query to grab the row for that user
		$this->failnet->sql('users', 'get')->execute(array(':nick' => $nick));
		$result = $this->failnet->sql('users', 'get')->fetch(PDO::FETCH_ASSOC); 
		
		// No such user?  Derr...
		if(!$result)
			return NULL;
		
		// Let's check that password now...
		if($this->hash->check($password, $result['hash']))
		{
			// Success!  We need to just add a row to the sessions table now so that the login persists.
			return $this->failnet->sql('sessions', 'create')->execute(array(':key' => $this->failnet->unique_id(), ':user' => $result['user_id'], ':time' => time(), ':hostmask' => $hostmask));
		}
		
		// FAIL!  NOW GIT OUT OF MAH KITCHEN!
		return false;
	}

	/**
	 * Adds a hostmask to 
	 * @param $sender
	 * @param $hostmask
	 * @return mixed - Boolean true on success, false on invalid password, NULL on no such user. 
	 */
	public function add_access($sender, $hostmask, $password)
	{ // @todo Rewrite for PDO!
		foreach ($this->users as &$user)
		{
			if ($user['nick'] == strtolower($sender))
			{
				// Check password first..
				if ($this->hash->check($password, $user['hash']))
				{
					$user['hosts'][] = $hostmask; 
					$user['regex'] = hostmasks_to_regex($user['hosts']);

					// We need to push the new hostmask entry to the users DB file now...
					$list = file(FAILNET_ROOT . 'data/users', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
					foreach($list as &$item)
					{
						$item = explode('::', rtrim($item));
						if($item[0] == $sender)
							$item[] = $hostmask;
						$item = implode('::', rtrim($item));
					}

					// New that we've got it current...we need to write it back to the users DB file.  
					// We want to OVERWRITE in this case, also.
					file_put_contents(FAILNET_ROOT . 'data/users', implode(PHP_EOL, $list));
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		return NULL;
	}
	
	/**
	 * Looks up the authorization level for a certain user...
	 * @param string $nick - The user to check for.
	 * @param string $hostmask - The hostmask for the user we're checking, if we want to use access lists for this.
	 * @return mixed - Always returns 100 if boolean false is used as the authlevel, integer for the authlevel if in the access list or logged in, or false if the user isn't logged in/does not exist.
	 */
	public function authlevel($nick, $hostmask = false)
	{	// @todo Check if this works.  o_O
		if($nick === false)
			return 100;

		if(!empty($hostmask))	
			parse_hostmask($hostmask, $nick, $user, $host);

		if(empty($hostmask))
		{
			$sql = $this->failnet->db->query('SELECT u.authlevel, s.login_time
				FROM sessions s, users u 
				WHERE u.user_id = s.user_id
					AND LOWER(u.nick) = LOWER(' . $this->failnet->db->quote($nick) . ') 
				ORDER BY s.login_time DESC');
			
			// Do we have a logged in user with that nick?
			$result = $sql->fetch(PDO::FETCH_ASSOC);
			if(!$result)
				return false;
			
			return $result['authlevel'];
		}
		else
		{
			$sql = $this->failnet->db->query('SELECT u.authlevel
				FROM access a, users u 
				WHERE u.user_id = a.user_id
					AND LOWER(a.hostmask) = LOWER(' . $this->failnet->db->quote($hostmask) . ')');
			
			// Do we have a user with that hostmask assigned or not?
			$result = $sql->fetch(PDO::FETCH_ASSOC);
			if(!$result)
				return false;
			
			return $result['authlevel'];
		}
	}
	
	/**
	 * Add a user to the users database
	 * @param string $nick - Who should we set this for?
	 * @param string $password - The new password to use (will be stored as a hash)
	 * @param integer $authlevel - The authorization level to give to the user
	 * @return boolean - False if user already exists, true if successful.
	 */
	public function adduser($nick, $password, $authlevel = 0)
	{
		$user_exists = $this->failnet->db->query('SELECT COUNT(*) FROM users WHERE nick = ' . $this->failnet->db->quote($nick))->fetchColumn();
		if(!$user_exists)
		{
			return $this->failnet->sql('users', 'create')->execute(array(':nick' => $nick, ':authlevel' => $authlevel, ':hash' => $this->hash->hash($password)));
		}
		else
		{
			return false;
		}
	}
}

?>