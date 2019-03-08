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
 * @method int getGuid()
 * @method ScoredGuid setGuid(int $guid)
 * @method float getScore()
 */
class ScoredGuid
{
    use MagicAttributes;

    /** @var int */
    protected $guid;

    /** @var float */
    protected $score;

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
