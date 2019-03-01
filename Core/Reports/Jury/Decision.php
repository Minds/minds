<?php
/**
 * Reported Entity Decision
 */
namespace Minds\Core\Reports\Jury;

use Minds\Traits\MagicAttributes;

/**
 * @method Report getReport(): Report
 * @method Report getJurorGuid(): long
 * @method Report isAppeal(): boolean
 * @method Report getAction(): string
 * @method Report getTimestamp: int
 */
class Decision
{
    use MagicAttributes;

    /** @var long $timestamp -< in ms*/
    private $timestamp;

    /** @var long $jurorGuid */
    private $jurorGuid;

    /** @var Report $report  */
    private $report;

    /** @var boolean $appeal */
    private $appeal;

    /** @var string $action */
    private $action;

    /**
     * @return array
     */
    public function export()
    {
        $export = [
            'juror_guid' => $this->jurorGuid,
            '@timestamp' => $this->timestamp,
            'report' => $this->report ? $this->report->export() : null,
            'action' => $this->action,
            'is_appeal' => $this->isAppeal(),
        ];

        return $export;
    }

}
