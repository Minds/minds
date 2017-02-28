<?php
/**
 * Cassandra client
 */
namespace Minds\Core\Data\Cassandra;

use Cassandra as Driver;
use Minds\Core;
use Minds\Core\Data\Interfaces;
use Minds\Core\Config;

class Client implements Interfaces\ClientInterface
{
    private $cluster;
    private $session;
    private $prepared;
    protected $debug;

    public function __construct(array $options = array())
    {
        $options = array_merge((array) Config::_()->cassandra, $options);

        $this->cluster = Driver::cluster()
           ->withContactPoints(... $options['cql_servers'])
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
              new Driver\ExecutionOptions([
                  'arguments' => $cql['values']
              ])
            );
            if (!$silent) {
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

    public function batchRequest($requests = array())
    {
        $batch = new Driver\BatchStatement(Driver::BATCH_COUNTER);

        foreach ($requests as $request) {
            $cql = $request;
            $statement = $this->session->prepare($cql['string']);
            $batch->add($statement, $cql['values']);
        }

        return $session->execute($batch);
    }

    public function getPrefix()
    {
        return Config::_()->get('multi')['prefix'];
    }
}
