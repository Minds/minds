<?php
/**
 * Update markers - a quick way to know if entities (groups)
 * have updates worth checking
 */
namespace Minds\Core\Notification\UpdateMarkers;

class Manager
{

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository;
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

}
