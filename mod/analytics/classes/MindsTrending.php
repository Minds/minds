<?php

class MindsTrending{
	
	static $data = array();
		
	/**
	 * @param array $sources - eg. analytics, thumbs
	 */
	public function __construct(array $sources = array('google'), array $options = array()){
		$this->sources = $sources;
		
		$defaults = array(
			'timespan' => 'day',	
			'limit' => 100000
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
			self::$data = array_merge(self::$data, $result);
		}
		
		$this->pullOwners();
		
		$this->save();
	}
	
	/**
	 * Get the owners and create a trending list for them
	 * 
	 * @param array data
	 */
	public function pullOwners(array $data){
		
		$user_guids = array();
		
		foreach($data as $score => $guid){
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
			'subtype' => null
		);
		
		$options = array_merge($defaults, $options);
		
		$timespan = $this->options['timespan'];
		$type = $options['type'];
		$subtype = $options['subtype'];
		
		$db = new DatabaseCall('entities_by_time');
		if($subtype){
			$guids = $db->getRow("trending:$timespan:$type:$subtype");
		} else {
			$guids = $db->getRow("trending:$timespan:$type");
		}
		
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
		
		$db = new DatabaseCall('entities_by_time');
		foreach($lists as $type => $subtypes){
			if(isset($subtypes)){
				foreach($subtypes as $subtype){
					$db->removeRow("trending:$timespan:$type:$subtype");
				}
			} else {
				$db->removeRow("trending:$timespan:$type");
			}
		}
		
		//a lower score is higher in the list
		foreach(self::$data as $score => $guid){
			
			$entity = get_entity($guid);
			
			if(isset($entity->subtype)){
				$db->insert("trending:$timespan:$type:$subtype", array($score=>$guid));
			}
			$db->insert("trending:$timespan:$type", array($score=>$guid));
		
			echo "Successfuly imported $guid with score $score to $timespan:$type:$subtype \n";
		}
	
	}
	
	public function timespanVariables($timespan = 'day'){
		$helpers = array(
			'hour' => 60 * 60,
			'day' => (60 * 60) * 24,
			'week' => ((60 * 60) * 24) * 7,
			'month' => (((60 * 60) * 24) * 7) * 30, //an average month
			'year' => ((((60 * 60) * 24) * 7) * 30) * 12 // an average year
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
					'from' => date('o-m-d', time() - ($helpers['year']*20)), //the earliest possible date (20 years??)
					'to' => date('o-m-d', time()) //today 
				);
		}
		return false;
	}
}
