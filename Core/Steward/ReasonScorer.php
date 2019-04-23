<?php
/**
 * Minds AutoReport Reason Scorer.
*/

namespace Minds\Core\Steward;

class ReasonScorer
{
    private $reasons;

    public function __construct($reasons = [])
    {
        $this->reasons = $reasons;
    }

    public function score()
    {
        $scores = [];
        foreach ($this->reasons as $reason) {
            $key = "{$reason->getReasonCode()}.{$reason->getSubreasonCode()}";
            $scores[$key] = ($scores[$key] ?? 0) + $reason->getWeight();
        }
        uasort($scores, function ($a, $b) {
            return $b <=> $a;
        });

        if ($scores > 0) {
            reset($scores);
            $key = key($scores);
            $codes = explode('.', $key);

            return new Reason($codes[0], $codes[1], $scores[$key]);
        }
    }
}
