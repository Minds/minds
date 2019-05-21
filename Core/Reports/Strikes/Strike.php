<?php
/**
 * Moderation Strikes
 */
namespace Minds\Core\Reports\Strikes;

use Minds\Traits\MagicAttributes;

/**
 * Class Strike
 * @method Strike getUserGuid(): long
 * @method Strike getTimestamp(): int
 * @method Strike getReasonCode(): float
 * @method Strike getSubReasonCode(): float
 * @method Strike getReportUrn(): string
 */
class Strike
{
    use MagicAttributes;

    /** @var long $userGuid */
    private $userGuid;

    /** @var int $timestamp */
    private $timestamp;

    /** @var float $reasonCode */
    private $reasonCode;

    /** @var float $subReasonCode */
    private $subReasonCode;

    /** @var string $reportUrn */
    private $reportUrn;

    /** @var Report $report */
    private $report;

    /**
     * Return preferred urn
     * `urn:strike:123-512949505000-2-5`
     * @return string
     */
    public function getUrn()
    {
        return "urn:strike:" . implode('-', [
            $this->userGuid,
            $this->timestamp,
            $this->reasonCode,
            $this->subReasonCode,
        ]);
    }

    /**
     * Export
     * @return array
     */
    public function export()
    {
        $output = [
            'report_urn' => $this->reportUrn,
            'user_guid' => (string) $this->userGuid,
            'reason_code' => $this->reasonCode,
            'sub_reason_code' => $this->subReasonCode,
            '@timestamp' => $this->timestamp,
        ];

        if ($this->report) {
            $output['report'] = $this->report->export();
        }

        return $output;
    }

}
