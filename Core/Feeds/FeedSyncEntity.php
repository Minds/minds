<?php
/**
 * FeedSyncEntity.
 *
 * @author emi
 */

namespace Minds\Core\Feeds;

use Minds\Traits\Exportable;
use Minds\Traits\MagicAttributes;

/**
 * Class FeedSyncEntity
 * @package Minds\Core\Feeds
 * @method int|string getGuid()
 * @method FeedSyncEntity setGuid(int|string $guid)
 * @method int|string getOwnerGuid()
 * @method FeedSyncEntity setOwnerGuid(int|string $ownerGuid)
 * @method int getTimestamp()
 * @method FeedSyncEntity setTimestamp(int $timestamp)
 * @method string getUrn()
 * @method FeedSyncEntity setUrn(string $urn)
 */
class FeedSyncEntity
{
    use MagicAttributes;

    /** @var int|string */
    protected $guid;

    /** @var int|string */
    protected $ownerGuid;

    /** @var int */
    protected $timestamp;

    /** @var string */
    protected $urn;

    /** @var Entity */
    protected $entity;

    /**
     * Export to public API
     * @return array
     */
    public function export()
    {
        return [
            'guid' => (string) $this->guid,
            'owner_guid' =>  (string) $this->ownerGuid,
            'timestamp' => $this->timestamp,
            'urn' => $this->urn,
            'entity' => $this->entity ? $this->entity->export() : null,
        ];
    }
}
