<?php
/**
 * Notification
 */
namespace Minds\Core\Notification;

use Minds\Traits\MagicAttributes;

/**
 * Class Notification
 * @package Minds\Core\Notification
 * @method string getUuid()
 * @method Notification setUuid(string $value)
 * @method string getToGuid()
 * @method Notification setToGuid(string $value)
 * @method string getFromGuid()
 * @method Notification setFromGuid(string $value)
 * @method string getEntityGuid()
 * @method Notification setEntityGuid(string $value)
 * @method string getEntityUrn()
 * @method Notification getEntityUrn(string $value)
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

    /** @param string $entityUrn */
    private $entityUrn;

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
     * Return the UUID of the notification
     * @return string
     */
    public function getUrn()
    {
        return "urn:notification:" . implode('-', [ $this->toGuid, $this->uuid ]);
    }

    /**
     * Export
     * @return array
     */
    public function export()
    {
        return [
            'uuid' => $this->getUuid(),
            'toGuid' => $this->getToGuid(),
            'to_guid' => $this->getToGuid(),
            'fromGuid' => $this->getFromGuid(),
            'from_guid' => $this->getFromGuid(),
            'entityGuid' => $this->getEntityGuid(),
            'entity_urn' => $this->getEntityUrn(),
            'type' => $this->getType(),
            'data' => $this->getData(),
        ];
    }

}
