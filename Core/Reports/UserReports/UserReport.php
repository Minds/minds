<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports\UserReports;

use Minds\Entities\Report;
use Minds\Traits\MagicAttributes;

/**
 * @method Report getReport()
 * @method string getReportUrn()
 * @method int getReporterGuid()
 * @method string getReporterHash()
 * @method int getReasonCode()
 * @method int getSubReasonCode()
 * @method int getTimestamp()
 */
class UserReport
{
    use MagicAttributes;

    /** @var int $timestamp -< in ms*/
    private $timestamp;

    /** @var int $reporterGuid */
    private $reporterGuid;

    /** @var int $reporterHash */
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
