<?php
/**
 * Spam Block Repository
 */
namespace Minds\Core\Security\SpamBlocks;

use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Cassandra\Varint;
use Cassandra\Timestamp;

class Repository
{

    /** @var Client $client */
    private $client;

    public function __construct($client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * Return a list of blocked key/values
     * @param array $opts 
     * @return Response[SpamBlock]
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'limit' => 100,
            'offset' => '',
            'token' => '',
            'key' => ''
        ], $opts);

        $query = new Prepared;
        $query->query("SELECT * FROM spam_blocks 
            WHERE key = ?",
            [
                (string) $opts['key'],
            ]);
        
        $query->setOpts([
            'page_size' => $opts['limit'],
            'paging_state_token' => $opts['token'],
        ]);

        $result = $this->client->request($prepared);

        if (!$result || !$result[0]) {
            return null;
        }

        $response = new Response();

        foreach ($result as $rows) {
            $model = new SpamBlock();
            $model->setKey($row['key'])
                ->setValue($row['value']);
            $response[] = $model;
        }

        $response->setPagingToken(base64_encode($result->pagingStateToken()));
        return $response;
    }

    /**
     * Return a single spam block key/value
     * @param string $key
     * @param string $value
     * @return SpamBlock
     */
    public function get($key, $value)
    {
        $query = new Prepared;
        $query->query("SELECT * FROM spam_blocks 
            WHERE key = ? AND value = ?",
            [
                (string) $key,
                (string) $value,
            ]);

        $result = $this->client->request($query);

        if (!$result || !$result[0]) {
            return null;
        }

        $model = new SpamBlock();
        $model->setKey($result[0]['key'])
            ->setValue($result[0]['value']);
        return $model;
    }

    /**
     * Add a spam block to store
     * @param SpamBlock $model
     * @return bool
     */
    public function add(SpamBlock $model)
    {
        $query = new Prepared;
        $query->query("INSERT INTO spam_blocks (key, value)
            VALUES (?, ?)",
            [
                (string) $model->getKey(),
                (string) $model->getValue(),
            ]);

        return $this->client->request($query);
    }

    /**
     * Update a spam block
     * @param SpamBlock $model
     * @param array $fields
     * @return bool
     */
    public function update(SpamBlock $model, $fields = [])
    {
        return $this->add($model);
    }

    /**
     * Remove a spam block
     * @param SpamBlock $model
     * @return bool
     */
    public function delete(SpamBlock $model)
    {
        $query = new Prepared;
        $query->query("DELETE FROM spam_blocks 
            WHERE key = ? AND value = ?",
            [
                (string) $model->getKey(),
                (string) $model->getValue(),
            ]);

        return $this->client->request($query);
    }

}