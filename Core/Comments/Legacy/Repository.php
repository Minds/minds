<?php

/**
 * Comments Legacy Repository
 *
 * @author emi
 */

namespace Minds\Core\Comments\Legacy;

use Minds\Common\Repository\Response;
use Minds\Core\Comments\Comment;
use Minds\Core\Data\Call;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Di\Di;

class Repository
{
    /** @var Client */
    protected $cql;

    /** @var Call */
    protected $entities;

    /** @var Call */
    protected $indexes;

    /** @var int */
    protected $ltGuid;

    /** @var Entity */
    protected $legacyEntity;

    /** @var bool */
    protected $fallbackEnabled;

    /** @var string */
    protected $lastPaginationToken;

    /**
     * Legacy Repository constructor.
     * @param Client $cql
     * @param Call $entities
     * @param Call $indexes
     * @param int $ltGuid
     * @param Entity $legacyEntity
     */
    public function __construct(
        $cql = null,
        $entities = null,
        $indexes = null,
        $ltGuid = null,
        $legacyEntity = null,
        $fallbackEnabled = null
    )
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->entities = $entities ?: new Call('entities');
        $this->indexes = $indexes ?: new Call('entities_by_time');
        $this->ltGuid = $ltGuid ?: Di::_()->get('Config')->get('comments_migration_guid');
        $this->legacyEntity = $legacyEntity ?: new Entity();
        $this->fallbackEnabled = $fallbackEnabled ?: Di::_()->get('Config')->get('comments_migration_fallback');
    }

    /**
     * Returns whether double writes should be performed
     * @return bool
     */
    public function isFallbackEnabled()
    {
        return !!$this->fallbackEnabled;
    }

    /**
     * Returns whether legacy operations should be done or not
     * @param mixed $entity_guid
     * @return bool
     */
    public function isLegacy($entity_guid = null)
    {
        return $this->ltGuid &&
            $entity_guid &&
            is_numeric($entity_guid) &&
            strlen($entity_guid) >= 18 &&
            $entity_guid < $this->ltGuid;
    }

    /**
     * Returns a list of Comment entities from the legacy table
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 3,
            'offset' => '',
            'descending' => true, // formerly 'reversed'
        ], $opts);

        $guids = $this->indexes->getRow("comments:{$opts['entity_guid']}", [
            'limit' => $opts['limit'],
            'offset' => $opts['offset'] ? base64_decode($opts['offset']) : '',
            'reversed' => $opts['descending']
        ]);

        if (!$guids) {
            return new Response();
        }

        $rows = $this->entities->getRows(array_keys($guids));

        if ($opts['offset'] && $opts['limit'] > 1) {
            unset($rows[base64_decode($opts['offset'])]);
        }

        if (!$rows) {
            return new Response();
        }

        $comments = new Response();

        foreach ($rows as $key => $row) {
            $row['guid'] = $key;
            $comments[] = $this->legacyEntity->build($row);
        }

        $comments->setPagingToken(base64_encode($comments->end()->getGuid()));

        return $comments;
    }

    /**
     * Counts the comments on an entity
     * @param int $entity_guid
     * @return int
     */
    public function count($entity_guid)
    {
        return (int) $this->indexes->countRow('comments:' . $entity_guid);
    }

    /**
     * Adds/Updates a comment from the legacy tables
     * @param Comment $comment
     * @param array|null $attributes
     * @return bool
     */
    public function add(Comment $comment, array $attributes = null, $isUpdating = false)
    {
        $fields = [];

        if (in_array('ownerGuid', $attributes)) {
            $fields['owner_guid'] = (string) $comment->getOwnerGuid();
        }

        if (in_array('containerGuid', $attributes)) {
            $fields['container_guid'] = (string) ($comment->getContainerGuid() ?: 0);
        }

        if (in_array('timeCreated', $attributes)) {
            $fields['time_created'] = (string) $comment->getTimeCreated();
        }

        if (in_array('timeUpdated', $attributes)) {
            $fields['time_updated'] = (string) $comment->getTimeUpdated();
        }

        if (in_array('accessId', $attributes)) {
            $fields['access_id'] = (string) $comment->getAccessId();
        }

        if (in_array('body', $attributes)) {
            $fields['description'] = (string) $comment->getBody();
        }

        if (in_array('attachments', $attributes)) {
            $attachments = $comment->getAttachments() ?: [];

            foreach ($attachments as $key => $value) {
                $fields[$key] = (string) $value;
            }
        }

        if (in_array('mature', $attributes)) {
            $fields['mature'] = $comment->isMature() ? '1' : '';
        }

        if (in_array('edited', $attributes)) {
            $fields['edited'] = $comment->isEdited() ? '1' : '';
        }

        if (in_array('spam', $attributes)) {
            $fields['spam'] = $comment->isSpam() ? '1' : '';
        }

        if (in_array('deleted', $attributes)) {
            $fields['deleted'] = $comment->isDeleted() ? '1' : '';
        }

        if (in_array('ownerObj', $attributes)) {
            $fields['owner_obj'] = $comment->getOwnerObj() ? json_encode($comment->getOwnerObj()) : null;
        }

        if (!$fields) {
            // No changes
            return true;
        }

        $fields = array_merge($fields, [
            'parent_guid' => (string) $comment->getEntityGuid(),
            'guid' => (string) $comment->getGuid(),
        ]);

        $guid = (string) $comment->getGuid();
        $entityGuid = (string) $comment->getEntityGuid();

        $success = $this->entities->insert($guid, $fields);

        if ($success) {
            $this->indexes->insert("comments:{$entityGuid}", [ $guid => $guid ]);

            // Store migration action
            $this->indexes->insert("migration:comments", [ "{$entityGuid}:{$guid}" => $isUpdating ? 'updated' : 'added' ]);
        }

        return !!$success;
    }

    /**
     * Deletes a comment from the legacy tables
     * @param Comment $comment
     * @return bool
     */
    public function delete(Comment $comment)
    {
        $guid = (string) $comment->getGuid();
        $entityGuid = (string) $comment->getEntityGuid();

        if (!$guid || !$entityGuid) {
            return false;
        }

        // Delete entity and index
        $this->entities->removeRow($guid);
        $this->indexes->removeAttributes("comments:{$entityGuid}", [ $guid ]);

        // Store migration action
        $this->indexes->insert("migration:comments", [ "{$entityGuid}:{$guid}" => 'deleted' ]);

        return true;
    }

    /**
     * Old comments lookup
     * @param string $guid
     * @return Comment|null
     */
    public function getByGuid($guid)
    {
        try {
            $row = $this->entities->getRow((string) $guid);

            if (!$row || $row['type'] !== 'comment') {
                return null;
            }

            $row['guid'] = (string) $guid;

            return $this->legacyEntity->build($row);
        } catch (\Exception $e) {
            error_log("[Comments\Legacy\Repository::getByGuid] {$e->getMessage()} > " . get_class($e));
        }

        return null;
    }
}
