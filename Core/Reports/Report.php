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
        arsort($reason_codes);
        return (int) key($reason_codes);
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
            $sub_reason_codes[$report->getSubReasonCode()] = ($sub_reason_codes[$report->getSubReasonCode()] ?? 0) + 1;
        }
        arsort($sub_reason_codes);
        return (int) key($sub_reason_codes);
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
