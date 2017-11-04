<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Entities;

class AbuseGuard extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        $this->out('Usage: cli abuseguard [run]');
    }

    /**
     * Keep a thread open that keeps checking for new actions
     * Runs every x seconds
     */
    public function run()
    {
        $guard = new Core\Security\AbuseGuard();
        $interval = $this->getOpt('interval') ?: 5;

        while (true) {
            
            $guard->setPeriod(
                time() - (60 * 60 * 10),
                time()
            );

            $guard->getScores();

            $guard->ban();

            $guard->recover();
            
            $this->out("{$guard->getTotalAccused()}/{$guard->getTotal()} [done]");

            sleep($interval);
        }
  
    }

    public function sync_single()
    {
        $entity = @Entities\Factory::build($this->getOpt('guid'));
        $sync = new Core\Search\InteractionsSync();
        $sync->sync($entity);
    }
}
