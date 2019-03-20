<?php
/**
 * Reported Entity Verdict
 */
namespace Minds\Core\Reports\Verdict;

use Minds\Traits\MagicAttributes;

/**
 * @method Report getReport(): Report
 * @method Report getDecisions(): array<Decision>
 * @method Report isAppeal(): boolean
 * @method Report isAccepted(): boolean
 * @method Report getAction(): string
 * @method Report getInitialJuryAction(): string
 * @method Report getTimestamp: int
 */
class Verdict
{
    use MagicAttributes;

    /** @var long $timestamp -< in ms*/
    private $timestamp;

    /** @var Report $report */
    private $report;

    /** @var boolean $accept */
    private $accepted = false;

    /** @var string $action */
    private $action;

    /** @var string $initialJuryAction */
    private $initialJuryAction;

    /**
     * Decisions
     * @return array<Decision>
     */
    public function getDecisions()
    {
        if ($this->report->isAppeal()) {
            return $this->report->getAppealJuryDecisions();
        }
        return $this->report->getInitialJuryDecisions();
    }

    /**
     * @return array
     */
    public function export()
    {
        $export = [
            'report' => $this->report->export(),
            'decisions' => array_map(function($decision){
                return $decision->export();
             }, $this->decisions),
            '@timestamp' => $this->timestamp,
            'is_appeal' => $this->isAppeal(),
            'is_accepted' => $this->accepted,
            'action' => $this->action,
        ];

        return $export;
    }

}
