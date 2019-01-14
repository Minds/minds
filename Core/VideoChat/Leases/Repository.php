<?php
namespace Minds\Core\VideoChat\Leases;

use Cassandra;
use Cassandra\Varint;
use Cassandra\Timestamp;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Minds\Common\Repository\Response;
use Minds\Core\Di\Di;

class Repository
{

    /** @var Client */
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * Add a lease to the datastore
     * @param VideoChatLease $lease
     * @return bool
     */
    public function add($lease)
    {
        $template = "INSERT INTO video_chast_lease (
            key,
            secret,
            holder_guid,
            last_refreshed
            ) 
            VALUES (?,?,?,?)";

        $values = [
            $lease->getKey(),
            $lease->getSecret(),
            new Varint($lease->getHolderGuid()),
            new Timestamp($lease->getLastRefreshed()),
        ];

        $query = new Prepared();
        $query->query($template, $values);

        try {
            $this->db->request($query);
        } catch (\Exception $e) {
            error_log("[VideoChat\Leases\Repository::add] {$e->getMessage()} > " . get_class($e));
            return false;
        }

        return true;
    }

    /**
     * Return a list of leases
     * @param array $options
     * @return VideoChatLeaes[]
     */
    public function getList($options)
    {
        $options = array_merge([
            'key' => null,
            'limit' => 1000,
            'token' => null
        ], $options);


        $cql = "SELECT * from video_chat_lease";
        $where = [];
        $values = [];

        if ($options['key']) {
            $where[] = 'key = ?';
            $values[] = $options['key'];
        }

        if ($where) {
            $cql .= " WHERE " . implode(" AND ", $where);
        }

        $query = new Prepared();
        $query->query($cql, $values);
        $query->setOpts([
            'page_size' => (int) $options['limit'],
            'paging_state_token' => base64_decode($options['token'])
        ]);

        $leases = new Response();

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        foreach($rows as $row) {
            $lease = new VideoChatLease();
            $lease
                ->setKey((string) $row['key'])
                ->setSecret((string) $row['secret'])
                ->setLastRefreshed($row['last_refreshed']->time())
                ->setHolderGuid((int) $row['holder_guid']->value());

            $leases[] = $lease;
        }

        $leases->setPagingToken($rows->pagingStateToken());

        return $leases;
    }

    public function get($key)
    {
        $row = $this->getList([ 'key' => $key ]);

        if (!$row) {
            return null;
        }

        return $row[0];
    }

    public function update($key, $guids)
    {
        // TODO: Implement update() method.
    }

    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }

}