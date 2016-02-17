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
        $this->types = array_merge($this->types, $this->db->getRow('settings:push:toggles:' . Session::getLoggedInUser()->guid) ?: []);
        return $this->types;
    }

    /**
     * Sets an individual toggle
     * @return $this
     */
    public function setToggle($toggle, $value)
    {
        $this->types[$toggle] = $value;
        return $this;
    }

    /**
     * Batch sets toggles
     * @return this
     */
    public function setToggles($toggles = [])
    {
        $this->types = array_merge($this->types, $toggles);
        return $this;
    }

    /**
     * Saves notifications
     * @return array
     */
    public function save()
    {
        $this->db->insert('settings:push:toggles:' . Session::getLoggedInUser()->guid, $this->types);
        return $this;
    }

}
