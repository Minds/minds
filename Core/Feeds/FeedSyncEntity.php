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
 * @method string getUrn()
 * @method FeedSyncEntity setUrn(string $urn)
 */
class FeedSyncEntity
{
    use MagicAttributes;
    use Exportable;

    /** @var int|string */
    protected $guid;

    /** @var int|string */
    protected $ownerGuid;

    /** @var string */
    protected $urn;

    /**
     * Specifies the exportable properties
     * @return array<string|\Closure>
     */
    public function getExportable()
    {
        return [
            'urn',
            'guid',
            'ownerGuid',
        ];
    }
}
