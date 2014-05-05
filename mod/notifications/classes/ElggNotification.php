<?php
/**
 * ElggNotification Class
 * 
 */
class ElggNotification extends ElggEntity{

	/**
	 * Set type to notifications
	 * 
	 * @return void
	 */
	protected function initializeAttributes() {

		$this->attributes['type'] = 'notification';
	}

	/**
         * Load or create a new ElggWiget.
         *
         * If no arguments are passed, create a new entity.
         *
         * @param mixed $guid If an int, load that GUID.  If a db row, then will attempt to
         * load the rest of the data.
         *
         * @throws IOException If passed an incorrect guid
         * @throws InvalidParameterException If passed an Elgg* Entity that isn't an ElggObject
         */
        function __construct($guid = null) {
                $this->initializeAttributes();

                // compatibility for 1.7 api.
                $this->initialise_attributes(false);

                if (!empty($guid)) {
                        // Is $guid is a DB row from the entity table
                        if ($guid instanceof stdClass) {
                                // Load the rest
                                if (!$this->load($guid)) {
                                        $msg = elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid->guid));
                                        throw new IOException($msg);
                                }

                        // Is $guid is an ElggObject? Use a copy constructor
                        } else if ($guid instanceof ElggNotification) {
                                elgg_deprecated_notice('This type of usage of the ElggObject constructor was deprecated. Please use the clone method.', 1.7);

                                foreach ($guid->attributes as $key => $value) {
                                        $this->attributes[$key] = $value;
                                }

                        // Is this is an ElggEntity but not an ElggObject = ERROR!
                        } else if ($guid instanceof ElggEntity) {
                                throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggObject'));

                        // Is it a GUID
                        } else {
                                if (!$this->load($guid)) {
                                        throw new IOException(elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid)));
                                }
                        }
                }
        }

	/**
	 * Loads the full ElggNotification when given a guid.
	 *
	 * @param mixed $guid GUID of an ElggNotification or the stdClass object from entities table
	 *
	 * @return bool
	 * @throws InvalidClassException
	 */
	protected function load($guid) {
		
		foreach($guid as $k => $v){
                        $this->attributes[$k] = $v;
                }		

		cache_entity($this);

		return true;
	}

	public function save(){
		//some special logic as this is not an enitiy... or should it be?
//		var_dump($this);
		$guid =  create_entity($this,'false');
		
		$db = new minds\core\data\call('entities_by_time');
		$db->insert('notifications:'.$this->to_guid, array($guid => $guid));

		notifications_increase_counter($this->to_guid);

		return $guid;
	}

}
