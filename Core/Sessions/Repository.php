<?php
/**
 * Minds Session Repository
 */
namespace Minds\Core\Sessions;

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
     * Get the session from the database
     */
    public function get($user_guid, $id)
    {
        $prepared = new Prepared;
        $prepared->query("SELECT * FROM jwt_sessions 
            WHERE user_guid = ?
            AND id = ?", [
            new Varint($user_guid),
            (string) $id,
        ]);

        $result = $this->client->request($prepared);

        if (!$result || !$result[0]) {
            return null;
        }

        $session = new Session();
        $session->setId((string) $result[0]['id'])
            ->setUserGuid((int) $result[0]['user_guid']->value())
            ->setExpires((int) $result[0]['expires']->time());

        return $session;
    }

    public function getAll($opts = [])
    {
        // TODO:
    }

    public function add($session)
    {
        $prepared = new Prepared;
        $prepared->query("INSERT INTO jwt_sessions 
            (user_guid, id, expires)
            VALUES
            (?,?,?)", [
                new Varint($session->getUserGuid()),
                $session->getId(),
                new Timestamp($session->getExpires()),
            ]);
        $this->client->request($prepared);
    }

    public function update($session, $fields = [])
    {
        // TODO
    }

    /**
     * Destory session
     * @param Session $session
     * @param bool $all - destory all for user
     */
    public function delete($session, $all = false)
    {
        $prepared = new Prepared;

        if ($all) {
            $prepared->query("DELETE FROM jwt_sessions WHERE user_guid = ?", [
                new Varint($session->getUserGuid()),
            ]);
        } else {
            $prepared->query("DELETE FROM jwt_sessions WHERE user_guid = ? AND id = ?", [
                new Varint($session->getUserGuid()),
                $session->getId(),
            ]);
        }

        $this->client->request($prepared);
    }

    /**
     * Return counts
     * @param int $user_guid
     * @return int
     * TODO: This should be in its own class
     */
    public function getCount($user_guid = null)
    {
        $prepared = new Prepared;
        $prepared->query("SELECT COUNT(*) as count FROM jwt_sessions WHERE user_guid = ?", [
            new Varint($user_guid),
        ]);

        $response = $this->client->request($prepared);
        if (!$response) {
            return 0;
        }

        return (int) $response[0]['count'];
    }

}
