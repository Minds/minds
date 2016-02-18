<?php
namespace Minds\Core\Notification\Settings;

use Minds\Entities;
use Minds\Core\Session;
use Minds\Core\Data;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\Event;
use Minds\Core\Notification\Extensions\Push;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Core\Notification\Factory as NotificationFactory;

class PushSettings
{

    protected $types = [
      'daily' => true,
      'comment' => true,
      'like' => true,
      'tag' => true,
      'friends' => true,
      'remind' => true,
      'boost_gift' => true,
      'friends' => true,
      'remind' => true,
      'boost_gift' => true,
      'boost_request' => true,
      'boost_accepted' => true,
      'boost_rejected' => true,
      'boost_completed' => true
    ];

    protected $toBeSaved = [];

    public function __construct($db = null)
    {
        $this->db = $db ?: new Data\Call('entities_by_time');
    }

    /**
     * Return toggles for notifications
     * @return array
     */
    public function getToggles()
    {
        $types = $this->db->getRow('settings:push:toggles:' . Session::getLoggedInUser()->guid);
        foreach($types as $toggle => $value){
            $this->types[$toggle] = (bool) $value;
        }
        return $this->types;
    }

    /**
     * Sets an individual toggle
     * @return $this
     */
    public function setToggle($toggle, $value)
    {
        $this->types[$toggle] = $value;
        $this->toBeSaved[$toggle] = $value;
        return $this;
    }

    /**
     * Batch sets toggles
     * @return this
     */
    public function setToggles($toggles = [])
    {
        $this->types = array_merge($this->types, $toggles);
        $this->toBeSaved = $toggles;
        return $this;
    }

    /**
     * Saves notifications
     * @return array
     */
    public function save()
    {
        $this->db->insert('settings:push:toggles:' . Session::getLoggedInUser()->guid, $this->toBeSaved);
        $this->toBeSaved = [];
        return $this;
    }

}
