<?php
/**
 * Reports manager
 */
namespace Minds\Core\Reports\UserReports;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Core\Reports\Manager as ReportsManager;

class Manager
{
    /** @var Repository $repository */
    private $repository;

    /** @var ElasticRepository $elasticRepository */
    private $elasticRepository;

    /** @var Delegates\NotificationDelegate $notificationDelegate */
    private $notificationDelegate;

    /** @var ReportsManager $reportsManager */
    private $reportsManager;

    public function __construct(
        $repository = null,
        $elasticRepository = null,
        $notificationDelegate = null,
        $reportsManager = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->elasticRepository = $elasticRepository ?: new ElasticRepository;
        $this->notificationDelegate = $notificationDelegate ?: new Delegates\NotificationDelegate;
        $this->reportsManager = $reportsManager ?: Di::_()->get('Moderation\Manager');
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'hydrate' => false,
        ], $opts);

        return $this->elasticRepository->getList($opts);
    }

    /**
     * Add a report
     * @param UserReport $userReport
     * @return boolean
     */
    public function add(UserReport $userReport)
    {
        // Return the latest report, or the same supplied report if none exist
        $report = $this->reportsManager->getLatestReport($userReport->getReport());

        if ($report->getState() !== 'reported' 
            && !in_array($report->getEntity()->type, [ 'user', 'group' ])
        ) {
            return true; // Already past report threshold
        } 
        
        if ($report->getState() === 'initial_jury_decided' && $report->isUpheld()) {
            return true; // Until appealed, don't accept any more reports
        }

        if ($report->getState() === 'appealed') {
            return true; // Do not accept further reports while awaiting appeal jury decision
        }

        if ($report->getState() === 'appeal_jury_decided' && $report->isUpheld()) {
            return true; // Do not accept further reports if appeal jury uphols
        }
        
        if ($report->getReasonCode() == 2
            && $report->getEntity()->getNsfw()
            && in_array($report->getSubReasonCode(), $report->getEntity()->getNsfw())
        ) {
            return true; // If the post is NSFW and tagged, do not allow report
        }

        if ($report->getState() !== 'reported') {
            $report->setTimestamp(time()); // Create a new report
        }
        
        $userReport->setReport($report);

        $this->repository->add($userReport);
        //$this->elasticRepository->add($report);
        $this->notificationDelegate->onAction($userReport);
        return true;
    }

}
