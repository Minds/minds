<?php
/**
 * Strikes manager
 */
namespace Minds\Core\Reports\Strikes;

class Manager
{

    //const STRIKE_TIME_WINDOW = (60 * 60) * 24; // 24 hours
    const STRIKE_TIME_WINDOW = 60;
    const STRIKE_RETENTION_WINDOW = (60 * 60) * 24 * 90; // 90 days

    /** @var Repository $repository */
    private $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository;
    }

    /**
     * Return a list of strikes
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'user' => null,
            'reason_code' => null,
            'sub_reason_code' => null,
            'from' => strtotime('-90 days'),
            'to' => time(),
        ], $opts);

        if (!$opts['user']) {
            throw new \Exception('User must be provided');
        }

        $opts['user_guid'] = $opts['user']->getGuid();

        return $this->repository->getList($opts);
    }

    /**
     * Add a strike to the repository
     * @param Strike $strike
     * @return bool
     */
    public function add($strike)
    {
        return $this->repository->add($strike);
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

}