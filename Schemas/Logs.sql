/**
 *
 *===================================================================
 *
 *  Failnet -- PHP-based IRC Bot
 *-------------------------------------------------------------------
 * @version     3.0.0 DEV
 * @category    Failnet
 * @package     schemas
 * @author      Damian Bushong
 * @copyright   (c) 2009 - 2010 -- Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/Failnet-PHP-IRC-Bot
 *
 *===================================================================
 *
 */

/**
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

CREATE TABLE Logs (
	log_id INTEGER PRIMARY KEY NOT NULL,
	log_time INTEGER UNSIGNED NOT NULL DEFAULT 0,
	sender TEXT NOT NULL DEFAULT '',
	location TEXT NOT NULL DEFAULT '',
	type INTEGER UNSIGNED NOT NULL DEFAULT 0,
	event TEXT NOT NULL DEFAULT ''
);
