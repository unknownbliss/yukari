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
 * @license		GNU General Public License, Version 3
 * @link		http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
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
 */

namespace Failnet;

// Version constant
define('Failnet\FAILNET_VERSION', '3.0.0-DEV');

/**
 * DO NOT _EVER_ CHANGE THIS, FOR THE SAKE OF HUMANITY.
 * @link http://xkcd.com/534/
 */
define('Failnet\CAN_BECOME_SKYNET', false);
define('Failnet\COST_TO_BECOME_SKYNET', 999999999);

// Output levels
define('Failnet\OUTPUT_SILENT', 0);
define('Failnet\OUTPUT_NORMAL', 1);
define('Failnet\OUTPUT_DEBUG', 2);
define('Failnet\OUTPUT_DEBUG_FULL', 3);
define('Failnet\OUTPUT_RAW', 4);
define('Failnet\OUTPUT_SPAM', 4); // ;D

// Hook types
define('Failnet\HOOK_NULL', 0);
define('Failnet\HOOK_STACK', 1);
define('Failnet\HOOK_OVERRIDE', 2);
