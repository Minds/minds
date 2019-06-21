<?php

namespace Minds\Controllers\Cli;

use Elasticsearch\Common\Exceptions\ServerErrorResponseException;
use Minds\Cli;
use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;

class Analytics extends Cli\Controller implements Interfaces\CliControllerInterface
{
    private $start;
    private $elasticsearch;

    public function help($command = null)
    {
        switch ($command) {
            case 'sync_activeUsers':
                $this->out('Indexes user activity by guid and counts per day');
                $this->out('--from={timestamp} the day to start counting. Default is yesterday at midnight');
                $this->out('--to={timestamp} the day to stop counting. Default is yesterday at midnight');
                $this->out('--rangeOffset={number of days} the number of days to look back into the past. Default is 7');
                $this->out('--mode={silent | notify} silent mode does not send emails when running batches to re-index. Notify sends the notifications. Default is notify');
                break;
            case 'sync_graphs':
                $this->out('sync graphs between es and cassandra');
                break;
            case 'counts':
                $this->out('Prints the counts of a user');
                $this->out('--from={timestamp in milliseconds} the day to start count. Default is yesterday');
                $this->out('--guid={user guid} REQUIRED the user to aggregate');
            // no break
            default:
                $this->out('Syntax usage: cli analytics <type>');
                $this->displayCommandHelp();
        }
    }

    public function exec()
    {
        $this->help();
    }

    public function sync_activeUsers()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $from = (strtotime('midnight', $this->getOpt('from')) ?: strtotime('midnight yesterday'));
        $to = (strtotime('midnight', $this->getOpt('to')) ?: strtotime('midnight yesterday'));
        $rangeOffset = getopt('rangeOffset') ?: 7;
        $mode = strtolower($this->getOpt('mode')) ?: 'notify';
        $this->out('Collecting user activity');
        $this->out("Running in {$mode} mode");
        while ($from <= $to) {
            $this->out('Syncing for ' . gmdate('c', $from));
            $manager = new Core\Analytics\UserStates\Manager();
            $manager->setReferenceDate($from)
                ->setRangeOffset($rangeOffset)
                ->sync();
            if ($mode === 'notify') {
                $this->out('Sending notifications');
                $manager->emitStateChanges();
            }
            $from = strtotime('+1 day', $from);
        }
        $this->out('Completed syncing user activity');
    }

    public function counts()
    {
        $interval = $this->getOpt('interval') ?: 'day';
        $from = $this->getOpt('from') ?: (strtotime('-24 hours') * 1000);
        $user = new Entities\User();
        $user->guid = $this->getOpt('guid');

        $manager = new Core\Analytics\Manager();
        $manager->setUser($user)
            ->setFrom($from)
            ->setInterval($interval);
        $result = $manager->getCounts();

        var_dump($result);
    }

    public function sync_graphs()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        /** @var Core\Analytics\Graphs\Manager $manager */
        $manager = Core\Di\Di::_()->get('Analytics\Graphs\Manager');

        $aggregates = [
            'avgpageviews',
//            'interactions',
            'offchainboosts',
            'onchainboosts',
            'offchainplus',
            'onchainplus',
            'offchainwire',
            'onchainwire',
            'activeusers',
            'posts',
            'votes',
            'comments',
            'reminds',
            //'subscribers',
            'totalpageviews',
            'usersegments',
            'pageviews',
            'withdraw',
            'tokensales',
            'rewards',
        ];

        if ($this->getOpt('aggregate')) {
            $aggregates = [ $this->getOpt('aggregate') ];
        }

        foreach ($aggregates as $aggregate) {
            $this->out("Syncing {$aggregate}");

            try { 
                $manager->sync([
                    'aggregate' => $aggregate,
                    'all' => true,
                ]);
            } catch (\Exception $e) {
            }
        }

        $this->out('Completed caching site metrics');
   }

    public function syncViews()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $from = $this->getOpt('from') ?: strtotime('-7 days');

        $opts = [
            'from' => $from,
            'day' => (int) date('d', $from),
        ];

        $manager = new Core\Analytics\Views\Manager();

        $i = 0;
        $start = time();
        foreach ($manager->syncToElastic($opts) as $view) {
            $time = (new \Cassandra\Timeuuid($view->getUuid()))->time();
            $date = date('d-m-Y h:i', $time);
            $rps = (++$i) / ((time() - $start) ?: 1);
            $this->out($i . "-{$view->getUuid()} {$date} ($rps/sec)");
        }
        $this->out('Done');
    }

}
