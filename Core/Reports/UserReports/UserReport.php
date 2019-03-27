<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports\UserReports;

use Minds\Traits\MagicAttributes;

/**
 * @method Report getReport(): Report
 * @method Report getReporterGuid(): long
 * @method Report getReasonCode(): int
 * @method Report getSubReasonCode(): int
 * @method Report getTimestamp: int
 */
class UserReport
{
    use MagicAttributes;

    /** @var long $timestamp -< in ms*/
    private $timestamp;

    /** @var long $reporterGuid */
    private $reporterGuid;

    /** @var Report $report  */
    private $report;

    /** @var int $reasonCode */
    private $reasonCode;

    /** @var int $subReasonCode */
    private $subReasonCode;

    /**
     * @return array
     */
    public function export()
    {
        $export = [
            'reporter_guid' => $this->reporterGuid,
            '@timestamp' => $this->timestamp,
            'entity_guid' => $this->entityGuid,
            'entity' => $this->entity ? $this->entity->export() : null,
            'reason_code' => $this->reasonCode,
            'sub_reason_code' => $this->subReasonCode,
        ];

        return $export;
    }

}
