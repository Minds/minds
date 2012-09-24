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

//	gatekeeper();
	
	if (isloggedin())
	{
		
		global $SESSION;
		
		if (!empty($_POST['beechat_state']))
		{
			$SESSION->offsetSet('beechat_state', $_POST['beechat_state']);
		} 
		elseif (!empty($_POST['beechat_conn']))
		{
			$SESSION->offsetSet('beechat_conn', get_input('beechat_conn'));
		}
	}		
	exit();
?>
