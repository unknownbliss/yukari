<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 * @version		3.0.0 DEV
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
 * Echos a message, and cleans out any extra NL's after the message.
 * 		Also will echo an array of messages properly as well.
 * @param mixed $message - The message or array of messages we want to echo to the terminal.
 * @return void
 * @deprecated since 2.1.0
 */
function display($message)
{
	if(is_array($message))
	{
		foreach($message as $line)
		{
			echo ((strrpos($line, PHP_EOL . PHP_EOL) !== false) ? substr($line, 0, strlen($line) - 1) : $line) . PHP_EOL;
		}
	}
	else
	{
		echo ((strrpos($message, PHP_EOL . PHP_EOL) !== false) ? substr($message, 0, strlen($message) - 1) : $message) . PHP_EOL;
	}
}

/**
 * Throws a fatal and non-recoverable error.
 * @param string $msg - The error message to use
 * @return void
 * @deprecated since 2.1.0
 */
function throw_fatal($msg)
{
	if(file_exists(FAILNET_ROOT . 'data/restart.inc'))
		unlink(FAILNET_ROOT . 'data/restart.inc');
	display('[Fatal Error] ' . $msg);
	display(dump_backtrace());
	sleep(7);
	exit(1);
}

/**
 * Error handler function for Failnet.  Modified from the phpBB 3.0.x msg_handler() function.
 * @param integer $errno - Level of the error encountered
 * @param string $msg_text - The error message recieved
 * @param string $errfile - The file that the error was encountered at
 * @param integer $errline - The line that the error was encountered at
 * @return mixed - If suppressed, nothing returned...if not handled, false.
 */
function failnetErrorHandler($errno, $msg_text, $errfile, $errline)
{
   // Do not display notices if we suppress them via @
   if (error_reporting() == 0)
	   return;

   // Strip the current directory from the offending file
   $errfile = (!empty($errfile)) ? substr(str_replace(array(__DIR__, '\\'), array('', '/'), $errfile), 1) : '';
   $error = 'in file ' . $errfile . ' on line ' . $errline . ': ' . $msg_text . PHP_EOL;
   $handled = false;

   switch ($errno)
   {
	   case E_NOTICE:
	   case E_STRICT:
	   case E_DEPRECATED:
	   case E_USER_NOTICE:
	   case E_USER_DEPRECATED:
		   $handled = true;
		   failnet::core('ui')->notice($error);
		   file_put_contents(FAILNET_ROOT . 'logs/error_log_' . date('m-d-Y', time()) . '.log', date('D m/d/Y - h:i:s A') . ' - [PHP Notice] ' . $error, FILE_APPEND | LOCK_EX);
	   break;

	   case E_WARNING:
	   case E_USER_WARNING:
		   $handled = true;
		   failnet::core('ui')->warning($error);
		   file_put_contents(FAILNET_ROOT . 'logs/error_log_' . date('m-d-Y', time()) . '.log', date('D m/d/Y - h:i:s A') . ' - [PHP Warning] ' . $error, FILE_APPEND | LOCK_EX);
	   break;

	   case E_ERROR:
	   case E_USER_ERROR:
		   $handled = true;
		   failnet::core('ui')->error($error);
		   file_put_contents(FAILNET_ROOT . 'logs/error_log_' . date('m-d-Y', time()) . '.log', date('D m/d/Y - h:i:s A') . ' - [PHP Error] ' . $error, FILE_APPEND | LOCK_EX);
	   break;
   }

   // Fatal error? DAI.
   if($errno === E_USER_ERROR)
	   failnet::core()->terminate(false);

   // If we notice an error not handled here we pass this back to PHP by returning false
   // This may not work for all php versions
   return ($handled) ? true : false;
}

/**
 * Return formatted string for filesizes
 * @param integer $bytes - The number of bytes to convert.
 * @return string - The filesize converted into KiB, MiB, or GiB.
 *
 * @author (c) 2007 phpBB Group
 */
function formatFilesize($bytes)
{
	if ($bytes >= pow(2, 40))
		return round($bytes / 1024 / 1024 / 1024 / 1024, 2) . ' TiB';
	if ($bytes >= pow(2, 30))
		return round($bytes / 1024 / 1024 / 1024, 2) . ' GiB';
	if ($bytes >= pow(2, 20))
		return round($bytes / 1024 / 1024, 2) . ' MiB';
	if ($bytes >= pow(2, 10))
		return round($bytes / 1024, 2) . ' KiB';
	return $bytes . ' B';
}

/**
 * Converts a given integer/timestamp into days, minutes and seconds
 * @param integer $time - The time/integer to calulate the values from
 * @param boolean $last_comma - Should we have a comma between the second to last item of the list and the last, if more than 3 items for time?
 * 									This WAS actually something of debate, for grammar reasons. :P
 * @return string
 */
