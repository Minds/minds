<?php
/**
 * Minds activity entity.
 */

namespace minds\entities;
use Minds\Helpers;
use Minds\Core\Queue;

class activity extends entity{

	public $indexes = NULL;

	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'type' => 'activity',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 2, //private,

			'node' => elgg_get_site_url()
		));
	}

    public function __construct($guid = NULL){
        parent::__construct($guid);
    }

		public function save($index = true){

			//cache owner_guid for brief
			if(!$this->ownerObj && $owner = $this->getOwnerEntity(false))
				$this->ownerObj = $owner->export();

			$guid = parent::save($index);

      return $guid;
		}

    public function delete(){
        if($this->p2p_boosted)
                return false;

        $indexes = $this->getIndexKeys(true);
        $db = new \Minds\Core\Data\Call('entities');
        $res = $db->removeRow($this->guid);

        $db = new \Minds\Core\Data\Call('entities_by_time');
        foreach($indexes as $index){
            $db->removeAttributes($index, array($this->guid));
        }

        Queue\Client::build()->setExchange("mindsqueue")
                            ->setQueue("FeedCleanup")
                            ->send(array(
                                "guid" => $this->guid,
                                "owner_guid" => $this->owner_guid,
																"type" => "activity"
                                ));

        return true;

    }
	/**
	 * Returns an array of indexes into which this entity is stored
	 *
	 * @param bool $ia - ignore access
	 * @return array
	 */
	protected function getIndexKeys($ia = false){

		if($this->indexes){
			return $this->indexes;
		}

		$indexes = array(
			$this->type
		);

		$owner = $this->getOwnerEntity();

        array_push($indexes, "$this->type:user:$owner->guid");
        array_push($indexes, "$this->type:network:$owner->guid");


        if($this->to_guid == $owner->guid)
            array_push($indexes, "$this->type:user:own:$owner->guid");

		/**
		 * @todo make it only post to a group if we are in a group
		 */
		array_push($indexes, "$this->type:container:$this->container_guid");

		/**
		 * Make a link from entity to this activity post
		 */
		if($this->entity_guid)
			array_push($indexes, "$this->type:entitylink:$this->entity_guid");

		return $indexes;
	}

	public function getExportableValues(){
		return array_merge(parent::getExportableValues(),
			array(
				'title',
				'blurb',
				'perma_url',
				'message',
				'ownerObj',
				'thumbnail_src',
				'remind_object',
				'entity_guid',
				'custom_type',
				'custom_data',
				'thumbs:up:count',
				'thumbs:up:user_guids',
                'thumbs:down:count',
                'thumbs:down:user_guids',
                'p2p_boosted'
			));
	}

	public function export(){
		$export = parent::export();
		if($this->entity_guid)
			$export['entity_guid'] = (string) $this->entity_guid;
       		$export['impressions'] = $this->getImpressions();
            $export['reminds'] = $this->getRemindCount();
            if($this->message)
                $export['message'] = strip_tags($this->message);
         if($this->entity_guid){
                $export['thumbs:up:count'] = Helpers\Counters::get($this->entity_guid,'thumbs:up');
                $export['thumbs:down:count'] = Helpers\Counters::get($this->entity_guid,'thumbs:down');
            }
      		$export = array_merge($export, \Minds\Core\Events\Dispatcher::trigger('export:extender', 'activity', array('entity'=>$this), array()));

		return $export;
	}

	/**
	 * Return a friendly url
	 */
	public function getURL(){
		return elgg_get_site_url() . 'newsfeed/'.$this->guid;
	}

	/**
	 * Returns the owner entity
	 */
	public function getOwnerEntity($brief = false){
		return parent::getOwnerEntity(true);
	}

	/**
	 * Set the message
	 * @param string $message
	 * @return $this
	 */
	public function setMessage($message){
		$this->message = $message;
		return $this;
	}

	/**
	 * Sets the title
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title){
		$this->title = $title;
		return $this;
	}

	/**
	 * Sets the blurb
	 * @param string $blurb
	 * @return $this
	 */
	public function setBlurb($blurb){
		$this->blurb = $blurb;
		return $this;
	}

	/**
	 * Sets the url
	 * @param string $url
	 * @return $this
	 */
	public function setURL($url){
		$this->perma_url = $url;
		return $this;
	}

	/**
	 * Sets the thumbnail
	 * @param string $src
	 * @return $this
	 */
	public function setThumbnail($src){
		$this->thumbnail_src = $src;
		return $this;
	}

	/**
	 * Sets the owner
	 * @param mixed $owner
	 * @return $this
	 */
	public function setOwner($owner){
		if(is_numeric($owner)){
			$owner = new \minds\entities\user($owner);
			$owner = $owner->export();
		}

		$this->owner = $owner;

		return $this;
	}

	/**
	 * Set from a local minds object
	 * @return $this
	 */
	public function setFromEntity($entity){

		$this->entity_guid = $entity->guid;

		return $this;
	}

	/**
	 * Set the reminded object
	 * @param array $array - the exported array
	 * @return $this
	 */
	public function setRemind($array){
		$this->remind_object = $array;
		return $this;
	}

	/**
	 * Set a custom, arbitrary set. For example a custom video view, or maybe a set of images. I envisage
	 * certain service could extend this.
	 * @param string $type
	 * @param array $data
	 * @return $this
	 */
	public function setCustom($type, $data = array()){
		$this->custom_type = $type;
		$this->custom_data = $data;
		return $this;
	}

    /**
     * Set the to_guid
     * @param int $guid
     * @return $this
     */
    public function setToGuid($guid){
        $this->to_guid = $guid;
        return $this;
    }

    /**
     * Return the count for this entity
     */
    public function getImpressions(){
        return \Minds\Helpers\Counters::get($this, 'impression');
    }

    /**
     * Return the count of reminds
     * @return int
     */
    public function getRemindCount(){
        if($this->remind_object)
            return \Minds\Helpers\Counters::get($this->remind_object['guid'], 'remind');

        return \Minds\Helpers\Counters::get($this, 'remind');
    }
}
