<?php

/**
 * Minds Comments Repository
 *
 * @author emi
 */

namespace Minds\Core\Comments;

use Cassandra\Map;
use Cassandra\Rows;
use Cassandra\Timestamp;
use Cassandra\Type;
use Cassandra\Varint;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Helpers\Cql;

class Repository
{
    /** @var Client */
    protected $cql;

    /** @var Legacy\Repository */
    protected $legacyRepository;

    /** @var array */
    static $allowedEntityAttributes = [
        'entityGuid',
        'parentGuid',
        'guid',
        'hasChildren',
        'ownerGuid',
        'containerGuid',
        'timeCreated',
        'timeUpdated',
        'accessId',
        'body',
        'attachments',
        'mature',
        'edited',
        'spam',
        'deleted',
        'ownerObj',
    ];

    /**
     * Repository constructor.
     * @param Client $cql
     * @param Legacy\Repository $legacyRepository
     */
    public function __construct($cql = null, $legacyRepository = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->legacyRepository = $legacyRepository ?: new Legacy\Repository();
    }

    /**
     * Returns a list of Comment entities
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'entity_guid' => null,
            'parent_guid' => null,
            'guid' => null,
            'limit' => null,
            'offset' => null,
            'descending' => true
        ], $opts);

        if ($this->legacyRepository->isLegacy($opts['entity_guid'])) {
            return $this->legacyRepository->getList($opts);
        }

        $cql = "SELECT * from comments";
        $values = [];
        $cqlOpts = [];

        $where = [];

        if ($opts['entity_guid']) {
            $where[] = 'entity_guid = ?';
            $values[] = new Varint($opts['entity_guid']);
        }

        if ($opts['parent_guid'] !== null) {
            $where[] = 'parent_guid = ?';
            $values[] = new Varint($opts['parent_guid']);
        }

        if ($opts['guid']) {
            $where[] = 'guid = ?';
            $values[] = new Varint($opts['guid']);
        }

        if ($where) {
            $cql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (!$opts['descending']) {
            $cql .= 'ORDER BY parent_guid DESC, guid ASC';
        }

        if ($opts['offset']) {
            $cqlOpts['paging_state_token'] = base64_decode($opts['offset']);
        }

        if ($opts['limit']) {
            $cqlOpts['page_size'] = (int) $opts['limit'];
        }

        $query = new Custom();
        $query->query($cql, $values);
        $query->setOpts($cqlOpts);

        $comments = new Response();

        try {
            /** @var Rows $rows */
            $rows = $this->cql->request($query);

            foreach ($rows as $row) {
                $row = Cql::toPrimitiveType($row);

                $flags = $row['flags'] ?: [];

                $comment = new Comment();
                $comment
                    ->setEntityGuid($row['entity_guid'])
                    ->setParentGuid($row['parent_guid'])
                    ->setGuid($row['guid'])
                    ->setHasChildren($row['has_children'])
                    ->setOwnerGuid($row['owner_guid'])
                    ->setContainerGuid($row['container_guid'])
                    ->setTimeCreated($row['time_created'])
                    ->setTimeUpdated($row['time_updated'])
                    ->setAccessId($row['access_id'])
                    ->setBody($row['body'])
                    ->setAttachments($row['attachments'] ?: [])
                    ->setMature(isset($flags['mature']) && $flags['mature'])
                    ->setEdited(isset($flags['edited']) && $flags['edited'])
                    ->setSpam(isset($flags['spam']) && $flags['spam'])
                    ->setDeleted(isset($flags['deleted']) && $flags['deleted'])
                    ->setOwnerObj($row['owner_obj'])
                    ->setVotesUp($row['votes_up'] ?: [])
                    ->setVotesDown($row['votes_down'] ?: [])
                    ->setEphemeral(false)
                    ->markAllAsPristine();

                $comments[] = $comment;
            }

