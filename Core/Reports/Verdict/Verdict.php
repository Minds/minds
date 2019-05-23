<?php
/**
 * Reported Entity Verdict
 */
namespace Minds\Core\Reports\Verdict;

use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Report;
use Minds\Traits\MagicAttributes;

/**
 * @method Report getReport()
 * @method boolean isUphold()
 * @method string getAction()
 * @method string getInitialJuryAction()
 * @method int getTimestamp()
 */
class Verdict
{
    use MagicAttributes;

    /** @var long $timestamp -< in ms*/
    private $timestamp;

    /** @var Report $report */
    private $report;

    /** @var boolean $uphold */
    private $uphold = false;

    /** @var string $action */
    private $action;

    /** @var string $initialJuryAction */
    private $initialJuryAction;

    /**
     * Decisions
     * @return Decision[]
     */
    public function getDecisions()
    {
        if ($this->report->isAppeal()) {
            return $this->report->getAppealJuryDecisions();
        }
        return $this->report->getInitialJuryDecisions();
    }

    /**
     * Is Appeal
     * @return bool
     */
    public function isAppeal()
    {
        return (bool) $this->report->isAppeal();
    }

    /**
     * Return if the report is upheld
     * @return bool
     */
    public function isUpheld()
    {
        return $this->uphold;
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
