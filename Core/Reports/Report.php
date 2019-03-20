<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports;

use Minds\Core\Reports\UserReport;
use Minds\Traits\MagicAttributes;

/**
 * @method Report getEntityGuid(): long
 * @method Report getReports(): []
 * @method Report getEntity(): Entity
 */
class Report
{
    use MagicAttributes;

    /** @var long $entityGuid  */
    private $entityGuid;

    /** @var long $entityOwnerGuid */
    private $entityOwnerGuid;

    /** @var Entity $entity  */
    private $entity;

    /** @var array<UserReport> $reports */
    private $reports = [];

    /** @var array<Decisions> $initialJuryDecisions */
    private $initialJuryDecisions = [];

    /** @var int $initialJuryDecidedTimestamp */
    private $initialJuryDecidedTimestamp;

    /** @var array<Decisions> $appealJuryDecisions */
    private $appealJuryDecisions = [];

    /** @var int $appealJuryDecidedTimestamp */
    private $appealJuryDecidedTimestamp;

    /** @var string $action */
    private $action;

    /** @var boolean $appeal */
    private $appeal = false;

    /** @var int $appealTimestamp */
    private $appealTimestamp;

    /** @var string $appealNote */
    private $appealNote = '';

    /**
     * Return the reason code
     * @return int | null
     */
    public function getReasonCode()
    {
        if (!$this->reports) {
            return null;
        }
        $reason_codes = [];
        foreach ($this->reports as $report) {
            $reason_codes[$report->getReasonCode()] = ($reason_codes[$report->getReasonCode()] ?? 0) + 1;
        }
        $flipped = array_flip($reason_codes);
        rsort($flipped);
        return (int) $flipped[0];
    }

    /**
     * Return the sub reason code
     * @return int | null
     */
    public function getSubReasonCode()
    {
        if (!$this->reports) {
            return null;
        }
        $sub_reason_codes = [];
        foreach ($this->reports as $report) {
            $sub_reason_codes[$report->getSubReasonCode()] = ($reason_codes[$report->getSubReasonCode()] ?? 0) + 1;
        }
        $flipped = array_flip($sub_reason_codes);
        rsort($flipped);
        return (int) $flipped[0];
    }

    /**
     * @return array
     */
    public function export()
    {
        $export = [
            'entity_guid' => $this->entityGuid,
            'entity' => $this->entity ? $this->entity->export() : null,
            /*'reports' => $this->reports ? array_map(function($report){
                return $report->export();
             }, $this->reports) : [],*/
            'is_appeal' => (bool) $this->isAppeal(),
            'appeal_note' => $this->getAppealNote(),
            'reason_code' => $this->getReasonCode(),
            'sub_reason_code' => $this->getSubReasonCode(),
        ];

        return $export;
    }

}
