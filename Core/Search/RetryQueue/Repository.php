<?php
/**
 * Repository
 * @author edgebal
 */

namespace Minds\Core\Search\RetryQueue;

use Cassandra\Rows;
use Cassandra\Timestamp;
use Exception;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client as CassandraClient;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Repository
{
    /** @var CassandraClient */
    protected $db;

    public function __construct(
        $db = null
    )
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'entity_urn' => null,
        ], $opts);

        $cql = "SELECT * FROM search_dispatcher_queue";
        $values = [];
        $cqlOpts = [];

        if ($opts['entity_urn']) {
            $cql .= ' WHERE entity_urn = ?';
            $values[] = $opts['entity_urn'];
        }

        if ($opts['limit'] ?? null) {
            $cqlOpts['page_size'] = (int) $opts['limit'];
        }

        if ($opts['offset'] ?? null) {
            $cqlOpts['paging_state_token'] = base64_decode($opts['offset']);
        }

        $prepared = new Custom();
        $prepared->query($cql, $values);
        $prepared->setOpts($cqlOpts);

        $response = new Response();

        try {
            /** @var Rows $rows */
            $rows = $this->db->request($prepared);

            if ($rows) {
                foreach ($rows as $row) {
                    $retryQueueEntry = new RetryQueueEntry();
                    $retryQueueEntry
                        ->setEntityUrn($row['entity_urn'])
                        ->setLastRetry($row['last_retry']->time())
                        ->setRetries($row['retries']);

                    $response[] = $retryQueueEntry;
                }

                $response->setPagingToken(base64_encode($rows->pagingStateToken()));
                $response->setLastPage($rows->isLastPage());
            }
        } catch (Exception $e) {
            error_log($e);
            $response->setException($e);
        }

        return $response;
    }

    /**
     * @param $urn
     * @return RetryQueueEntry
     */
    public function get($urn)
    {
        $retryQueueEntries = $this->getList([
            'entity_urn' => $urn,
        ])->toArray();

        if (count($retryQueueEntries)) {
            return $retryQueueEntries[0];
        } else {
            $retryQueueEntry = new RetryQueueEntry();
            $retryQueueEntry
                ->setEntityUrn($urn)
                ->setLastRetry(time())
                ->setRetries(0);

            return $retryQueueEntry;
        }
    }

    /**
     * @param RetryQueueEntry $retryQueueEntry
     * @return bool
     * @throws Exception
     */
    public function add(RetryQueueEntry $retryQueueEntry)
    {
        if (!$retryQueueEntry->getEntityUrn()) {
            throw new Exception('Missing URN');
        }

        $cql = "INSERT INTO search_dispatcher_queue (entity_urn, last_retry, retries) VALUES (?, ?, ?)";
        $values = [
            (string) $retryQueueEntry->getEntityUrn(),
            new Timestamp($retryQueueEntry->getLastRetry()),
            (int) $retryQueueEntry->getRetries(),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            return (bool) $this->db->request($prepared, true);
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * @param RetryQueueEntry $retryQueueEntry
     * @return bool
     * @throws Exception
     */
    public function update(RetryQueueEntry $retryQueueEntry)
    {
        return $this->add($retryQueueEntry);
    }

    /**
     * @param RetryQueueEntry $retryQueueEntry
     * @return bool
     * @throws Exception
     */
    public function delete(RetryQueueEntry $retryQueueEntry)
    {
        if (!$retryQueueEntry->getEntityUrn()) {
            throw new Exception('Missing URN');
        }

        $cql = "DELETE FROM search_dispatcher_queue WHERE entity_urn = ?";
        $values = [
            (string) $retryQueueEntry->getEntityUrn(),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            return (bool) $this->db->request($prepared, true);
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
}
