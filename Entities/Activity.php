<?php
namespace Minds\Entities;

use Minds\Helpers;
use Minds\Core;
use Minds\Core\Queue;
use Minds\Core\Analytics;

/**
 * Activity Entity
 */
class Activity extends Entity
{
    public $indexes = null;

    /**
     * Initialize entity attributes
     * @return null
     */
    public function initializeAttributes()
    {
        parent::initializeAttributes();
        $this->attributes = array_merge($this->attributes, array(
            'type' => 'activity',
            'owner_guid' => elgg_get_logged_in_user_guid(),
            'access_id' => 2, //private,
            'mature' => false,
            'paywall' => false
        //	'node' => elgg_get_site_url()
        ));
    }

    public function __construct($guid = null)
    {
        parent::__construct($guid);
    }

    /**
     * Saves the activity
     * @param  bool  $index - save to index
     * @return mixed        - the GUID
     */
    public function save($index = true)
    {

        //cache owner_guid for brief
        if (!$this->ownerObj && $owner = $this->getOwnerEntity(false)) {
            $this->ownerObj = $owner->export();
        }

        $guid = parent::save($index);

        return $guid;
    }

    /**
     * Deletes the activity entity and indexes
     * @return bool
     */
    public function delete()
    {
        if ($this->p2p_boosted) {
            return false;
        }

        $indexes = $this->getIndexKeys(true);
        $db = new \Minds\Core\Data\Call('entities');
        $res = $db->removeRow($this->guid);

        $db = new \Minds\Core\Data\Call('entities_by_time');
        foreach ($indexes as $index) {
            $db->removeAttributes($index, array($this->guid));
        }

        (new Core\Translation\Storage())->purge($this->guid);

        Queue\Client::build()->setQueue("FeedCleanup")
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
     * @param  bool $ia - ignore access
     * @return array
     */
    protected function getIndexKeys($ia = false)
    {
        if ($this->indexes) {
            return $this->indexes;
        }

        $indexes = array(
            $this->type
        );

        $owner = $this->getOwnerEntity();

        array_push($indexes, "$this->type:user:$owner->guid");
        array_push($indexes, "$this->type:network:$owner->guid");


        if ($this->to_guid == $owner->guid) {
            array_push($indexes, "$this->type:user:own:$owner->guid");
        }

        /**
         * @todo make it only post to a group if we are in a group
         */
        array_push($indexes, "$this->type:container:$this->container_guid");

        /**
         * Make a link from entity to this activity post
         */
        if ($this->entity_guid) {
            array_push($indexes, "$this->type:entitylink:$this->entity_guid");
        }

        return $indexes;
    }

    /**
     * Returns an array of which Entity attributes are exportable
     * @return array
     */
    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(),
            array(
                'title',
                'blurb',
                'perma_url',
                'message',
                'ownerObj',
                'containerObj',
                'thumbnail_src',
                'remind_object',
                'entity_guid',
                'featured',
                'featured_guid',
                'custom_type',
                'custom_data',
                'thumbs:up:count',
                'thumbs:up:user_guids',
                'thumbs:down:count',
                'thumbs:down:user_guids',
                'p2p_boosted',
                'mature',
                'monetized',
                'paywall'
            ));
    }

    /**
     * Exports the activity onto an array
     * @return array
     */
    public function export()
    {
        $export = parent::export();
        if ($this->entity_guid) {
            $export['entity_guid'] = (string) $this->entity_guid;
        }

        $export['impressions'] = $this->getImpressions();
        $export['reminds'] = $this->getRemindCount();

        if ($this->entity_guid && !$this->remind_object) {
            $export['thumbs:up:count'] = Helpers\Counters::get($this->entity_guid, 'thumbs:up');
            $export['thumbs:down:count'] = Helpers\Counters::get($this->entity_guid, 'thumbs:down');
        } elseif ($this->remind_object) {
            if ($this->remind_object['entity_guid']) {
                $export['thumbs:up:count'] = Helpers\Counters::get($this->remind_object['entity_guid'], 'thumbs:up');
                $export['thumbs:down:count'] = Helpers\Counters::get($this->remind_object['entity_guid'], 'thumbs:down');
            } else {
                $export['thumbs:up:count'] = Helpers\Counters::get($this->remind_object['guid'], 'thumbs:up');
                $export['thumbs:down:count'] = Helpers\Counters::get($this->remind_object['guid'], 'thumbs:down');
            }
        } else {
            $export['thumbs:up:count'] = Helpers\Counters::get($this, 'thumbs:up');
            $export['thumbs:down:count'] = Helpers\Counters::get($this, 'thumbs:down');
        }
        $export['thumbs:up:user_guids'] = $export['thumbs:up:user_guids'] ? (array) array_values($export['thumbs:up:user_guids']) : [];

        $export = array_merge($export, \Minds\Core\Events\Dispatcher::trigger('export:extender', 'activity', array('entity'=>$this), array()));

        $export['mature'] = (bool) $export['mature'];

        if ($this->custom_type == 'video' && $this->custom_data['guid']) {
            $export['play:count'] = Helpers\Counters::get($this->custom_data['guid'], 'plays');
        }

        if ($this->paywall) {
            $export['message'] = null;
            $export['custom_data'] = null;
        }

        return $export;
    }

    /**
     * Returns a friendly URL
     * @return string
     */
    public function getURL()
    {
        return elgg_get_site_url() . 'newsfeed/'.$this->guid;
    }

    /**
     * Returns the owner entity
     * @return mixed
     */
    public function getOwnerEntity($brief = false)
    {
        return parent::getOwnerEntity(true);
    }

    /**
     * Set the message
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Sets the title
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Sets the blurb
     * @param string $blurb
     * @return $this
     */
    public function setBlurb($blurb)
    {
        $this->blurb = $blurb;
        return $this;
    }

    /**
     * Sets the url
     * @param string $url
     * @return $this
     */
    public function setURL($url)
    {
        $this->perma_url = $url;
        return $this;
    }

    /**
     * Sets the thumbnail
     * @param string $src
     * @return $this
     */
    public function setThumbnail($src)
    {
        $this->thumbnail_src = $src;
        return $this;
    }

    /**
     * Sets the owner
     * @param mixed $owner
     * @return $this
     */
    public function setOwner($owner)
    {
        if (is_numeric($owner)) {
            $owner = new \Minds\Entities\User($owner);
            $owner = $owner->export();
        }

        $this->owner = $owner;

        return $this;
    }

    /**
     * Set from a local minds object
     * @return $this
     */
    public function setFromEntity($entity)
    {
        $this->entity_guid = $entity->guid;
        $this->ownerObj = $entity->ownerObj;
        return $this;
    }

    /**
     * Set the reminded object
     * @param array $array - the exported array
     * @return $this
     */
    public function setRemind($array)
    {
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
    public function setCustom($type, $data = array())
    {
        $this->custom_type = $type;
        $this->custom_data = $data;
        return $this;
    }

    /**
     * Set the to_guid
     * @param int $guid
     * @return $this
     */
    public function setToGuid($guid)
    {
        $this->to_guid = $guid;
        return $this;
    }

    /**
     * Sets the maturity flag for this activity
     * @param mixed $value
     */
    public function setMature($value)
    {
        $this->mature = (bool) $value;
        return $this;
    }

    /**
     * Gets the maturity flag
     * @return boolean
     */
    public function getMature()
    {
        return (bool) $this->mature;
    }

    /**
     * Sets if there is a paywall or not
     * @param mixed $value
     */
    public function setPayWall($value)
    {
        $this->paywall = (bool) $value;
        return $this;
    }

    /**
     * Checks if there is a paywall for this post
     * @return boolean
     */
    public function isPayWall()
    {
        return (bool) $this->paywall;
    }

    /**
     * Return the count for this entity
     * @return int
     */
    public function getImpressions()
    {
        $app = Analytics\App::_()
                ->setMetric('impression')
                ->setKey($this->guid);
        return $app->total();
    }

    /**
     * Return the count of reminds
     * @return int
     */
    public function getRemindCount()
    {
        if ($this->remind_object) {
            return \Minds\Helpers\Counters::get($this->remind_object['guid'], 'remind');
        }

        return \Minds\Helpers\Counters::get($this, 'remind');
    }
}
