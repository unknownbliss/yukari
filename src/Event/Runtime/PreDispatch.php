<?php
/**
 *
 *===================================================================
 *
 *  Yukari
 *-------------------------------------------------------------------
 * @category    Yukari
 * @package     event
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

namespace Failnet\Event\Runtime;

/**
 * Failnet - Event object,
 * 	    A class to define ene of the various event types.
 *
 *
 * @category    Yukari
 * @package     event
 * @author      Damian Bushong
 * @license     MIT License
 * @link        https://github.com/damianb/yukari
 */
class PreDispatch extends RuntimeBase
{
	/**
	 * @var array - The map of the various arguments for the event
	 */
	protected $map = array(
		'time',
		'event',
	);

	/**
	 * @var Failnet\Event\EventBase - An event that is about to be sent.
	 */
	public $arg_event;
}
