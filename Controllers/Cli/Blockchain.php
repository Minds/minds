<?php
declare(ticks = 1);

/**
 * Blockchain CLI
 *
 * @author emi
 */

namespace Minds\Controllers\Cli;

use Minds\Cli;
use Minds\Core\Blockchain\EthPrice;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\Purchase\Delegates\EthRate;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Interfaces;
use Minds\Core\Util\BigNumber;

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

    public function testMul()
    {
        $manager = Di::_()->get('Blockchain\Purchase\Manager');
        $amount = "0.006868131868131868";
        $weiAmount = BigNumber::toPlain($amount, 18)->mul($manager->getEthTokenRate()); //convert to tokens
        var_dump($weiAmount);
    }

    public function setRate()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
while (true) {
        $ethereum = Di::_()->get('Blockchain\Services\Ethereum');
        $config = Di::_()->get('Config');
        $ethRate = new EthRate;
        $ethPrice = new EthPrice;
        $ethPrice->setFrom(strtotime('24 hours ago'))
            ->setTo(time())
            ->get();

        $eth = round($ethPrice->getNearestPrice(strtotime('1 minute ago')));
        $usd = 0.15;

        $rate = round($eth/$usd);
        if ($rate % 2 !== 0) {
           $rate++;
        }
        
        $txHash = $ethereum->sendRawTransaction($config->blockchain['contracts']['token_sale_event']['rate_pkey'], [
            'from' => $config->blockchain['contracts']['token_sale_event']['rate_address'],
            'to' => $config->blockchain['contracts']['token_sale_event']['contract_address'],
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'gasPrice' => BigNumber::_(25000000000)->toHex(true),
            'data' => $ethereum->encodeContractMethod('modifyRate(uint256)', [
                BigNumber::_($rate)->toHex(true),
            ])
        ]);

        // Wait until mined before updating our backend

        while (true) {
            sleep(1);
            $receipt = $ethereum->request('eth_getTransactionReceipt', [ $txHash ]);
            echo "\n Waiting for $txHash";
            if ($receipt && $receipt['status']) {
                if ($receipt['status'] !== '0x1') {
                    echo "\n$txHash failed";
                }
                break;
            }
        }

        $ethRate->set($rate);
        echo "\n Completed: new rate is $rate: $txHash";
        echo "\n Now sleeping for one hour";
        sleep(3600);
}
    }

    public function balance()
    {
        error_reporting(E_ALL);
                ini_set('display_errors', 1);

        $username = $this->getOpt('username');
        $user = new \Minds\Entities\User($username);
        var_dump($user);
        $offChainBalance = Di::_()->get('Blockchain\Wallets\OffChain\Balance');
        $offChainBalance->setUser($user);
        $offChainBalanceVal = BigNumber::_($offChainBalance->get());
        $this->out((string) $offChainBalanceVal);
    }
}
