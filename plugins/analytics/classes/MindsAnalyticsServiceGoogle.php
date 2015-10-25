<?php

class MindsAnalyticsServiceGoogle extends MindsAnalyticsService{
	
	
	public function connect(){
		$this->client = $this->setUpGoogleClient();
		$this->analytics = new Google_AnalyticsService($this->client);
		$this->profile_id = 'ga:' . elgg_get_plugin_setting('profile_id', 'analytics');
	}

	public function setUpGoogleClient(){
		$client = new Google_Client();
		$client->setApplicationName('Minds analytics reporter');

		// set assertion credentials
		$client->setAssertionCredentials(
			new Google_AssertionCredentials(

				'81109256529-7204tgap3gkaf3gmeuji4k9r408m76m8@developer.gserviceaccount.com', // email you added to GA

				array('https://www.googleapis.com/auth/analytics.readonly'),

				file_get_contents('/key.p12')  // keyfile you downloaded

		));

		$client->setClientId('81109256529-7204tgap3gkaf3gmeuji4k9r408m76m8.apps.googleusercontent.com');
		$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
		$client->setAccessType('offline_access');


		$client->setUseObjects(true);

		return $client;
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
			'limit'=>10
		);
		
		$options = array_merge($defaults, $options);
		
		try{
			$optParams = array(
				'dimensions' => 'ga:pagePath',
				'sort' => '-ga:pageviews',
				'filters' => 'ga:pagePath=~/view/',
				'max-results' => $options['limit']
			);
			
			$results = $this->analytics->data_ga->get(
				$this->profile_id,
				$options['from'],
				$options['to'],
				'ga:pageviews',
				$optParams
			);
		} catch (Exception $e){
			var_dump($e);
			return;
		}
		return $this->render($results);
	}
		
	/**
	 * Render the results so they follow a standard format that services can share..
	 * 
	 * @param array/object $results
	 * @return array of guids
	 */
	public function render($results){
			
		$guids = array();
		
		foreach ($results->getRows() as $row) {
			try{		
				$url = $row[0];			   
				$count = $row[1];
				$entity = get_entity($this->getGuidFromURL($url));
					
				//check if the entity extists
				if(!$entity){
					throw new Exception("The entity doesn't exist");
				} 
					
				//check if the entity is public
				if($entity->access_id != ACCESS_PUBLIC){
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
				//echo "$entity->type $entity->guid failed because" . $e->getMessage() . "\n";
				continue; //we just want to skup
			}
				
			array_push($guids, array('guid'=>$entity->guid, 'count'=>$count)); //now add to the list
			//echo "rendered $entity->guid";
		}	
		
		return $guids; 	
	}
}
