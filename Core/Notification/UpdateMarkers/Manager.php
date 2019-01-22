<?php
/**
 * Update markers - a quick way to know if entities (groups)
 * have updates worth checking
 */
namespace Minds\Core\Notification\UpdateMarkers;

use Minds\Core\Sockets;

class Manager
{

    public function __construct($repository = null, $sockets = null)
    {
        $this->repository = $repository ?: new Repository;
        $this->sockets = $sockets ?: new Sockets\Events;
    }

    public function getList($opts = [])
    {
        $opts = array_merge([
            'user_guid' => null,
            'entity_type' => 'group',
            'entity_guid' => null,
            'entity_guids' => [ ],
            'marker' => null,
        ], $opts);

        return $this->repository->getList($opts);
    }

    /**
     * Add a marker entry to the database
     * @param UpdateMarker $marker
     * @return bool
     */
    public function add(UpdateMarker $marker)
    {
        return $this->repository->add($marker);
    }

    public function pushToSocketRoom(UpdateMarker $marker)
    {
        $this->sockets
          ->setRoom("marker:{$marker->getEntityGuid()}")
          ->emit("marker:{$marker->getEntityGuid()}", json_encode($marker->export()));
    }

}
