<?php

class MindsTrending{
	
	static $data = array();
		
	/**
	 * @param array $sources - eg. analytics, thumbs
	 */
	public function __construct(array $sources = array('google'), array $options = array()){

		self::$data = array();
		
		if(!$sources)
			$sources = array('google');
		$this->sources = $sources;
		
		$defaults = array(
			'timespan' => 'day',	
			'limit' => 500
		);
		
		$options = array_merge($defaults, $options);
		$dates = $this->timespanVariables($options['timespan']);
		$this->options = array_merge($options, $dates);
	}
	
	/**
	 * Return the analytical data
	 * 
	 * @param array $options
	 */
	public function pull(){
		
		foreach($this->sources as $source){
			$analytics = new MindsAnalytics($source);
			$guids = $analytics->fetch($this->options); //guids sorted highest 
			self::$data = array_merge(self::$data, $guids);
		}
		
		$this->pullOwners();
		
		$this->save();
	}
	
	/**
	 * Get the owners and create a trending list for them
	 * 
	 * @param array data
	 */
	public function pullOwners(){
		
		$user_guids = array();
		
		foreach(self::$data as $score => $guid){
			$entity = get_entity($guid);
			array_push($user_guids,$entity->owner_guid);
		}
		
		$occurances = array_count_values($user_guids);
		arsort($occurances);
		$user_guids = array_keys($occurances);
	
		self::$data = array_merge(self::$data, $user_guids);
				
		return;
	}
	
	/**
	 * Get list
	 * 
	 * @return array $guids
	 */
	public function getList(array $options = array()){
		
		$defaults = array(
			'type' => 'object',
			'subtype' => null,
			'limit' => 12,
			'offset' => 0,
			'count' => false,
			'reversed' => false
		);	
	
		$options = array_merge($defaults, $options);

		$g = new GUID();
		$options['offset'] = $g->migrate($options['offset']);
		

		$timespan = $this->options['timespan'];
		$type = $options['type'];
		$subtype = $options['subtype'];
		
		$db = new minds\core\data\call('entities_by_time');

		if($subtype){
			$namespace = "trending:$timespan:$type:$subtype";
		} else {
			$namespace = "trending:$timespan:$type";
		}

		$count = $g->migrate($db->countRow($namespace)); // cassandra does strange things if the digit count is not the same, so we need 18 0s
		if(((int) $options['offset'] + $options['limit']>= $count) && $options['offset'] > $g->migrate(1)){
			return false;
		}

		$guids = $db->getRow($namespace, $options);		
		ksort($guids);
//var_dump($guids);
		return $guids;
	}
	
	/**
	 * Save
	 * 
	 * @return void
	 */
	public function save(){
		
		$timespan = $this->options['timespan'];
		//remove the current list
		$lists = array('user'=>null, 'object'=>array('blog','kaltura_video'));
		
		$db = new minds\core\data\call('entities_by_time');
		foreach($lists as $type => $subtypes){
			if(isset($subtypes)){
				foreach($subtypes as $subtype){
					//$db->removeRow("trending:$timespan:$type:$subtype");
				}
			} else {
				//$db->removeRow("trending:$timespan:$type");
			}
		}
	
		$index_variables = array();
		//a lower score is higher in the list
		foreach(self::$data as $score => $guid){	
			
			$entity = get_entity($guid);
			$g = new GUID();	
		
			if(!in_array($guid, $index_variables[$entity->type])){
				$i = $g->migrate(count($index_variables[$entity->type]));
	                        $index_variables[$entity->type][] = $guid;
				$db->insert("trending:$timespan:$entity->type", array($i=>$guid));
			}	
			if(isset($entity->subtype)){
				if(!in_array($guid, $index_variables[$entity->type][$entity->subtype])){
					$i = $g->migrate(count($index_variables[$entity->type][$entity->subtype]));
                               		if(!is_array($index_variables[$entity->type][$entity->subtype]))
						$index_variables[$entity->type][$entity->subtype] = array();
					$index_variables[$entity->type][$entity->subtype][] = $guid;
					$db->insert("trending:$timespan:$entity->type:$entity->subtype", array($i=>$guid));
				}
                        }
	
			echo "Successfuly imported $guid with score $score and index $i to $timespan:$entity->type:$entity->subtype \n";
		}
	
	}
	
	public function timespanVariables($timespan = 'day'){
		$helpers = array(
			'hour' => 60 * 60,
			'day' => (60 * 60) * 24,
			'week' => ((60 * 60) * 24) * 7,
			'month' => (((60 * 60) * 24) * 7) * 4, //an average month
			'year' => ((((60 * 60) * 24) * 7) * 4) * 12 // an average year
		);
		
		switch($timespan){
			case 'day':
				return array(
					'from' => date('o-m-d', time() - $helpers['day']),  //yesterday
					'to' => date('o-m-d', time()) //today
				);
				break;
			case 'yesterday':
				return array(
					'from' => date('o-m-d', time() - ($helpers['day']*2)), //two days ago
					'to' => date('o-m-d', time() - $helpers['day']) //a day ago
				);
				break;
			case 'week':
				return array(
					'from' => date('o-m-d', time() - $helpers['week']), //a week ago
					'to' => date('o-m-d', time()) //today
				);
				break;
			case 'lastweek':
				return array(
					'from' => date('o-m-d', time() - ($helpers['week'] * 2)), //two weeks ago 
					'to' => date('o-m-d', time() - $helpers['week']) // a week ago
				);
				break;
			case 'month':
				return array(
					'from' => date('o-m-d', time() - $helpers['month']), // a month ago 
					'to' =>  date('o-m-d', time()) //today 
				);
				break;
			case 'lastmonth':
				return array(
					'from' => date('o-m-d', time() - ($helpers['month']*2)), // two months ago 
					'to' => date('o-m-d', time() - $helpers['month']) // a month ago
				);
				break;
			case 'year':
				return array(
					'from' => date('o-m-d', time() - $helpers['year']), // a year ago 
					'to' => date('o-m-d', time()) //today 
				);
				break;
			case 'lastyear':
				return array(
					'from' => date('o-m-d', time() - ($helpers['year']*2)), // two years ago
					'to' =>  date('o-m-d', time() - $helpers['year']), // a year ago 
				);
				break;
			case 'entire':
				return array(
					'from' => date('o-m-d', time() - ($helpers['year']*5)), //the earliest possible date (5 years??)
					'to' => date('o-m-d', time()) //today 
				);
		}
		return false;
	}
}
