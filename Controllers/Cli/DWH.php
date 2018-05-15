<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Entities;

class DWH extends Cli\Controller implements Interfaces\CliControllerInterface
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
        $this->out('Missing subcommand');
    }

    public function signups()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->out('Collecting signups data');
        $db = new Core\Data\Call('entities_by_time');
        $limit = 100;
        $offset = "";
        Core\Security\ACL::$ignore = false;
        $i=0;
        while(true){
            echo "\n[$offset]: ";
            $users = Core\Entities::get(['type'=>'user', 'limit' => $limit, 'offset'=>$offset]);
            foreach($users as $user){
                $i++;
                $joined = date('d-m-Y h:i', $user->time_created);
                echo "\n[$i/$joined]: $user->guid"; 
                $ts = Core\Analytics\Timestamps::get(['day', 'month'], $user->time_created);
                //$db->insert("analytics:signup:day:{$ts['day']}", [$user->guid => $user->time_created]);
                //$db->insert("analytics:signup:month:{$ts['month']}", [$user->guid => $user->time_created]);
            }
            if(count($users) < $limit/2){
                //break;
            }
            $offset = end($users)->guid;
            echo "\n\t" . date('d-m-Y h:i', end($users)->time_created) . "\n";
            //break;
        }
        $this->out('Done.');
    }

    public function retention()
    {
        $this->out('Calculating retention rates.. this may take a moment');
        $app = Core\Analytics\App::_()
          ->setMetric('retention')
          ->increment();
        $this->out('Done.');
    }

}
