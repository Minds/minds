<?php


/*
* SendWyre Integration repositories
*
*/

namespace Minds\Core\SendWyre;

use Cassandra\Varint;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Repository
{
    /**
     * @var Client
     */
    protected $db;

    /**
     * Repository constructor.
     *
     * @param null $db
     */
    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param varint $userGuid
     *
     * @return SendWyreAccount
     */
    public function get($userGuid)
    {
        $template = 'SELECT * FROM sendwyre_accounts WHERE user_guid = ?';
        $values = [new VarInt($userGuid)];

        $query = new Custom();
        $query->query($template, $values);

        try {
            $result = $this->db->request($query);
        } catch (\Exception $e) {
            error_log($e);
        }

        if ($result && $result->count() > 0) {
            $row = $result->current();

            $account = (new SendWyreAccount())
                    ->setUserGuid($row['user_guid'])
                    ->setSendWyreAccountId($row['sendwyre_account_id']);

            return $account;
        }
    }

    /**
     * @param SendWyreAccount
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function save($sendWyreAccount)
    {
        if (!$sendWyreAccount->getUserGuid()) {
            throw new \Exception('user_guid is required');
        }
        if (!$sendWyreAccount->getSendWyreAccountId()) {
            throw new \Exception('sendwyre_account_id is required');
        }

        $template = 'INSERT INTO sendwyre_accounts (user_guid, sendwyre_account_id) VALUES (?, ?)';

        $values = [
            new Varint($sendWyreAccount->getUserGuid()),
            (string) $sendWyreAccount->getSendWyreAccountId(),
        ];

        $query = new Custom();
        $query->query($template, $values);

        return $this->db->request($query);
    }

    /**
     * @param SendWyreAccount $sendWyreAccount
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete($sendWyreAccount)
    {
        if (!$sendWyreAccount->getUserGuid()) {
            throw new \Exception('user_guid is required');
        }

        $template = 'DELETE FROM sendwyre_accounts WHERE user_guid = ?';

        $values = [
            new Varint($sendWyreAccount->getUserGuid()),
        ];

        $query = new Custom();
        $query->query($template, $values);

        return $this->db->request($query);
    }
}
