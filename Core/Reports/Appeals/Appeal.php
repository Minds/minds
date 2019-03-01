<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports\Appeals;

use Minds\Traits\MagicAttributes;

/**
 * @method Report getOwnerGuid(): long
 * @method Report getReport(): Report
 * @method Report getTimestamp: int
 * @method Report getNote(): int
 */
class Appeal
{
    use MagicAttributes;

    /** @var long $timestamp -< in ms*/
    private $timestamp;

    /** @var long $ownerGuid */
    private $ownerGuid;

    /** @var Report $report  */
    private $report;

    /** @var int $note */
    private $note;

    /**
     * @return array
     */
    public function export()
    {
        $export = [
            'owner_guid' => $this->ownerGuid,
            '@timestamp' => $this->timestamp,
            'report' => $this->report ? $this->report->export() : null,
            'note' => $this->note,
        ];

        return $export;
    }

}
