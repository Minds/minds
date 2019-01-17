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
        'parentGuidL1',
        'parentGuidL2',
        'parentGuidL3',
        'guid',
        'repliesCount',
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
            'parent_guid_l1' => null,
            'parent_guid_l2' => null,
            'parent_guid_l3' => 0, //future support
            'parent_path' => '0:0:0',
            'guid' => null,
            'limit' => null,
            'offset' => null,
            'include_offset' => false,
            'token' => null,
            'descending' => true
        ], $opts);

        $parent_guids = explode(':', $opts['parent_path']);
        $opts['parent_guid_l1'] = $parent_guids[0];
        $opts['parent_guid_l2'] = $parent_guids[1];
        $opts['parent_guid_l3'] = 0; //do not support l3 yet
        
        $cql = "SELECT * from comments";
        $values = [];
        $cqlOpts = [];

        $where = [];

        if ($opts['entity_guid']) {
            $where[] = 'entity_guid = ?';
            $values[] = new Varint($opts['entity_guid']);
        }

        if ($opts['parent_guid_l1'] !== null) {
            $where[] = 'parent_guid_l1 = ?';
            $values[] = new Varint((int) $opts['parent_guid_l1']);
        }

        if ($opts['parent_guid_l2'] !== null) {
            $where[] = 'parent_guid_l2 = ?';
            $values[] = new Varint((int) $opts['parent_guid_l2']);
        }

        // Do not allow l3 at the moment
        // but still pass as we need to query
        $where[] = 'parent_guid_l3 = ?';
        $values[] = new Varint($opts['parent_guid_l3']);


        if ($opts['guid']) {
            $where[] = 'guid = ?';
            $values[] = new Varint($opts['guid']);
        }
        if ($opts['offset']) {
            if ($opts['include_offset']) {
                $where[] = $opts['descending'] ? " guid <= ?" : "guid >= ?";

            } else {
                $where[] = $opts['descending'] ? " guid < ?" : "guid > ?";
            }
            $values[] = new Varint($opts['offset']);
        }

        if ($where) {
            $cql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (!$opts['descending']) {
            $cql .= ' ORDER BY parent_guid_l1 DESC, parent_guid_l2 DESC, parent_guid_l3 DESC, guid ASC';
        }

        if ($opts['token']) {
            $cqlOpts['paging_state_token'] = base64_decode($opts['token']);
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
                    ->setParentGuidL1($row['parent_guid_l1'])
                    ->setParentGuidL2($row['parent_guid_l2'])
                    ->setGuid($row['guid'])
                    ->setOwnerGuid($row['owner_guid'])
                    ->setTimeCreated($row['time_created'])
                    ->setTimeUpdated($row['time_updated'])
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

                $comment->setRepliesCount($this->countReplies($comment));

                $comments[] = $comment;
            }

            if ($rows)
              $comments->setPagingToken(base64_encode($rows->pagingStateToken()));
        } catch (\Exception $e) {
            error_log($e);
        }

        return $comments;
    }

    /**
     * Gets a single comment based on its primary keys
     * @return Comment|null
     */
    public function get($entity_guid, $parent_path, $guid)
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
                'parent_path' => $parent_path, //do not support l3 yet
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
    public function countReplies($comment)
    {
        if (!$comment) {
            return 0;
        }

        if ($this->legacyRepository->isLegacy($comment->getEntityGuid())) {
            if ($comment->getParentGuidL1() > 0) {
                return 0;
            }

            return $this->legacyRepository->count($comment->getEntityGuid());
        }

        $cql = "SELECT COUNT(*) as count FROM comments WHERE entity_guid = ?";
        $values = [
            new Varint($comment->getEntityGuid())
        ];

        $l1 = $comment->getGuid();
        $l2 = 0;

        if ($comment->getParentGuidL1()) {
            $l1 = $comment->getParentGuidL1();
            $l2 = $comment->getGuid();
        }

        $cql .= " AND parent_guid_l1 = ?";
        $values[] = new Varint($l1);

        $cql .= " AND parent_guid_l2 = ?";
        $values[] = new Varint($l2);

        $cql .= " AND parent_guid_l3 = ?";
        $values[] = new Varint(0);

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

        if (in_array('repliesCount', $attributes)) {
            $fields['replies_count'] = new Varint($comment->getRepliesCount());
        }

        if (in_array('parentGuidL1', $attributes)) {
            $fields['parent_guid_l1'] = new Varint($comment->getParentGuidL1() ?: 0);
        }

        if (in_array('parentGuidL2', $attributes)) {
            $fields['parent_guid_l2'] = new Varint($comment->getParentGuidL2() ?: 0);
        }

        if (in_array('ownerGuid', $attributes)) {
            $fields['owner_guid'] = new Varint($comment->getOwnerGuid() ?: 0);
        }

        if (in_array('timeCreated', $attributes)) {
            $fields['time_created'] = new Timestamp($comment->getTimeCreated());
        }

        if (in_array('timeUpdated', $attributes)) {
            $fields['time_updated'] = new Timestamp($comment->getTimeUpdated());
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
            'parent_guid_l1' => new Varint($comment->getParentGuidL1()),
            'parent_guid_l2' => new Varint($comment->getParentGuidL2()),
            'parent_guid_l3' => new Varint(0),
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
          parent_guid_l1 = ? AND
          parent_guid_l2 = ? AND
          parent_guid_l3 = ? AND
          guid = ?";

        $values = [
            new Varint($comment->getEntityGuid()),
            new Varint($comment->getParentGuidL1()),
            new Varint($comment->getParentGuidL2()),
            new Varint(0),
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
