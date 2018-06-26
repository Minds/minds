<?php

/**
 * Minds Legacy Feed Repository
 *
 * @author emi
 */

namespace Minds\Core\Feeds\Legacy;

use Cassandra\Rows;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Feeds\FeedItem;

class Repository
{
    /** @var Client */
    protected $cql;

    /**
     * Repository constructor.
     * @param null $cql
     */
    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $opts
     * @return Response
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => null,
            'offset' => '',
            'type' => null,
            'subtype' => null,
            'feed' => null,
            'container_guid' => null,
            'all' => false,
        ], $opts);

        $keyParts = [];

        if ($opts['type']) {
            $keyParts[] = $opts['type'];

            if ($opts['subtype']) {
                $keyParts[] = $opts['subtype'];
            }

            if ($opts['network']) {
                $keyParts[] = 'network';
                $keyParts[] = $opts['network'];
            }

            if ($opts['container']) {
                $keyParts[] = 'container';
                $keyParts[] = $opts['container'];
            }

            if ($opts['feed']) {
                $keyParts[] = $opts['feed'];
            }

            if ($opts['container_guid']) {
                $keyParts[] = $opts['container_guid'];
            }
        }

        if (!$keyParts && !$opts['all']) {
            throw new \Exception('Invalid feed filter');
        }

        $key = implode(':', $keyParts);

        $cql = "SELECT * FROM entities_by_time";
        $values = [];

        if ($key) {
            $cql .= " WHERE key = ?";
            $values[] = $key;
        }

        if ($opts['reversed']) {
            $cql .= " ORDER BY column1 DESC";
        }
        
        $prepared = new Custom();
        $prepared->query($cql, $values);
        $cqlOpts = [];

        if ($opts['limit']) {
            $cqlOpts['page_size'] = (int) $opts['limit'];
        }

        if ($opts['offset']) {
            $cqlOpts['paging_state_token'] = base64_decode($opts['offset']);
        }

        $prepared->setOpts($cqlOpts);

        try {
            /** @var Rows $result */
            $result = $this->cql->request($prepared);
        } catch (\Exception $e) {
            error_log($e);
            // TODO: Attempt a couple of times?
            return (new Response())->setException($e);
        }

        $response = new Response();
        $response->setPagingToken($result->pagingStateToken());
        $response->setLastPage($result->isLastPage());

        foreach ($result as $row) {
            $feedItem = new FeedItem();
            $feedItem
                ->setType($opts['type'])
                ->setSubtype($opts['subtype'] ?: '')
                ->setContainerGuid($opts['container_guid'] ?: 0)
                ->setFeed($opts['feed'] ?: 'all')
                ->setGuid($row['column1']);

            $response[] = $feedItem;
        }

        return $response;
    }

    /**
     * @param FeedItem $feedItem
     * @return bool
     * @throws \Exception
     */
    public function add(FeedItem $feedItem)
    {
        $guid = (string) $feedItem->getGuid();
        $failed = false;

        foreach ($this->buildKeys($feedItem) as $key) {
            $cql = "INSERT INTO entities_by_time (key, column1, value) VALUES (?, ?, ?)";
            $values = [
                $key,
                $guid,
                $guid,
            ];

            $prepared = new Custom();
            $prepared->query($cql, $values);

            try {
                $this->cql->request($prepared, true);
            } catch (\Exception $e) {
                error_log(static::class . '::' . __METHOD__ . ' ' . $e);
                $failed = true;
            }
        }

        return !$failed;
    }

    /**
     * @param FeedItem $feedItem
     * @return bool
     * @throws \Exception
     */
    public function update(FeedItem $feedItem)
    {
        return $this->add($feedItem);
    }

    /**
     * @param FeedItem $feedItem
     * @return bool
     * @throws \Exception
     */
    public function delete(FeedItem $feedItem)
    {
        $guid = (string) $feedItem->getGuid();
        $failed = false;

        foreach ($this->buildKeys($feedItem) as $key) {
            $cql = "DELETE FROM entities_by_time WHERE key = ? AND column1 = ?";
            $values = [
                $key,
                $guid,
            ];

            $prepared = new Custom();
            $prepared->query($cql, $values);

            try {
                $this->cql->request($prepared, true);
            } catch (\Exception $e) {
                error_log(static::class . '::' . __METHOD__ . ' ' . $e);
                $failed = true;
            }
        }


        return $failed;
    }

    /**
     * Generates entities_by_time key for a Feed Item
     * @param FeedItem $feedItem
     * @return string[]
     * @throws \Exception
     */
    public function buildKeys(FeedItem $feedItem)
    {
        $primaryKey = "{$feedItem->getType()}";

        $keys = [
            $primaryKey
        ];

        if ($feedItem->getSubtype()) {
            $primaryKey = "{$feedItem->getType()}:{$feedItem->getSubtype()}";
            $keys[] = $primaryKey;
        }

        $keys[] = "{$primaryKey}:{$feedItem->getFeed()}:{$feedItem->getContainerGuid()}";

        return $keys;
    }
}