            $comments->setPagingToken(base64_encode($rows->pagingStateToken()));
        } catch (\Exception $e) { }

        return $comments;
    }

    /**
     * Gets a single comment based on its primary keys
     * @return Comment|null
     */
    public function get($entity_guid, $parent_guid, $guid)
    {
        if (!$entity_guid || !$guid) {
            return null;
        }

        if ($this->legacyRepository->isLegacy($entity_guid)) {
            $comments = $this->legacyRepository->getList([
                'limit' => 1,
                'offset' => base64_encode($guid),
                'entity_guid' => $entity_guid
            ]);
        } else {
            $comments = $this->getList([
                'entity_guid' => $entity_guid,
                'parent_guid' => $parent_guid,
                'guid' => $guid,
                'limit' => 1,
            ]);
        }

        if (isset($comments[0])) {
            return $comments[0];
        }

        return null;
    }

    /**
     * Counts the comments on an entity
     * @param int $entity_guid
     * @param int $parent_guid
     * @return int
     */
    public function count($entity_guid, $parent_guid = null)
    {
        if (!$entity_guid) {
            return 0;
        }

        if ($this->legacyRepository->isLegacy($entity_guid)) {
            if ($parent_guid > 0) {
                return 0;
            }

            return $this->legacyRepository->count($entity_guid);
        }

        $cql = "SELECT COUNT(*) as count FROM comments WHERE entity_guid = ?";
        $values = [
            new Varint($entity_guid)
        ];

        if ($parent_guid !== null) {
            $cql .= " AND parent_guid = ?";
            $values[] = new Varint($parent_guid);
        }

        $prepared = new Custom();
        $prepared->query($cql, $values);

        $result = $this->cql->request($prepared);

        if (!isset($result)) {
            return 0;
        }

        return (int) $result[0]['count'];
    }

    /**
     * Adds/updates a Comment entity
     * @param Comment $comment
     * @param array $attributes
     * @return bool
     */
    public function add(Comment $comment, array $attributes = null)
    {
        if ($attributes === null) {
            // All
            $attributes = static::$allowedEntityAttributes;
        } else {
            // Only dirty
            $attributes = array_values(array_intersect($attributes, static::$allowedEntityAttributes));
        }

        $fields = [];

        if (in_array('hasChildren', $attributes)) {
            $fields['has_children'] = $comment->getHasChildren();
        }

        if (in_array('ownerGuid', $attributes)) {
            $fields['owner_guid'] = new Varint($comment->getOwnerGuid() ?: 0);
        }

        if (in_array('containerGuid', $attributes)) {
            $fields['container_guid'] = new Varint($comment->getContainerGuid() ?: 0);
        }

        if (in_array('timeCreated', $attributes)) {
            $fields['time_created'] = new Timestamp($comment->getTimeCreated());
        }

        if (in_array('timeUpdated', $attributes)) {
            $fields['time_updated'] = new Timestamp($comment->getTimeUpdated());
        }

        if (in_array('accessId', $attributes)) {
            $fields['access_id'] = new Varint($comment->getAccessId());
        }

        if (in_array('body', $attributes)) {
            $fields['body'] = (string) $comment->getBody();
        }

        if (in_array('attachments', $attributes)) {
            // TODO: Check a way to make atomic updates
            $fields['attachments'] = new Map(Type\Map::text(), Type\Map::text());

            $attachments = $comment->getAttachments() ?: [];
            foreach ($attachments as $key => $value) {
                $fields['attachments']->set((string) $key, (string) $value);
            }
        }

        if (
            in_array('mature', $attributes) ||
            in_array('edited', $attributes) ||
            in_array('spam', $attributes) ||
            in_array('deleted', $attributes)
        ) {
            // TODO: Check a way to make atomic updates
            $fields['flags'] = new Map(Type\Map::text(), Type\Map::boolean());

            $fields['flags']->set('mature', $comment->isMature());
            $fields['flags']->set('edited', $comment->isEdited());
            $fields['flags']->set('spam', $comment->isSpam());
            $fields['flags']->set('deleted', $comment->isDeleted());
        }

        if (in_array('ownerObj', $attributes)) {
            $fields['owner_obj'] = $comment->getOwnerObj() ? json_encode($comment->getOwnerObj()) : null;
        }

        if (!$fields) {
            // No changes
            return true;
        }

        $fields = array_merge($fields, [
            'entity_guid' => new Varint($comment->getEntityGuid()),
            'parent_guid' => new Varint($comment->getParentGuid()),
            'guid' => new Varint($comment->getGuid()),
        ]);

        $cql = "INSERT INTO comments (";
        $cql .= implode(', ', array_keys($fields));
        $cql .= ") VALUES (";
        $cql .= implode(', ', array_fill(0, count($fields), '?'));
        $cql .= ')';
        $values = array_values($fields);

        $query = new Custom();
        $query->query($cql, $values);

        try {
            if ($this->legacyRepository->isFallbackEnabled()) {
                $this->legacyRepository->add($comment, $attributes, !$comment->isEphemeral());
            }
        } catch (\Exception $e) {
            error_log("[Comments\Repository::add/legacy] {$e->getMessage()} > " . get_class($e));
        }

        try {
            $this->cql->request($query);
        } catch (\Exception $e) {
            error_log("[Comments\Repository::add] {$e->getMessage()} > " . get_class($e));
            return false;
        }

        return true;
    }

    /**
     * Updates a Comment entity. Passthru to add().
     * @param Comment $comment
     * @param array $attributes
     * @return bool
     */
    public function update(Comment $comment, array $attributes = null)
    {
        return $this->add($comment, $attributes);
    }


    /**
     * Deletes a Comment entity
     * @param Comment $comment
     * @return bool
     */
    public function delete(Comment $comment)
    {
        $cql = "DELETE FROM comments WHERE
          entity_guid = ? AND
          parent_guid = ? AND
          guid = ?";

        $values = [
            new Varint($comment->getEntityGuid()),
            new Varint($comment->getParentGuid()),
            new Varint($comment->getGuid())
        ];

        $query = new Custom();
        $query->query($cql, $values);

        try {
            if ($this->legacyRepository->isFallbackEnabled()) {
                $this->legacyRepository->delete($comment);
            }
        } catch (\Exception $e) {
            error_log("[Comments\Repository::delete/legacy] {$e->getMessage()} > " . get_class($e));
        }

        try {
            $this->cql->request($query);
        } catch (\Exception $e) {
            error_log("[Comments\Repository::delete] {$e->getMessage()} > " . get_class($e));
            return false;
        }

        $comment->setEphemeral(true);

        return true;
    }
}
