<?php
/**
 * Groups featured by Minds administrators
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Entities;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class Featured
{

    protected $db;

    /**
     * Constructor.
     */
    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->db->setNamespace('group');
    }

    /**
     * Fetch the featured groups
     * @param  array $opts
     * @return array
     */
    public function getFeatured(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'hydrate' => true
        ], $opts);

        $guids = $this->db->get('featured', $opts);

        if (!$guids) {
            return [];
        }

        if (!$opts['hydrate']) {
            return $guids;
        }

        return Entities::get([
          'guids' => $guids
        ]);
    }

    /**
     * Adds a group to featured index
     * @param  GroupEntity $group
     * @return boolean
     */
    public function feature(GroupEntity $group)
    {
        return $this->db->set('featured', [ $group->getFeaturedId() => $group->getGuid() ]);
    }

    /**
     * Removes a group from featured index
     * @param  GroupEntity $group [description]
     * @return [type]             [description]
     */
    public function unfeature(GroupEntity $group)
    {
        if (!$group->getFeaturedId()) {
            return false;
        }

        return $this->db->remove('featured', [ $group->getFeaturedId() ]);
    }
}
