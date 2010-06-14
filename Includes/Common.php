<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 * @version     3.0.0 DEV
 * @category    Failnet
 * @package     Failnet
 * @author      Failnet Project
 * @copyright   (c) 2009 - 2010 -- Failnet Project
 * @license     GNU General Public License, Version 3
 * @link        http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
 *
 *===================================================================
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 */

namespace Failnet;

/**
 * Failnet - Master class,
 *      Used as the master static class that will contain all node objects, core objects, etc.
 *
 *
 * @category    Failnet
 * @package     Failnet
 * @author      Failnet Project
 * @license     GNU General Public License, Version 3
 * @link        http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
 */
abstract class Bot
{
	/**
	 * @var array - The core objects, which will also include the core class.
	 */
	private static $core = array();

	/**
	 * @var array - Array of loaded node objects
	 */
	private static $nodes = array();

	/**
	 * @var array - Array of loaded cron objects
	 */
	private static $cron = array();

	/**
	 * @var array - Array of loaded plugins
	 */
	private static $plugins = array();

	/**
	 * @var array - Array of hook data
	 */
	protected static $hooks = array();

	/**
	 * Grab the core object.
	 * @param string $core_name - The name of the core object that we want, or an empty string if we want THE core.
	 * @return \Failnet\Base - The desired core object if present.
	 * @throws Failnet\Exception
	 */
	public static function core($core_name = '')
	{
		if(empty($core_name))
			return self::$core['core'];
		if(self::checkCoreLoaded($core_name))
			return self::$core[$core_name];
		throw new Exception(ex(Exception::ERR_NO_SUCH_CORE_OBJ));
	}

	/**
	 * Grab a node object.
	 * @param string $node_name - The name of the node object that we want.
	 * @return \Failnet\Base - The desired node object if present, or void if no such object.
	 * @throws Failnet\Exception
	 */
	public static function node($node_name)
	{
		if(!self::checkNodeLoaded($node_name))
			throw new Exception(ex(Exception::ERR_NO_SUCH_NODE_OBJ));
		return self::$nodes[$node_name];
	}

	/**
	 * Grab a cron object.
	 * @param string $cron_name - The name of the cron object that we want.
	 * @return \Failnet\Cron\Common - The desired cron object if present, or void if no such object.
	 * @throws Failnet\Exception
	 */
	public static function cron($cron_name)
	{
		if(empty($cron_name))
			return self::$cron['core'];
		if(!self::checkCronLoaded($cron_name))
			throw new Exception(ex(Exception::ERR_NO_SUCH_CRON_OBJ));
		return self::$cron[$cron_name];
	}

	/**
	 * Grab a plugin object.
	 * @param string $plugin_name - The name of the plugin object that we want.
	 * @return Failnet\Plugin\Common - The desired plugin object if present.
	 * @throws Failnet\Exception
	 */
	public static function plugin($plugin_name)
	{
		if(!self::checkPluginLoaded($plugin_name))
			throw new Exception(ex(Exception::ERR_NO_SUCH_PLUGIN_OBJ));
		return self::$plugins[$plugin_name];
	}

	/**
	 * Create a new core object.
	 * @param string $core_name - The name of the core slot to load into.
	 * @param string $core_class - The name of the class to load.
	 * @return void
	 */
	public static function setCore($core_name, $core_class)
	{
		self::$core[$core_name] = new $core_class();
	}

	/**
	 * Create a new node object.
	 * @param string $node_name - The name of the node slot to load into.
	 * @param string $node_class - The name of the class to load.
	 * @return void
	 */
	public static function setNode($node_name, $node_class)
	{
		self::$nodes[$node_name] = new $node_class();
	}

	/**
	 * Create a new core object.
	 * @param string $cron_name - The name of the cron slot to load into.
	 * @param string $cron_class - The name of the class to load.
	 * @return void
	 */
	public static function setCron($cron_name, $cron_class)
	{
		self::$cron[$cron_name] = new $cron_class();
	}

	/**
	 * Create a new core object.
	 * @param string $cron_name - The name of the cron slot to load into.
	 * @param string $cron_class - The name of the class to load.
	 * @return void
	 */
	public static function setPlugin($plugin_name, $plugin_class)
	{
		self::$plugins[$plugin_name] = new $plugin_class();
	}

	/**
	 * Checks to see if the specified core slot has been occupied
	 * @param string $core_name - The name of the core slot to check
	 * @return boolean - Whether or not a core object has been loaded yet into the specified slot
	 */
	public static function checkCoreLoaded($core_name)
	{
		return isset(self::$core[$core_name]);
	}

	/**
	 * Checks to see if the specified node slot has been occupied
	 * @param string $node_name - The name of the node slot to check
	 * @return boolean - Whether or not a node object has been loaded yet into the specified slot
	 */
	public static function checkNodeLoaded($node_name)
	{
		return isset(self::$nodes[$core_name]);
	}

	/**
	 * Checks to see if the specified cron slot has been occupied
	 * @param string $cron_name - The name of the cron slot to check
	 * @return boolean - Whether or not a cron object has been loaded yet into the specified slot
	 */
	public static function checkCronLoaded($cron_name)
	{
		return isset(self::$cron[$cron_name]);
	}

	/**
	 * Checks to see if the specified cron slot has been occupied
	 * @param string $plugin_name - The name of the plugin slot to check
	 * @return boolean - Whether or not a plugin object has been loaded yet into the specified slot
	 */
	public static function checkPluginLoaded($plugin_name)
	{
		return isset(self::$plugins[$plugin_name]);
	}


