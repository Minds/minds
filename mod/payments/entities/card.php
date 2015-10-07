<?php
/**
 * Card entity
 */
 
namespace minds\plugin\payments\entities;
 
use Minds\Entities;
use minds\plugin\payments\services\paypal;

class card extends Entities\Object{
	
	public $card = NULL;
	
	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'subtype' => 'card',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 0 //private
		));
	}

	/**
	 * Create the wallet
	 */
	public function create($params = array()){
		
		$params = array_merge(array(
			'type' => NULL,
			'number' => NULL,
			'month' => NULL,
			'year' => NULL,
			'sec' => NULL,
			'name' => NULL,
			'name2' => NULL
		), $params);
		
	
		try{
			$obj = paypal::factory()->createCard($params);
		} catch(\Exception $e){
			\register_error($e->getMessage());
			error_log('CARD EXCEPTION: '. $e->getMessage());
			return false;
		}
		
		$this->card_id = $obj->getID();
		$this->number = $obj->number;
		$this->card_type = $obj->type;
			
		return $obj;
	}
	
	public function getCard(){
		
		try{
			$obj = paypal::factory()->getCard($this->card_id);
			$this->card = $obj;
		} catch(\Exception $e){
			return false;
		}
			
		return $obj;
	}
	
}
