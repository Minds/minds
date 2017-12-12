<?php
declare(ticks = 1);

/**
 * Blockchain CLI
 *
 * @author emi
 */

namespace Minds\Controllers\Cli;

use Minds\Cli;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Interfaces;

class Blockchain extends Cli\Controller implements Interfaces\CliControllerInterface
{
    protected $ethActiveFilter;

    /**
     * Echoes $commands (or overall) help text to standard output.
     * @param  string|null $command - the command to be executed. If null, it corresponds to exec()
     * @return null
     */
    public function help($command = null)
    {
        $this->out('Usage: cli blockchain [listen]');
    }

    /**
     * Executes the default command for the controller.
     * @return mixed
     */
    public function exec()
    {
        $this->help();
    }

    public function listen()
    {
        if (function_exists('pcntl_signal')) {
            // Intercept Ctrl+C

            pcntl_signal(SIGINT, function() {
                $this->filterCleanup();
                exit;
            });
        }

        \Minds\Core\Events\Defaults::_();

        $ethereum = Di::_()->get('Blockchain\Services\Ethereum');

        $topics = Dispatcher::trigger('blockchain:listen', 'all', [], []);
        $filterOptions = [
            'topics' => [ array_keys($topics) ] // double array = OR
        ];

        $from = $this->getOpt('from');

        if ($from) {
            $filterOptions['fromBlock'] = $from;
        }

        $filterId = $ethereum
            ->request('eth_newFilter', [ $filterOptions ]);

        if (!$filterId) {
            $this->out('Filter could not be set');
            exit(1);
        }

        $this->ethActiveFilter = $filterId;

        while (true) {
            $logs = $ethereum
                ->request('eth_getFilterChanges', [ $filterId ]);

            if (!$logs) {
                sleep(1);
                continue;
            }

            foreach ($logs as $log) {
                $namespace = 'all';

                $this->out('Block ' . $log['blockNumber']);

                if (!isset($log['topics'])) {
                    $this->out('No topics. Skipping…');
                    continue;
                }

                foreach ($log['topics'] as $topic) {
                    if (isset($topics[$topic])) {
                        try {
                            (new $topics[$topic]())->event($topic, $log);
                        } catch (\Exception $e) {
                            $this->out('[Topic] ' . $e->getMessage());
                            continue;
                        }
                    }
                }

            }
            
            usleep(500 * 1000); // 500ms
        }

        $this->filterCleanup();
    }

    protected function filterCleanup()
    {
        $ethereum = Di::_()->get('Blockchain\Services\Ethereum');

        if ($this->ethActiveFilter) {
            $done = $ethereum
                ->request('eth_uninstallFilter', [ $this->ethActiveFilter ]);

            if ($done) {
                $this->out(['', 'Cleaned up filter…', $this->ethActiveFilter]);
            }

            $this->ethActiveFilter = null;
        }
    }
}
