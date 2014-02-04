<?php

class MindsAnalyticsServiceGoogle extends MindsAnalyticsService{
	
	
	public function connect(){
		
		$this->client = null;
		$this->analytics = new Google_AnalyticsService($this->client);
		$this->profile_id = 'ga:' . elgg_get_plugin_setting('profile_id', 'analytics');
	}
	
	/**
	 * Fetch the reports from google analytics
	 * 
	 * @param array $options 
	 * @return array
	 */
	public function fetch(array $options = array()){
		
		$defaults = array(
			'from'=>  date('o-m-d', time() - 60 * 60 * 24), //yesterday
			'to'=>date('o-m-d', time()),//today
			'limit'=>100000
		);
		
		$options = array_merge($defaults, $options);
		
		try{
			$optParams = array(
				'dimensions' => 'ga:pagePath',
				'sort' => '-ga:pageviews',
				'filters' => 'ga:pagePath=~/view/',
				'max-results' => $options['limit']
			);
			
			$results = $analytics->data_ga->get(
				$this->profile_id,
				$options['from'],
				$options['to'],
				'ga:pageviews',
				$optParams
			);
		} catch (Exception $e){
			return;
		}
			
		return $this->render($results);
	}
		
	/**
	 * Render the results so they follow a standard format that services can share..
	 * 
	 * @param array $results
	 * @return array of guids
	 */
	public function render(array $results){
			
		$guids = array();
		
		foreach ($results->getRows() as $row) {
			try{			
				$url = $row[0];			   
			    $entity = get_entity($this->getGuidFromURL($url));
					
				//check if the entity extists
				if(!$entity){
					throw new Exception("The entity doesn't exist");
				} 
					
				//check if the entity is public
				if(!$entity->access_id != ACCESS_PUBLIC){
					throw new Exception("The entity is not public");
				}
					
				$views = $row[2];
				//check for duplicates
				if(in_array($guid, $guids) || !elgg_instanceof($entity,'object')){
					throw new Exception("GUID $guid failed, probably because it doesn't exists");
				}
					
				//check for available subtypes...
				if(!in_array($entity->subtype,array('blog', 'kaltura_video'))){
					throw new Exception("Subtype $entity->subtype is not allowed");
				}

			} catch( Exception $e){
				continue; //we just want to skup
			}
				
			array_push($guids, $entity->guid); //now add to the list
		}	
		
		return $guids; 	
	}
}