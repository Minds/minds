<?php
/**
 * Minds AutoReport Reasons.
 */

namespace Minds\Core\Steward;

use Minds\Traits\MagicAttributes;

class Reason
{
    use MagicAttributes;
    private $reasonCode;
    private $subreasonCode;
    private $weight;

    const REASON_ILLEGAL = 1;
    const REASON_ILLEGAL_TERRORISM = 1;
    const REASON_ILLEGAL_PAEDOPHILIA = 2;
    const REASON_ILLEGAL_EXTORTION = 3;
    const REASON_ILLEGAL_FRAUD = 4;
    const REASON_NSFW = 2;
    const REASON_NSFW_NUDITY = 1;
    const REASON_NSFW_PORNOGRAPHY = 2;
    const REASON_NSFW_PROFANITY = 3;
    const REASON_NSFW_VIOLENCE = 4;
    const REASON_NSFW_RACE = 5;
    const REASON_NSFW_OTHER = 6;
    const REASON_VIOLENCE = 3;
    const REASON_THREATENS = 4;
    const REASON_PERSONAL = 5;
    const REASON_IMPERSONATES = 7;
    const REASON_SPAM = 8;
    const REASON_COPYRIGHT = 10;
    const REASON_HASHTAGS = 12;
    const REASON_MALWARE = 13;
    const REASON_OTHER = 11;

    public function __construct($reasonCode, $subreasonCode, $weight)
    {
        $this->reasonCode = $reasonCode;
        $this->subreasonCode = $subreasonCode;
        $this->weight = $weight;
    }
}
