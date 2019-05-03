<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports;

use Minds\Core\Reports\UserReport;
use Minds\Traits\MagicAttributes;

/**
 * Class Report
 * @method Report getEntityGuid(): long
 * @method Report getReports(): []
 * @method Report getEntity(): Entity
 * @method Report isAppeal(): boolean
 * @method Report getInitialJuryDecisions: []
 * @method Report getAppealJuryDecisions: []
 * @method Report getReasonCode(): int
 * @method Report getSubReasonCode(): int
 */
class Report
{
    use MagicAttributes;

    /** @var long $entityGuid  */
    private $entityGuid;

    /** @var string $entityUrn */
    private $entityUrn;

    /** @var long $entityOwnerGuid */
    private $entityOwnerGuid;

    /** @var Entity $entity  */
    private $entity;

    /** @var int $timestamp */
    private $timestamp;

    /** @var array<UserReport> $reports */
    private $reports = [];

    /** @var array<Decisions> $initialJuryDecisions */
    private $initialJuryDecisions = [];

    /** @var array<Decisions> $appealJuryDecisions */
    private $appealJuryDecisions = [];

    /** @var boolean $uphold */
    private $uphold;

    /** @var boolean $appeal */
    private $appeal = false;

    /** @var int $appealTimestamp */
    private $appealTimestamp;

    /** @var string $appealNote */
    private $appealNote = '';

    /** @var int $reasonCode */
    private $reasonCode;

    /** @var int $subReasonCode */
    private $subReasonCode;

    /**
     * Return the URN of this case
     * @return string
     */
    public function getUrn()
    {
        $parts = [
            "({$this->getEntityUrn()})",
            $this->getReasonCode(),
            $this->getSubReasonCode(),
            $this->getTimestamp(),
        ];
        return "urn:report:" . implode('-', $parts);
    }

    /**
     * @return array
     */
    public function export()
    {
        $export = [
            'urn' => $this->getUrn(),
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
