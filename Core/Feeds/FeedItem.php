<?php

/**
 * Feed Item Entity
 *
 * @author emi
 */

namespace Minds\Core\Feeds;

use Minds\Core\Luid;
use Minds\Traits\MagicAttributes;

/**
 * Class FeedItem
 * @package Minds\Core\Feeds
 * @method FeedItem setType(string $value)
 * @method string getType()
 * @method FeedItem setSubtype(string $value)
 * @method string getSubtype()
 * @method FeedItem setContainerGuid(int $value)
 * @method int getContainerGuid()
 * @method FeedItem setFeed(string $value)
 * @method string getFeed()
 * @method FeedItem setGuid(int $value)
 * @method int getGuid()
 * @method FeedItem setLuid(Luid|string $value)
 */
class FeedItem
{
    use MagicAttributes;

    /** @var string */
    protected $type;

    /** @var string */
    protected $subtype;

    /** @var int */
    protected $containerGuid;

    /** @var string */
    protected $feed;

    /** @var int */
    protected $guid;

    /** @var Luid */
    protected $luid;

    /**
     * @return Luid|null
     * @throws \Minds\Exceptions\InvalidLuidException
     */
    public function getLuid()
    {
        if ($this->luid && !($this->luid instanceof Luid)) {
            return new Luid($this->luid);
        }

        return $this->luid ?: null;
    }
}