function timespan($time, $last_comma = false)
{
	$return = array();

	$count = floor($time / 29030400);
	if ($count > 0)
	{
		$return[] = $count . (($count == 1) ? ' year' : ' years');
		$time %= 29030400;
	}

	$count = floor($time / 2419200);
	if ($count > 0)
	{
		$return[] = $count . (($count == 1) ? ' month' : ' months');
		$time %= 2419200;
	}

	$count = floor($time / 604800);
	if ($count > 0)
	{
		$return[] = $count . (($count == 1) ? ' week' : ' weeks');
		$time %= 604800;
	}

	$count = floor($time / 86400);
	if ($count > 0)
	{
		$return[] = $count . (($count == 1) ? ' day' : ' days');
		$time %= 86400;
	}

	$count = floor($time / 3600);
	if ($count > 0)
	{
		$return[] = $count . (($count == 1) ? ' hour' : ' hours');
		$time %= 3600;
	}

	$count = floor($time / 60);
	if ($count > 0)
	{
		$return[] = $count . (($count == 1) ? ' minute' : ' minutes');
		$time %= 60;
	}

	$bigtime = (sizeof($return) ? implode(', ', $return) : '');

	if(!$last_comma)
	{
		if ($time > 0 || count($return) <= 0)
			$bigtime .= (sizeof($return) ? ' and ' : '') . ($time > 0 ? $time : '0') . (($time == 1) ? ' second' : ' seconds');
	}
	else
	{
		if ($time > 0 || count($return) <= 0)
			$bigtime .= (sizeof($return) ? ((sizeof($return) > 1) ? ',' : '') . ' and ' : '') . ($time > 0 ? $time : '0') . (($time == 1) ? ' second' : ' seconds');
	}

	return $bigtime;
}

/**
 * Generate a backtrace and return it for use elsewhere.
 * @return array - The backtrace results.
 */
function dump_backtrace()
{
	$output = array();
	$backtrace = debug_backtrace();
	foreach ($backtrace as $number => $trace)
	{
		// We skip the first one, because it only shows this file/function
		if ($number == 0)
		{
			continue;
		}

		// Strip the current directory from path
		if (empty($trace['file']))
		{
			$trace['file'] = '';
		}
		else
		{
			$trace['file'] = str_replace(array(__DIR__, '\\'), array('', '/'), $trace['file']);
			$trace['file'] = substr($trace['file'], 1);
		}
		$args = array();

		// If include/require/include_once is not called, do not show arguments - they may contain sensible information
		if (!in_array($trace['function'], array('include', 'require', 'include_once')))
		{
			unset($trace['args']);
		}
		else
		{
			// Path...
			if (!empty($trace['args'][0]))
			{
				$argument = $trace['args'][0];
				$argument = str_replace(array(__DIR__, '\\'), array('', '/'), $argument);
				$argument = substr($argument, 1);
				$args[] = "'{$argument}'";
			}
		}

		$trace['class'] = (!isset($trace['class'])) ? '' : $trace['class'];
		$trace['type'] = (!isset($trace['type'])) ? '' : $trace['type'];

		$output[] = 'FILE: ' . $trace['file'];
		$output[] = 'LINE: ' . ((!empty($trace['line'])) ? $trace['line'] : '');
		$output[] = 'CALL: ' . $trace['class'] . $trace['type'] . $trace['function'] . '(' . ((sizeof($args)) ? implode(', ', $args) : '') . ')';
	}
	return $output;
}

/**
 * Deny function...
 * @return string - The deny message to use. :3
 */
function deny_message()
{
	$rand = rand(0, 9);
	switch($rand)
	{
		case 0:
		case 1:
			return 'No.';
		break;
		case 2:
		case 3:
			return 'Uhm, no.';
		break;
		case 4:
		case 5:
			return 'Hells no!';
			break;
		case 6:
		case 7:
		case 8:
			return 'HELL NOEHS!';
		break;
		case 9:
			return 'The number you are dialing is not available at this time.';
		break;
	}
}

/**
 * Are we directing this at our owner or ourself?
 * This is best to avoid humilation if we're using an agressive command.  ;)
 * @param string $user - The user to check.
 * @return boolean - Are we targeting the owner or ourself?
 */
function checkuser($user)
{
   if(preg_match('#' . preg_quote(failnet::core()->config('owner'), '#') . '|' . preg_quote(failnet::core()->config('nick'), '#') . '|self#i', $user))
	   return true;
   return false;
}

