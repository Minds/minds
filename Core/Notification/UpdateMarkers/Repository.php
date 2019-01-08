<?php
namespace Minds\Core\Notification\UpdateMarkers;

use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Cassandra\Timestamp;
use Cassandra\Type;
use Cassandra\Varint;
use Minds\Common\Repository\Response;
use Minds\Helpers\Cql;

class Repository
{

    /** @var Client $cql */
    private $cql;

    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * Return a list
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'user_guid' => null,
        ], $opts);

        if (!$opts['user_guid']) {
            throw new \Exception('user_guid be be sent');
        }

        $statement = "SELECT * FROM update_markers";

        $where = [
            'user_guid = ?',
            'entity_type  = ?',
        ];

        $values = [
            new Varint($opts['user_guid']),
            $opts['entity_type'],
        ];

        if ($opts['entity_guid']) {
            $where[] = "entity_guid = ?";
            $values[] = new Varint($opts['entity_guid']);
        }

        if ($opts['marker']) {
            $where[] = 'marker = ?';
            $values[] = $opts['marker'];
        }

        $statement .= " WHERE " . implode(' AND ', $where);

        $prepared = new Prepared();
        $prepared->query($statement, $values);
        
        $response = new Response();

        try {
            foreach ($this->cql->request($prepared) as $row) {
                $row = Cql::toPrimitiveType($row);

                $marker = new UpdateMarker();
                $marker
                    ->setUserGuid($row['user_guid'])
                    ->setEntityType($row['entity_type'])
                    ->setEntityGuid($row['entity_guid'])
                    ->setMarker($row['marker'])
                    ->setUpdatedTimestamp($row['updated_timestamp'])
                    ->setReadTimestamp($row['read_timestamp']);
                $response[] = $marker;
            }
        } catch (\Exception $e) {
        }

        return $response;
    }

    public function get()
    {

    }

    /**
     * Add a marker
     * @param UpdateMarker $marker
     * @return bool
     */
    public function add(UpdateMarker $marker)
    {
        $statement = "INSERT INTO update_markers";

        $columns = [ 
            'user_guid' => new Varint($marker->getUserGuid()),
            'entity_type' => $marker->getEntityType(), 
            'entity_guid' => new Varint($marker->getEntityGuid()),
            'marker' => $marker->getMarker(),
        ];

        if ($marker->getUpdatedTimestamp()) {
            $columns['updated_timestamp'] = new Timestamp($marker->getUpdatedTimestamp());
        }

        if ($marker->getReadTimestamp()) {
            $columns['read_timestamp'] = new Timestamp($marker->getReadTimestamp());
        }

        $statement .= " (" . implode(',', array_keys($columns)) . ")";
        $statement .= " VALUES (" . implode(',', array_fill(0, count($columns), '?')) . ")";

        $values = array_values($columns);

        $prepared = new Prepared();
        $prepared->query($statement, $values);

        return $this->cql->request($prepared);
    }

    /**
     * Update a marker (same as add)
     * @param UpdateMarker $marker
     * @param array $fields
     * @return bool
     */
    public function update(UpdateMarker $marker, $fields = [])
    {
        return $this->add($marker);
    }

    /**
     * Remove a marker
     * @param UpdateMarker $marker
     * @return bool
     */
    public function delete(UpdateMarker $marker)
    {

    }
}
