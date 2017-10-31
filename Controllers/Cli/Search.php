<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Entities;

class Search extends Cli\Controller implements Interfaces\CliControllerInterface
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
        $this->out('Usage: cli search [set_mappings]');
    }

    public function set_mappings()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->out('Setting up mappings…');

        $provisioner = Di::_()->get('Search\Provisioner');
        $provisioner->setUp();

        $this->out('Done!');
    }

    public function index()
    {
    }

    public function sync_interactions()
    {
        $mins = $this->getOpt('minutes') ?: 30;

        $events = new Core\Analytics\Iterators\EventsIterator();
        $events->setType('action');
        $events->setPeriod(time() - ($mins * 60));
        $events->setTerms([ 'entity_guid.keyword' ]);

        $sync = new Core\Search\InteractionsSync();

        foreach ($events as $guid) {
            $entity = @Entities\Factory::build($guid);
            if (!$entity) {
                $this->out("$guid [not found]");
                continue;
            }
            $sync->sync($entity);
            $this->out("$entity->guid [done]");
        }
  
    }

    public function sync_single()
    {
        $entity = @Entities\Factory::build($this->getOpt('guid'));
        $sync = new Core\Search\InteractionsSync();
        $sync->sync($entity);
    }
}
