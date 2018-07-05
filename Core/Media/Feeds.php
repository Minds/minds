<?php
namespace Minds\Core\Media;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;

class Feeds
{
    /** @var Core\Data\Call */
    private $indexDb;
    /** @var Core\Data\Call */
    private $entityDb;
    /** @var Core\Events\EventsDispatcher */
    private $dispatcher;

    protected $entity;

    public function __construct($indexDb, $entityDb, $dispatcher = null)
    {
        $this->indexDb = $indexDb;
        $this->entityDb = $entityDb;
        $this->dispatcher = $dispatcher ?: Core\Di\Di::_()->get('EventsDispatcher');
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    public function createActivity()
    {
        if (!$this->entity) {
            throw new \Exception('Entity not set');
        }

        $activity = new Entities\Activity();
        $saved = $activity
            ->setCustom(...$this->entity->getActivityParameters())
            ->setFromEntity($this->entity)
            ->setTitle($this->entity->title)
            ->setBlurb($this->entity->description)
            ->save();

        if (!$saved) {
            throw new \Exception('Cannot save newsfeed activity');
        }

        Helpers\Wallet::createTransaction($this->entity->owner_guid, 15, $this->entity->guid, 'Post');

        return $activity;
    }

    public function updateActivities()
    {
        if (!$this->entity) {
            throw new \Exception('Entity not set');
        }

        foreach ($this->indexDb->getRow("activity:entitylink:{$this->entity->guid}") as $guid => $ts) {
            $this->entityDb->insert($guid, [ 'message' => $this->entity->title ]);
        }

        return true;
    }

    public function dispatch(array $targets = [])
    {
        $targets = array_merge([
            'facebook' => false,
            'twitter' => false
        ], $targets);

        $this->dispatcher->trigger('social', 'dispatch', [
            'entity' => $this->entity,
            'services' => [
                'facebook' => $targets['facebook'],
                'twitter' => $targets['twitter']
            ],
            'data' => [
                'message' => $this->entity->title,
                'thumbnail_src' => $this->entity->getIconUrl(),
                'perma_url' => $this->entity->getURL()
            ]
        ]);

        return true;
    }
}
