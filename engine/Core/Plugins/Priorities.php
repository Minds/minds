<?php
/**
 * Handles the orderring a priorities (helper functions)
 */

namespace Minds\Core\Plugins;

use Minds\Core;

class Priorities extends core\plugins{

	static public function getMax(){
		$plugins = parent::get();
		$max = count($plugins);

		// can't have a priority of 0.*/
		return ($max) ? $max : 1;
	}

	static public function set($plugin, $priority){

		if (!$plugin->guid)
			return false;

		// if no priority assume a priority of 1
		$old_priority = (int) $plugin->priority;
		$max_priority = self::getMax();

		// can't use switch here because it's not strict and
		// php evaluates +1 == 1
		if ($priority === '+1') {
			$priority = $old_priority + 1;
		} elseif ($priority === '-1') {
			$priority = $old_priority - 1;
		} elseif ($priority === 'first') {
			$priority = 1;
		} elseif ($priority === 'last') {
			$priority = $max_priority;
		}

		// should be a number by now
		if ($priority > 0) {

			// there's nothing above the max.
			if ($priority > $max_priority) {
				$priority = $max_priority;
			}

			// there's nothing below 1.
			if ($priority < 1) {
				$priority = 1;
			}

			$plugin_list = core\plugins::get('any');
			$reorder = array();

			foreach($plugin_list as $plugin){
				if($plugin->getPriority() ==  $old_priority-1 && $op=='-'){
					$plugin->priority =  $old_priority;
					$plugin->save();
					continue;
				}
				if($plugin->getPriority() ==  $old_priority+1 && $op == '+'){
					$plugin->priority =  $old_priority;
					$plugin->save();
					continue;
				}
			}
			// set this priority
			$this->priority = $priority;

			if ($plugin->save())
				return true;
			else
				return false;
		}

		return false;
	}

}
