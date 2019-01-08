<?php
/**
 * Update Marker model
 */
namespace Minds\Core\Notification\UpdateMarkers;

use Minds\Traits\MagicAttributes;

class UpdateMarker
{
    use MagicAttributes;

    /** @var $userGuid */
    private $userGuid;

    /** @var $fromGuid */
    private $fromGuid;

    /** @var $entityType */
    private $entityType;

    /** @var $entityGuid */
    private $entityGuid;

    /** @var $marker */
    private $marker;

    /** @var $updateTimestamp */
    private $updatedTimestamp;

    /** @var $readTimestamp */
    private $readTimestamp;

    public function export()
    {
        return [
            'user_guid' => $this->userGuid,
            'entity_type' => $this->entityType,
            'entity_guid' => $this->entityGuid,
            'marker' => $this->marker,
            'updated_timestamp' => $this->updatedTimestamp,
            'read_timestamp' => $this->readTimestamp,
        ];
    }

}
