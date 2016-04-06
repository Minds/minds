<?php
/**
 * Reporting operations
 */
namespace Minds\Core;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Helpers;

class Reports
{
    protected $mongo;

    public function __construct(Data\Interfaces\ClientInterface $mongo = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');
    }

    public function insert($object, $reporter, $subject)
    {
        $object_guid = $this->extractGuid($object);
        $reporter_guid = $this->extractGuid($reporter);

        if (!$object_guid || !$reporter_guid || !$subject) {
            return false;
        }

        $saved = $this->mongo->insert('report', [
            'object' => $object_guid,
            'reporter' => $reporter_guid,
            'reporter_name' => is_object($reporter) ? $reporter->username : $user_guid,
            'subject' => $subject,
            'date' => time(),
            'state' => 'review'
        ]);

        return (bool) $saved;
    }

    public function getQueue($limit = 12, $offset = '', $state = null)
    {
        $query = [];

        if ($state) {
            $query['state'] = $state;
        }

        if ($offset) {
            $query['_id'] = [ '$gt' => $offset ];
        }

        $data = $this->mongo->find('report', $query);
        $data->limit($limit);
        $data->sort([ '_id' => 1 ]);

        if (!$data) {
            return [];
        }

        $queue = [];

        foreach ($data as $row) {
            $object = Entities\Factory::build($row['object'])->export();
            $object_type = '';
            $object_subtype = '';

            if ($object) {
                $object_type = $object['type'];
                $object_subtype = isset($object['subtype']) ? $object['subtype'] : '';
            }

            $queue[] = [
                '_id' => $row['_id']->{'$id'},
                'object' => $object,
                'object_type' => $object_type,
                'object_subtype' => $object_subtype,
                'reporter' => $row['reporter'],
                'reporter_name' => $row['reporter_name'],
                'state' => $row['state'],
                'subject' => $row['subject'],
                'date' => $row['date'],
            ];
        }

        return $queue;
    }

    protected function extractGuid($object = null)
    {
        if (!$object) {
            return '';
        }

        if (is_object($object) && property_exists($object, 'getGuid')) {
            return $object->getGuid();
        } elseif (is_object($object) && $object->guid) {
            return $object->guid;
        } elseif (is_array($object) && isset($object['guid'])) {
            return $object['guid'];
        } elseif (is_numeric($object) || is_string($object)) {
            return $object;
        }

        return '';
    }
}
