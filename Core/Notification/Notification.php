<?php
/**
 * Notification
 */
namespace Minds\Core\Notification;

use Minds\Traits\MagicAttributes;

class Notification
{
    use MagicAttributes;

    /** @param string $uuid */
    private $uuid;

    /** @param string $toGuid */
    private $toGuid;

    /** @param string $fromGuid */
    private $fromGuid;

    /** @param string $entityGuid */
    private $entityGuid;

    /** @param string $type */
    private $type;

    /** @param array $data */
    private $data;

    /** @var string $batchId */
    private $batchId;

    /**
     * Export
     * @return array
     */
    public function export()
    {
        return [
            'toGuid' => $this->getToGuid(),
            'fromGuid' => $this->getFromGuid(),
            'entityGuid' => $this->getEntityGuid(),
            'type' => $this->getType(),
            'data' => $this->getData(),
        ];
    }

}
