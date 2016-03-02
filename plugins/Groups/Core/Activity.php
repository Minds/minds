<?php
/**
 * Groups activity
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Entities;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class Activity
{

    protected $db;
    protected $group;

    /**
     * Constructor.
     */
    public function __construct(GroupEntity $group, $db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->group = $group;
    }

    /**
     * Counts the group's activity
     * @return int
     */
     public function count()
     {
         return $this->db->count("activity:container:{$this->group->getGuid()}");
     }
}
