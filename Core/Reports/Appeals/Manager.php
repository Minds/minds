<?php
/**
 * Appeals manager
 */
namespace Minds\Core\Reports\Appeals;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var NotificationDelegate $notificationDelegate */

    public function __construct(
        $repository = null,
        $notificationDelegate = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->notificationDelegate = $notificationDelegate ?: new Delegates\NotificationDelegate;
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

        return $this->repository->getList($opts);
    }

    /**
     * Appeal
     * @param Appeal $appeal
     * @return boolean
     */
    public function appeal(Appeal $appeal)
    {
        $added = $this->repository->add($appeal);

        $this->notificationDelegate->onAction($appeal);

        return $added;
    }

}
