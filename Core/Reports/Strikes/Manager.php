<?php
/**
 * Strikes manager
 */
namespace Minds\Core\Reports\Strikes;

use Minds\Common\Repository\Response;
use Minds\Common\Urn;
use Minds\Core\Reports\Manager as ReportsManager;
use Minds\Core\Reports\Appeals\Appeal;

class Manager
{

    const STRIKE_TIME_WINDOW = (60 * 60) * 24; // 24 hours
    //const STRIKE_TIME_WINDOW = 60;
    const STRIKE_RETENTION_WINDOW = (60 * 60) * 24 * 90; // 90 days

    /** @var Repository $repository */
    private $repository;

    /** @var ReportsManager */
    private $reportsManager;

    /** @var Delegates\EmailDelegate */
    private $emailDelegate;

    public function __construct(
        $repository = null,
        $reportsManager = null,
        $emailDelegate = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->reportsManager = $reportsManager ?: new ReportsManager();
        $this->emailDelegate = $emailDelegate ?: new Delegates\EmailDelegate();
    }

    /**
     * Return a list of strikes
     * @param array $opts
     * @return Response
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'user' => null,
            'reason_code' => null,
            'sub_reason_code' => null,
            'from' => strtotime('-90 days'),
            'to' => time(),
            'hydrate' => false,
        ], $opts);

        if (!$opts['user']) {
            throw new \Exception('User must be provided');
        }

        $opts['user_guid'] = $opts['user']->getGuid();

        $response = $this->repository->getList($opts);

        if ($opts['hydrate']) {
            $response = $response->map(function (Strike $strike) {
                try {
                    $report = $this->reportsManager->getReport($strike->getReportUrn());
                    $strike->setReport($report);

                    $appeal = new Appeal;
                    $appeal
                        ->setTimestamp($report->getAppealTimestamp())
                        ->setReport($report)
                        ->setNote($report->getAppealNote());
                    $strike->setAppeal($appeal);
                } catch (\Exception $e) { }

                return $strike;
            });
        }

        return $response;
    }

    /**
     * Add a strike to the repository
     * @param Strike $strike
     * @return bool
     */
    public function add($strike)
    {
        $success = $this->repository->add($strike);

        $this->emailDelegate->onStrike($strike);

        return $success;
    }

    /**
     * Return if a strike exists in the configured time window
     * @param Strike $strike
     * @return int
     */
    public function countStrikesInTimeWindow($strike, $window)
    {
        $strikes = $this->repository->getList([
            'user_guid' => $strike->getUserGuid(),
            'reason_code' => $strike->getReasonCode(),
            'sub_reason_code' => $strike->getSubReasonCode(),
            'from' => time() - $window,
        ]);

        return count($strikes);
    }

    /**
     * Delete a strike
     * @param Strike $strike
     * @return bool
     */
    public function delete($strike)
    {
        return $this->repository->delete($strike);
    }

}
