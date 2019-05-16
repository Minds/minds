<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports\Appeals;

use Minds\Core\Reports\Report;
use Minds\Traits\MagicAttributes;

/**
 * @method int|string getOwnerGuid()
 * @method Report getReport()
 * @method Appeal setReport(Report $report)
 * @method int getTimestamp
 * @method string getNote()
 */
class Appeal
{
    use MagicAttributes;

    /** @var int $timestamp -< in ms*/
    private $timestamp;

    /** @var Report $report  */
    private $report;

    /** @var string $note */
    private $note;

    /**
     * @return array
     */
    public function export()
    {
        $export = [
            '@timestamp' => $this->timestamp,
            'report' => $this->report ? $this->report->export() : null,
            'note' => $this->note,
        ];

        return $export;
    }

}
