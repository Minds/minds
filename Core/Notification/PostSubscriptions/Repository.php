<?php

/**
 * PostSubscription Repository
 *
 * @author emi
 */

namespace Minds\Core\Notification\PostSubscriptions;

use Cassandra\Rows;
use Cassandra\Varint;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Repository
{
    /** @var Client */
    protected $db;

    /**
     * Repository constructor.
     * @param null $db
     */
    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * Gets raw rows from the database as an Iterator
     * @param array $opts
     * @return Rows
     */
    public function getRows(array $opts = [])
    {
        $opts = array_merge([
            'entity_guid' => null,
            'user_guid' => null,
            'offset' => null,
            'limit' => null
        ], $opts);

        $cql = "SELECT * FROM post_subscriptions";
        $values = [];
        $where = [];

        if ($opts['entity_guid']) {
            $where[] = 'entity_guid = ?';
            $values[] = new Varint($opts['entity_guid']);
        }

        if ($opts['user_guid']) {
            $where[] = 'user_guid = ?';
            $values[] = new Varint($opts['user_guid']);
        }

        if ($where) {
            $cql .= ' WHERE ' . implode(' AND ', $where);
        }

        $preparedOpts = [];

        if ($opts['limit']) {
            $preparedOpts['page_size'] = (int) $opts['limit'];
        }

        if ($opts['offset']) {
            $preparedOpts['paging_state_token'] = base64_decode($opts['offset']);
        }

        $prepared = new Custom();
        $prepared->query($cql, $values);
        $prepared->setOpts($preparedOpts);

        return $this->db->request($prepared);
    }

    /**
     * Get Post Subscriptions from the database
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $rows = $this->getRows($opts);

        if (!$rows) {
            return new Response();
        }

        $postSubscriptions = new Response();

        foreach ($rows as $row) {
            $postSubscription = new PostSubscription();
            $postSubscription
                ->setEntityGuid($row['entity_guid']->value())
                ->setUserGuid($row['user_guid']->value())
                ->setFollowing($row['following']);

            $postSubscriptions[] = $postSubscription;
        }

        return $postSubscriptions;
    }

    /**
     * Gets a Post Subscription entity
     * @param int $entity_guid
     * @param int $user_guid
     * @return PostSubscription|null
     */
    public function get($entity_guid, $user_guid)
    {
        $postSubscriptions = $this->getList([
            'entity_guid' => $entity_guid,
            'user_guid' => $user_guid,
            'limit' => 1,
        ]);

        if (isset($postSubscriptions[0])) {
            return $postSubscriptions[0];
        }

        return null;
    }

    /**
     * Inserts a Post Subscription into the database. If lazy, it'll be
     * added only if the subscription doesn't exist.
     * @param PostSubscription $postSubscription
     * @param bool $ifNotExists
     * @return bool
     */
    public function add(PostSubscription $postSubscription, $ifNotExists = false)
    {
        $cql = "INSERT INTO post_subscriptions (entity_guid, user_guid, following) VALUES (?, ?, ?)";
        $values = [
            new Varint($postSubscription->getEntityGuid()),
            new Varint($postSubscription->getUserGuid()),
            $postSubscription->isFollowing()
        ];

        if ($ifNotExists) {
            $cql .= " IF NOT EXISTS";
        }

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $result = $this->db->request($prepared);
        } catch (\Exception $e) {
            error_log('[PostSubscriptions/Repository::update]' . get_class($e) . ': ' . $e->getMessage());
            return false;
        }

        return !!$result;
    }

    /**
     * Updates a Post Subscription into the database. Always lazy.
     * @param PostSubscription $postSubscription
     * @return bool
     */
    public function update(PostSubscription $postSubscription)
    {
        return $this->add($postSubscription, true);
    }

    /**
     * Deletes a Post Subscription from the database
     * @param PostSubscription $postSubscription
     * @return bool
     */
    public function delete(PostSubscription $postSubscription)
    {
        $cql = "DELETE FROM post_subscriptions WHERE entity_guid = ? AND user_guid = ?";
        $values = [
            new Varint($postSubscription->getEntityGuid()),
            new Varint($postSubscription->getUserGuid())
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $result = $this->db->request($prepared);
        } catch (\Exception $e) {
            error_log('[PostSubscriptions/Repository::update]' . get_class($e) . ': ' . $e->getMessage());
            return false;
        }

        return !!$result;
    }
}