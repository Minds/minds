<?php

namespace Minds\Core\Analytics\Graphs;

use Cassandra\Timestamp;
use Minds\Core\Data\Cassandra;
use Minds\Core\Di\Di;
use Minds\Common\Repository\Response;
use Minds\Common\Urn;

class Repository
{
    /** @var Cassandra\Client */
    protected $client;

    /** @var Urn */
    protected $urn;

    public function __construct($client = null, $urn = null)
    {
        $this->client = $client ?: Di::_()->get('Database\Cassandra\Cql');
        $this->urn = $urn ?: new Urn();
    }

    /**
     * Return a list of reports
     * @param $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'key' => null,
            'limit' => 12
        ], $opts);

        $cql = "SELECT * FROM analytics_graphs ";
        $cqlOpts = [];

        $where = [];
        $values = [];

        if ($opts['key']) {
            $where[] = 'key = ?';
            $values[] = $opts['key'];
        }

        if (isset($opts['limit'])) {
            $cqlOpts['page_size'] = (int) $opts['limit'];
        }

        if (count($where)) {
            $cql .= 'WHERE ' . implode(' AND ', $where);
        }

        $query = new Cassandra\Prepared\Custom();
        $query->query($cql, $values);
        $query->setOpts($cqlOpts);

        $response = new Response;

        try {
            $rows = $this->client->request($query);

            foreach ($rows as $row) {
                $graph = new Graph();

                $graph->setKey($row['key'])
                    ->setLastSynced((int) $row['last_synced'])
                    ->setData(json_decode($row['data'], true));

                $response[] = $graph;
            }
        } catch (\Exception $e) {
            error_log("[Analytics\Graphs\Repository::getList] {$e->getMessage()} > " . get_class($e));
            return null;
        }

        return $response;
    }

    /**
     * Return a single report
     * @param string $urn
     * @return 
     */
    public function get($urn)
    {
        $key = $this->urn->setUrn($urn)->getNss();
        $response = $this->getList([
            'key' => $key,
            'limit' => 1,
        ]);
        return $response[0];
    }

    /**
     * @param Graph $metric
     * @param bool $ttl
     * @return bool
     */
    public function add(Graph $graph)
    {
        $cql = "INSERT INTO analytics_graphs (key, last_synced, data) VALUES (?, ?, ?)";
        $values = [
            $graph->getKey(),
            new Timestamp($graph->getLastSynced()),
            json_encode($graph->getData())
        ];

        $query = new Cassandra\Prepared\Custom();
        $query->query($cql, $values);

        try {
            $this->client->request($query);
        } catch (\Exception $e) {
            error_log("[Analytics\Graphs\Repository::add] {$e->getMessage()} > " . get_class($e));
            return false;
        }
        return true;
    }

    /**
     * Not implemented
     */
    public function update($graph, $fields = [])
    {

    }

    /**
     * Not implemented
     */
    public function delete($graph, $fields = [])
    {
        
    }

}
