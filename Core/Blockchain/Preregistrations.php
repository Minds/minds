<?php

/**
 * Blockchain pre-registrations
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Entities\User;

class Preregistrations
{
    /** @var Client $cql */
    protected $cql;

    /**
     * Preregistrations constructor.
     * @param null $cql
     */
    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function register(User $user)
    {
        if (!$user || !$user->guid) {
            throw new \Exception('User is required');
        }

        $query = new Custom();
        $query->query("INSERT INTO entities_by_time (key, column1, value) VALUES (?, ?, ?)", [
            'blockchain:preregistrations',
            (string) $user->guid,
            (string) time()
        ]);

        return (bool) $this->cql->request($query);
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function isRegistered(User $user)
    {
        if (!$user || !$user->guid) {
            throw new \Exception('User is required');
        }

        $query = new Custom();
        $query->query("SELECT COUNT(*) from entities_by_time WHERE key = ? AND column1 = ?", [
            'blockchain:preregistrations',
            (string) $user->guid
        ]);

        $result = $this->cql->request($query);

        if (!$result) {
            throw new \Exception('Error getting count');
        }

        $count = (int) $result[0]['count'];

        return $count > 0;
    }
}
