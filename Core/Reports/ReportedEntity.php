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
class ReportedEntity
{
    use MagicAttributes;

    /** @var long $entityGuid  */
    private $entityGuid;

    /** @var Entity $entity  */
    private $entity;

    /** @var array<Report> $reports */
    private $reports;

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
            'reports' => $this->reports ? array_map(function($report){
                return $report->export();
             }, $this->reports) : [],
             'reason_code' => $this->getReasonCode(),
             'sub_reason_code' => $this->getSubReasonCode(),
        ];

        return $export;
    }

}
