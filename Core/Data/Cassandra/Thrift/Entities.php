<?php
/**
 * Cassandra entities wrapper
 */
namespace Minds\Core\Data\Cassandra\Thrift;

class Entities
{
    protected $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
}
