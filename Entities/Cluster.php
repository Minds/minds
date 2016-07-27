<?php
namespace Minds\Entities;

use Minds\Core\data;

/**
 * Cluster Entity
 */
class Cluster extends Entity
{
    public $ttl = 1800; //keep nodes valid for half an hour

    public function __construct($guid = null)
    {
        $this->cluster = 'master';
    }

    /**
     * Return an array of node indexes
     * @param  integer $limit
     * @return array
     */
    public function getNodes($limit=10000)
    {
        $db = new Data\Call('user_index_to_guid');
        $row = $db->getRow('clusters:'.$this->cluster);
        $row[elgg_get_site_url()] = time(); //must always return ourself
        return $row;
    }

    /**
     * Stores a node in the index
     * @param string $server_addr
     */
    public function join($server_addr)
    {
        $db = new Data\Call('user_index_to_guid');
        $row = $db->insert('clusters:'.$this->cluster, array($server_addr=>time()), $this->ttl);
        return $this->getNodes();
    }

    /**
     * TBD. Not implemented.
     * @return mixed
     */
    public function leave()
    {
    }
}
