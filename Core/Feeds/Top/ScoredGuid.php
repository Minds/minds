<?php
/**
 * ScoredGuid
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Minds\Core\Feeds\Top;


use Minds\Traits\MagicAttributes;

/**
 * Class ScoredGuid
 * @package Minds\Core\Feeds\Top
 * @method int|string getGuid()
 * @method ScoredGuid setGuid(int|string $guid)
 * @method float getScore()
 * @method int|string getOwnerGuid()
 * @method ScoredGuid setOwnerGuid(int|string $ownerGuid)
 * @method int getTimestamp()
 * @method ScoredGuid setTimestamp(int $timestamp)
 */
class ScoredGuid
{
    use MagicAttributes;

    /** @var int|string */
    protected $guid;

    /** @var float */
    protected $score;

    /** @var int|string */
    protected $ownerGuid;

    /** @var int */
    protected $timestamp;

    /**
     * @param $score
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = (float) $score;
        return $this;
    }
}