	/**
	 * Register a hook function to be called before
	 * @param array $hooked_method_call - The callback info for the method we're hooking onto.
	 * @param mixed $hook_call - The function/method to hook on top of the method we're hooking.
	 * @param constant $hook_type - The type of hook we're using.
	 * @return boolean - Were we successful?
	 * @throws failnet_exception
	 */
	public static function registerHook($hooked_method_class, $hooked_method_name, $hook_call, $hook_type = HOOK_NULL)
	{
		// We're deliberately ignoring HOOK_NULL here.
		if(!in_array($hook_call, array(HOOK_STACK, HOOK_OVERRIDE)))
			throw new Exception(ex(Exception::ERR_REGISTER_HOOK_BAD_HOOK_TYPE));

		// Check for unsupported classes
		if(substr($hooked_method_class, 0, 8) != '\\Failnet')
			throw new Exception(ex(Exception::ERR_REGISTER_HOOK_BAD_CLASS, array($hooked_method_class)));

		/**
		 * Hooks are placed into the hook info array using the following array structure:
		 *
		 <code>
			self::$hooks[$hooked_method_class][$hooked_method_name] = array(
				array(
					'hook_call'		=> $hook_call,
					'type'			=> HOOK_STACK,
				),
				array(
					'hook_call'		=> $hook_call,
					'type'			=> HOOK_OVERRIDE,
				),
			);
		 </code>
		 *
		 */

		/**
		 * At some point in the future, we may want to check to see if the method we are hooking onto exists,
		 * but for now we will not, as the class may not yet be loaded.
		 * We'll just have to take their word for it.
		 */
		self::$hooks[$hooked_method_class][$hooked_method_name][] = array('hook_call' => $hook_call, 'type' => $hook_type);
	}

	/**
	 * Checks to see if any hooks have been assigned to a designated class/method, and returns their info.
	 * @param string $hooked_method_class - The name of the class to check a method of for hooks
	 * @param string $hooked_method_name - The name of the previously specified class's method to check for hooks
	 * @return mixed - Returns either false if there's no such hooks associated, or returns the array containing that method's hook data.
	 */
	public static function retrieveHook($hooked_method_class, $hooked_method_name)
	{
		if(!isset(self::$hooks[$hooked_method_class][$hooked_method_name]))
			return false;
		return self::$hooks[$hooked_method_class][$hooked_method_name];
	}
}

/**
 * Failnet - Base class,
 * 	    Used as the base class that will handle method hooking.
 *
 *
 * @category    Failnet
 * @package     Failnet
 * @author      Failnet Project
 * @license     GNU General Public License, Version 3
 * @link        http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
 */
abstract class Base
{
	/**
	 * @var string - The current class name
	 */
	public static $__CLASS__ = __CLASS__;

	/**
	 * __call hook enabler, intercepts calls to methods and checks for hooks, then forwards the call to the actual method.
	 * @param string $name - Method name
	 * @param array $arguments - Method parameters
	 * @return void
	 * @throws failnet_exception
	 */
	public function __call($name, $arguments)
	{
		if(method_exists($this, "_$name"))
		{
			$hook_ary = Bot::retrieveHook(get_class($this), $name);
			if(!empty($hook_ary))
			{
				foreach($hook_ary as $hook)
				{
					// process the hook data here
					if($hook['type'] === HOOK_OVERRIDE)
					{
						return call_user_func_array($hook['hook_call'], $arguments);
					}
					elseif($hook['type'] === HOOK_STACK)
					{
						call_user_func_array($hook['hook_call'], $arguments);
					}
				}
			}
			return call_user_func_array(array($this, "_$name"), $arguments);
		}
		else
		{
			throw new Exception(ex(Exception::ERR_UNDEFINED_METHOD_CALL, array($name, get_class($this))));
		}
	}

	/**
	 * __callStatic hook enabler, intercepts static calls to methods and checks for hooks, then forwards the static call to the actual method.
	 * @param string $name - Method name
	 * @param array $arguments - Method parameters
	 * @return void
	 * @throws failnet_exception
	 */
	public function __callStatic($name, $arguments)
	{
		if(method_exists(static::$__CLASS__, "_$name"))
		{
			$hook_ary = Bot::retrieveHook(static::$__CLASS__, $name);
			if(!empty($hook_ary))
			{
				foreach($hook_ary as $hook)
				{
					// process the hook data here
					if($hook['type'] === HOOK_OVERRIDE)
					{
						return call_user_func_array($hook['hook_call'], $arguments);
					}
					elseif($hook['type'] === HOOK_STACK)
					{
						call_user_func_array($hook['hook_call'], $arguments);
					}
				}
			}
			return call_user_func_array(array(static::$__CLASS__, "static::_$name"), $arguments);
		}
		else
		{
			throw new Exception(ex(Exception::ERR_UNDEFINED_METHOD_CALL, array($name, static::$__CLASS__)));
		}
	}
}

class ConfigBase extends Base
{
	const SRC_CONFIG = 1;
	const SRC_DB = 2;
	const SRC_PROP = 3;
	const SRC_MIX = 4;

	protected static $config = array();

	public static function get($setting, $source = self::SRC_MIX)
	{
		// Make sure they're not pulling a fast one on us
		if(!in_array($source, range(1, 4)))
			throw new Exception(); // @todo exception msg
		// code the rest
	}
}