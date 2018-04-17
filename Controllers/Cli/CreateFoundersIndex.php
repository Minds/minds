<?php

namespace Minds\Controllers\Cli;

use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Cli;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;
use Minds\Core;
use Minds\Core\Analytics\Iterators\SignupsOffsetIterator;

class CreateFoundersIndex extends Cli\Controller implements Interfaces\CliControllerInterface
{

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);        
        $db = Di::_()->get('Database\Cassandra\Cql');
        $entities_by_time = new Core\Data\Call('entities_by_time');

        $users = new SignupsOffsetIterator;
        $users->setOffset($this->getOpt('offset') ?: '');

        $i = 0;
        foreach ($users as $user) {
            $i++;
            echo "\n[$i]:$user->guid";
            if ($user->founder) {
                echo "\n[$i]:$user->guid indexed";
                $entities_by_time->insert('user:founders',  [ (string) $user->guid => (string) $user->guid ]);
            }
        }

    }
}
