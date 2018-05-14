<?php
/**
 * Cassandra client
 */
namespace Minds\Core\Data\Cassandra;

use Cassandra as Driver;
use Minds\Core;
use Minds\Core\Data\Interfaces;
use Minds\Core\Config;
use Minds\Core\Events\Dispatcher;

class Client implements Interfaces\ClientInterface
{
    private $cluster;
    private $session;
    private $prepared;
    protected $debug;

    public function __construct(array $options = array())
    {
        $options = array_merge((array) Config::_()->cassandra, $options);
        $retry_policy = new Driver\RetryPolicy\DowngradingConsistency();

        $this->cluster = Driver::cluster()
           ->withContactPoints(... $options['cql_servers'])
           ->withLatencyAwareRouting(true)
           ->withDefaultConsistency(Driver::CONSISTENCY_QUORUM)
           ->withRetryPolicy(new Driver\RetryPolicy\Logging($retry_policy))
           ->withPort(9042)
           ->build();
        $this->session = $this->cluster->connect($options['keyspace']);

        $this->debug = (bool) Core\Di\Di::_()->get('Config')->get('minds_debug');
    }

    public function request(Interfaces\PreparedInterface $request, $silent = false)
    {
        $cql = $request->build();
        try{
            $statement = $this->session->prepare($cql['string']);
            $future = $this->session->executeAsync(
              $statement,
              new Driver\ExecutionOptions(array_merge(
                  [
                    'arguments' => $cql['values']
                  ],
                  $request->getOpts()
                  ))
            );
            if ($silent) {
                return $future;
            } else {
                return $response = $future->get();
            }
        }catch(\Exception $e){
            if ($this->debug) {
                error_log('[CQL Error: ' . get_class($e) . '] ' . $e->getMessage());
                error_log(json_encode($cql));
            }
            return false;
        }

        return true;
    }

    public function batchRequest($requests = array(), $batchType = Driver::BATCH_COUNTER, $silent = false)
    {
        $batch = new Driver\BatchStatement($batchType);

        foreach ($requests as $request) {
            $cql = $request;
            $statement = $this->session->prepare($cql['string']);
            $batch->add($statement, $cql['values']);
        }

        if ($silent) {
            return $this->session->executeAsync($batch);
        }

        return $this->session->execute($batch);
    }

    public function getPrefix()
    {
        return Config::_()->get('multi')['prefix'];
    }
}
