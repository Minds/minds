<?php
	/**
	 * Beechat
	 * 
	 * @package beechat
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Beechannels <contact@beechannels.com>
	 * @copyright Beechannels 2007-2010
	 * @link http://beechannels.com/
	 */
	 
	header('Content-type: application/json');
	gatekeeper();
	
	global $SESSION;
	
	if ($SESSION->offsetExists('beechat_conn'))
	  echo $SESSION->offsetGet('beechat_conn');
	
	exit();
?>
