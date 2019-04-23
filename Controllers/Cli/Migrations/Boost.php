<?php

namespace Minds\Controllers\Cli\Migrations;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Entities;
use Stripe;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Analytics\Iterators\SignupsOffsetIterator;

class Boost extends Cli\Controller implements Interfaces\CliControllerInterface
{

    private $db;
    private $es;

    private $pendingBulkInserts = [];

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->db = Di::_()->get('Database\Cassandra\Cql');
        $this->es = Di::_()->get('Database\ElasticSearch');
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        echo "1";
    }

    public function run()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $type = $this->getOpt('type') ?? 'newsfeed';

        $cql = \Minds\Core\Di\Di::_()->get('Database\Cassandra\Cql');
        $prepared = new \Minds\Core\Data\Cassandra\Prepared\Custom;
        if ($this->getOpt('guid')) {
            $statement = "SELECT * FROM boosts WHERE type=? AND guid < ? ORDER BY guid DESC";
            $prepared->query($statement, [ $type, new \Cassandra\Varint($this->getOpt('guid')) ]);
        } else {
            $statement = "SELECT * FROM boosts WHERE type=? ORDER BY guid DESC";
            $prepared->query($statement, [ $type ]);
        }
        $prepared->setOpts([ 'page_size' => 200 ]);

        $i = 0;
        try {
            $rows = $cql->request($prepared);
            if (!$rows) {
                return;
            }
            while (true) {
                foreach ($rows as $row) {
                    $i++;
                    $boost = (new Entities\Boost\Factory())->build($row['type']);
                    $boost->loadFromArray($row['data']);
                    $body = $this->index($boost);
                    $date = date('d-m-Y', $boost->getTimeCreated());
                    echo "\n$i {$boost->guid} {$boost->getBidType()} {$boost->getHandler()} {$body['entity_guid']} {$boost->getState()} {$body['@reviewed']} $date";
                }
                if ($rows->isLastPage()) {
                    break;
                }
                $rows = $rows->nextPage();
            }
        } catch (\Exception $e) {
            var_dump($e);
        }
        $this->bulkIndex();
    }

    protected function index($boost)
    {
        $export = $boost->export();
        $body = [
            '@timestamp' => $boost->getTimeCreated() * 1000,
            'bid' => $boost->getBidType() === 'tokens' ?
                $boost->getBid() / (10**18) : $boost->getBid(),
            'bid_type' => $boost->getBidType() === 'tokens' ?
                strpos($boost->getTransactionId(), '0x', 0) === 0 ? 'onchain' : 'offchain'
                : $boost->getBidType(),
            'entity_guid' => $boost->getEntity()->getGuid(),
            'impressions' => $boost->getImpressions(),
            'impressions_met' => $export['met_impressions'],
            'owner_guid' => $boost->getOwner()->getGuid(),
            'rating' => $boost->getRating(),
            'type' => $boost->getHandler(),
            'priority' => (bool) $boost->getPriorityRate(),
        ];
        
        if ($boost->getState() === 'approved') {
            $body['@reviewed'] = $export['last_updated'] * 1000;
        } elseif ($boost->getState() === 'revoked') {
            $body['@revoked'] = $export['last_updated'] * 1000;
        } elseif ($boost->getState() === 'rejected') {
            $body['@reviewed'] = $export['last_updated'] * 1000;
            $body['@rejected'] = $export['last_updated'] * 1000; 
        } elseif ($boost->getState() === 'completed') {
            $body['@reviewed'] = $boost->getTimeCreated() * 1000;
            $body['@completed'] = $export['last_updated'] * 1000;
        } elseif ($boost->getState() === 'failed' || $boost->getState() === 'pending') {
            $body['@rejected'] = $export['last_updated'] * 1000;
        }

        $this->pendingBulkInserts[] = [
            'update' => [
                '_id' => (string) $boost->getGuid(),
                '_index' => 'minds-boost',
                '_type' => '_doc',
            ],
        ];

        $this->pendingBulkInserts[] = [
            'doc' => $body,
            'doc_as_upsert' => true,
        ];

        if (count($this->pendingBulkInserts) > 2000) { //1000 inserts
            $this->bulkIndex();
        }

        return $body;
    }

    protected function bulkIndex()
    {
        if (count($this->pendingBulkInserts) > 0) {
            $res = $this->es->bulk(['body' => $this->pendingBulkInserts]);
            $this->pendingBulkInserts = [];
        }
    }
}
