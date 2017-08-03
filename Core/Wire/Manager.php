<?php
/**
 * Created by Marcelo.
 * Date: 03/07/2017
 */

namespace Minds\Core\Wire;

use Minds\Api\Factory;
use Minds\Core\Data;
use Minds\Core\Di\Di;

class Manager
{
    protected $db;
    protected $timeline;

    public function __construct($db = null, $timeline = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');

        // @todo: migrate to CQLv3 (when PR is merged)
        $this->timeline = $timeline ?: new Data\Call('entities_by_time');
    }

    public function get(array $options = [])
    {
        $options = array_merge([
            'limit' => 5000,
            'offset' => '',
            'order' => 'ASC',
        ], $options);

        if ($options['limit'] > 5000) {
            throw new \Exception('Limit cannot be greater than 5000');
        }

        if (isset($options['type'])) {
            $index = "{$options['type']}";

            if (isset($options['status'])) {
                $index .= "{$options['status']}";
            }
        }

        if (isset($options['user_guid'])) {
            $index .= ":{$options['user_guid']}";
        }

        $timelineOptions = [
            'limit' => $options['limit'],
            'reversed' => false,
        ];

        if ($options['offset']) {
            $timelineOptions['offset'] = $options['offset'];
        }

        if ($options['order'] == 'DESC') {
            $timelineOptions['reversed'] = true;
        }

        $guids = $this->timeline->getRow($this->getIndexName($index), $timelineOptions);

        if ($guids && $options['offset'] && $options['limit'] > 1 && isset($guids[$options['offset']])) {
            unset($guids[$options['offset']]);
        }

        if (!$guids) {
            return [];
        }

        $rows = $this->fetch(array_keys($guids));
        return $rows;

        // @note: client-side re-ordering because cassandra sorts by TOKEN hash
        /*usort($rows, function ($a, $b) use ($options) {
            if (!$a['ts'] || !$b['ts'] || ($a['ts'] == $b['ts'])) {
                return 0;
            }

            if ($options['order'] == 'DESC') {
                return $a['ts'] > $b['ts'] ? -1 : 1;
            }

            return $a['ts'] > $b['ts'] ? 1 : -1;
        });

        return $rows;*/
    }

    public function fetch($guids)
    {
        if (!$guids) {
            return [];
        }

        if (!is_array($guids)) {
            $guids = [$guids];
        }

        $entities = \Minds\Core\Entities::get([
            'guids' => $guids
        ]);

        if (!$entities) {
            return [];
        }
        return $entities;
    }

    protected function getIndexName($index)
    {
        return 'wire:' . $index;
    }
}