/**
* Return unique id
* @param string $extra additional entropy
* @return string - The unique ID
*
* @author (c) 2007 phpBB Group
*/
function unique_id($extra = 'c')
{
	static $dss_seeded = false;

	$rand_seed = failnet::core()->config('rand_seed');
	$last_rand_seed = failnet::core()->config('last_rand_seed');

	$val = md5($rand_seed . microtime());
	$rand_seed = md5($rand_seed . $val . $extra);

	if($dss_seeded !== true && ($last_rand_seed < time() - rand(1,10)))
	{
		failnet::core()->sql('config', 'update')->execute(array(':name' => 'rand_seed', ':value' => $rand_seed));
		failnet::core()->settings['rand_seed'] = $rand_seed;
		$last_rand_seed = time();
		failnet::core()->sql('config', 'update')->execute(array(':name' => 'last_rand_seed', ':value' => $last_rand_seed));
		failnet::core()->settings['last_rand_seed'] = $last_rand_seed;
		$dss_seeded = true;
	}

	return substr($val, 4, 16);
}

/**
 * Converts a delimited string of hostmasks into a regular expression that will match any hostmask in the original string.
 * @param array $list - Array of hostmasks
 * @return string - Regular expression
 *
 * @author Phergie Development Team {@link http://code.assembla.com/phergie/subversion/nodes}
 */
function hostmasks_to_regex($list)
{
	static $hmask_find, $hmask_repl;
	if(empty($hmask_find))
		$hmask_find = array('\\', '^', '$', '.', '[', ']', '|', '(', ')', '?', '+', '{', '}');
	if(empty($hmask_repl))
		$hmask_repl = array('\\\\', '\\^', '\\$', '\\.', '\\[', '\\]', '\\|', '\\(', '\\)', '\\?', '\\+', '\\{', '\\}');

	$patterns = array();

	foreach($list as $hostmask)
	{
		// Find out which chars are present in the config mask and exclude them from the regex match
		$excluded = '';
		if (strpos($hostmask, '!') !== false)
		{
			$excluded .= '!';
		}
		if (strpos($hostmask, '@') !== false)
		{
			$excluded .= '@';
		}

		// Escape regex meta characters
		$hostmask = str_replace($hmask_find, $hmask_repl, $hostmask);

		// Replace * so that they match correctly in a regex
		$patterns[] = str_replace('*', ($excluded === '' ? '.*' : '[^' . $excluded . ']*'), $hostmask);
	}

	return ('#^' . implode('|', $patterns) . '$#iS');
}

/**
 * Parses a IRC hostmask and sets nick, user and host bits.
 * @param string $hostmask - Hostmask to parse
 * @param string &$nick - Container for the nick
 * @param string &$user - Container for the username
 * @param string &$host - Container for the hostname
 * @return void
 *
 * @author Phergie Development Team {@link http://code.assembla.com/phergie/subversion/nodes}
 */
function parse_hostmask($hostmask, &$nick, &$user, &$host)
{
	if (preg_match('/^([^!@]+)!([^@]+)@(.*)$/', $hostmask, $match) > 0)
	{
		list(, $nick, $user, $host) = array_pad($match, 4, NULL);
	}
	else
	{
		$nick = NULL;
		$user = NULL;
		$host = NULL;
	}
}

/**
 * Based on the function at http://php.net/manual/en/function.array-filter.php#89432 by "Craig", it allows separation of values based on a callback function
 * @param array &$input - The array to process, also this will be filled with array values that were evaluated as boolean FALSE via the compare callback
 * @param callback $compare - Function name that we will use to check each value
 * @return array - The vars that match in the strict comparison
 *
 * @note Kudos to cs278 for the function redesign...like ZOMG so much nicer!
 */
function array_split(&$input, $compare)
{
	$return = array_filter($input, $callback);
	$input = array_diff($input, $return);
	return $return;
}

/**
 * This function is a lie.
 * @return void
 */
function cake()
{
	$cake = array(
	'                                          ',
	'                                          ',
	'              ,:/+/-                      ',
	'              /M/              .,-=;//;-  ',
	'         .:/= ;MH/,    ,=/+%$XH@MM#@:     ',
	'        -$##@+$###@H@MMM#######H:.    -/H#',
	'   .,H@H@ X######@ -H#####@+-     -+H###@X',
	'    .,@##H;      +XM##M/,     =%@###@X;-  ',
	'  X%-  :M##########$.    .:%M###@%:       ',
	'  M##H,   +H@@@$/-.  ,;$M###@%,          -',
	'  M####M=,,---,.-%%H####M$:          ,+@##',
	'  @##################@/.         :%H##@$- ',
	'  M###############H,         ;HM##M$=     ',
	'  #################.    .=$M##M$=         ',
	'  ################H..;XM##M$=          .:+',
	'  M###################@%=           =+@MH%',
	'  @###############M/.           =+H#X%=   ',
	'  =+M#############M,        -/X#X+;.      ',
	'    .;XM#########H=     ,/X#H+;,          ',
	'      .=+HM########M+/+HM@+=.             ',
	'          ,:/%XM####H/.                   ',
	'               ,.:=-.                     ',
	'                                          ',
	'                                          ',
	);
	display($cake);
}
