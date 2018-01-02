<?php
namespace Minds\Core\Trending;

use Cassandra;
use Cassandra\Varint;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Repository
{
    /** @var Client */
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : Di::_()->get('Database\Cassandra\Cql');
    }

    public function add($key, $guids)
    {
        $requests = [];
        $template = "INSERT INTO trending (type, place, guid) VALUES (?,?,?)";
        foreach ($guids as $i => $guid) {
            $requests[] = ['string' => $template, 'values' => [$key, ($i), new Varint($guid)]];
        }

        $this->db->batchRequest($requests, Cassandra::BATCH_UNLOGGED);

        return $this;
    }

    public function getList($options)
    {
        $options = array_merge([
            'type' => '',
            'limit' => 12,
            'offset' => null
        ], $options);


        $query = new Custom();
        $query->query("SELECT * from trending WHERE type = ?", [$options['type']]);
        $query->setOpts([
            'page_size' => (int) $options['limit'],
            'paging_state_token' => base64_decode($options['offset'])
        ]);

        $result = null;

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        foreach($rows as $row) {
            $result[] = (string) $row['guid'];
        }

        if (!$result) {
            return [];
        }

        return [
            'guids' => $result,
            'token' => $rows->pagingStateToken()
        ];
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
