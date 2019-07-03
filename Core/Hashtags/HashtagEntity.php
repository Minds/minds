<?php
/**
 * Hashtag Entity
 */

namespace Minds\Core\Hashtags;

use Minds\Traits\MagicAttributes;

/**
 * Class HashtagEntity
 * @package Minds\Core\Hashtags
 * @method int getGuid()
 * @method HashtagEntity setGuid(int $value)
 * @method string getHashtag()
 * @method HashtagEntity setHashtag(string $value)
 */
class HashtagEntity
{
    use MagicAttributes;

    /** @var int $guid */
    private $guid;

    /** @var string $hashtag */
    private $hashtag;

    public function toArray()
    {
        return [
            'guid' => $this->getGuid(),
            'hashtag' => $this->getHashtag(),
        ];
    }
}
