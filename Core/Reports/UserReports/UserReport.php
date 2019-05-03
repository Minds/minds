<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports\UserReports;

use Minds\Traits\MagicAttributes;

/**
 * @method UserReport getReport(): Report
 * @method UserReport getReportUrn(): string
 * @method UserReport getReporterGuid(): long
 * @method UserReport getReporterHash(): string
 * @method UserReport getReasonCode(): int
 * @method UserReport getSubReasonCode(): int
 * @method UserReport getTimestamp: int
 */
class UserReport
{
    use MagicAttributes;

    /** @var long $timestamp -< in ms*/
    private $timestamp;

    /** @var long $reporterGuid */
    private $reporterGuid;

    /** @var long $reporterHash */
    private $reporterHash;

    /** @var Report $report  */
    private $report;

    /** @var string $reportUrn */
    private $reportUrn;

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
            'report_urn' => $this->reportUrn,
            '@timestamp' => $this->timestamp,
            'reason_code' => $this->reasonCode,
            'sub_reason_code' => $this->subReasonCode,
        ];

        return $export;
    }

}
