<?php
/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 * @version     3.0.0 DEV
 * @category    Failnet
 * @package     install
 * @author      Damian Bushong
 * @copyright   (c) 2009 - 2010 -- Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace Failnet\Install;
use Failnet;
use Failnet\Core as Core;
use Failnet\Lib as Lib;

/**
 * Failnet - Config file generator class,
 *      Generates the config files for Failnet's installer.
 *
 *
 * @category    Failnet
 * @package     install
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
 */
class Generator extends Base
{
	public function buildHeader()
	{
		return implode(PHP_EOL, array(
			'<' . '?php',
			'/' . '**',
			' *',
			' *===================================================================',
			' *',
			' *  Failnet -- PHP-based IRC Bot',
			' *-------------------------------------------------------------------',
			' * @version     3.0.x',
			' * @category    Failnet',
			' * @package     config',
			' * @author      Damian Bushong',
			' * @copyright   (c) 2009 - 2010 -- Damian Bushong',
			' * @license     MIT License',
			' * @link        http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot',
			' *',
			' *===================================================================',
			' *',
			' * This source file is subject to the MIT license that is bundled',
			' * with this package in the file LICENSE.',
			' *',
			' *' . '/',
			'',
		));
	}
}
