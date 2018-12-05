<?php
/**
 * Notification
 */
namespace Minds\Core\Notification;

use Minds\Traits\MagicAttributes;

/**
 * Class Notification
 * @package Minds\Core\Notification
 * @method string getUUID()
 * @method Notification setUUID(string $value)
 * @method string getToGuid()
 * @method Notification setToGuid(string $value)
 * @method string getFromGuid()
 * @method Notification setFromGuid(string $value)
 * @method string getEntityGuid()
 * @method Notification setEntityGuid(string $value)
 * @method string getType()
 * @method Notification setType(string $value)
 * @method array getData()
 * @method Notification setData($value)
 * @method string getBatchId()
 * @method Notification setBatchId(string $value)
 * @method int getCreatedTimestamp()
 * @method Notification setCreatedTimestamp(int $value)
 * @method int getReadTimestamp()
 * @method Notification setReadTimestamp(int $value)
 */
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

    /** @var int $createdTimestamp */
    private $createdTimestamp;

    /** @var int $readTimestamp */
    private $readTimestamp;

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
