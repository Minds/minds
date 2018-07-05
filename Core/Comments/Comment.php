<?php

namespace Minds\Core\Comments;

use Minds\Core\Guid;
use Minds\Core\Luid;
use Minds\Core\Security\ACL;
use Minds\Entities\RepositoryEntity;
use Minds\Entities\User;
use Minds\Helpers\Flags;
use Minds\Helpers\Unknown;

/**
 * Comment Entity
 * @package Minds\Core\Comments
 * @method Comment setEntityGuid(int $value)
 * @method Comment setParentGuid(int $value)
 * @method int getParentGuid()
 * @method Comment setGuid(int $value)
 * @method Comment setHasChildren(bool $value)
 * @method bool getHasChildren()
 * @method Comment setOwnerGuid(int $value)
 * @method int getOwnerGuid()
 * @method Comment setContainerGuid(int $value)
 * @method int getContainerGuid()
 * @method Comment setTimeCreated(int $value)
 * @method int getTimeCreated()
 * @method Comment setTimeUpdated(int $value)
 * @method int getTimeUpdated()
 * @method Comment setAccessId(int $value)
 * @method int getAccessId()
 * @method Comment setBody(string $value)
 * @method string getBody()
 * @method Comment setAttachments(array $value)
 * @method array getAttachments()
 * @method Comment setMature(bool $value)
 * @method bool isMature()
 * @method Comment setEdited(bool $value)
 * @method bool isEdited()
 * @method Comment setSpam(bool $value)
 * @method bool isSpam()
 * @method Comment setDeleted(bool $value)
 * @method bool isDeleted()
 * @method Comment setVotesUp(array $value)
 * @method array getVotesUp()
 * @method Comment setVotesDown(array $value)
 * @method array getVotesDown()
 * @method Comment setEphemeral(bool $value)
 * @method bool isEphemeral()
 */
class Comment extends RepositoryEntity
{
    /** @var string */
    protected $type = 'comment';

    /** @var int */
    protected $entityGuid;

    /** @var int */
    protected $parentGuid;

    /** @var int */
    protected $guid;

    /** @var bool */
    protected $hasChildren = false;

    /** @var int */
    protected $ownerGuid;

    /** @var int */
    protected $containerGuid;

    /** @var int */
    protected $timeCreated;

    /** @var int */
    protected $timeUpdated;

    /** @var int */
    protected $accessId = 2;

    /** @var string */
    protected $body;

    /** @var array */
    protected $attachments = [];

    /** @var bool */
    protected $mature = false;

    /** @var bool */
    protected $edited = false;

    /** @var bool */
    protected $spam = false;

    /** @var bool */
    protected $deleted = false;

    /** @var array */
    protected $ownerObj;

    /** @var array */
    protected $votesUp;

    /** @var array */
    protected $votesDown;

    /** @var bool */
    protected $ephemeral = true;

    /**
     * Gets the entity guid for the comment.
     * !!!NOTE!!! Needed for 'create' event hook
     * @return int
     */
    public function getEntityGuid()
    {
        return $this->entityGuid;
    }

    public function getLuid()
    {
        $luid = new Luid();

        $luid
            ->setType('comment')
            ->setEntityGuid($this->getEntityGuid())
            ->setParentGuid($this->getParentGuid())
            ->setGuid($this->getGuid());

        return $luid;
    }

    /**
     * @return int
     */
    public function getGuid()
    {
        if (!$this->guid) {
            $this->setGuid(Guid::build());
        }

        return $this->guid;
    }

    /**
     * @param array|string $value
     * @return $this
     */
    public function setOwnerObj($value)
    {
        if (is_string($value) && $value) {
            $value = json_decode($value, true);
        } else if ($value instanceof User) {
            $value = $value->export();
        }

        $this->ownerObj = $value;
        $this->markAsDirty('ownerObj');

        if ($value && !$this->ownerGuid) {
            $this->ownerGuid = $value['guid'];
            $this->markAsDirty('ownerGuid');
        }

        return $this;
    }

    /**
     * Gets (hydrates if necessary) the owner object
     * @return array
     */
    public function getOwnerObj()
    {
        if (!$this->ownerObj && $this->ownerGuid) {
            $user = new User($this->ownerGuid);
            $this->setOwnerObj($user->export());
        }

        return $this->ownerObj;
    }

    /**
     * Sets an individual attachment
     * @param $attachment
     * @param mixed $value
     * @return Comment
     */
    public function setAttachment($attachment, $value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $this->attachments[$attachment] = (string) $value;
        $this->markAsDirty('attachments');

        return $this;
    }

    /**
     * Gets an individual attachment's value
     * @param $attachment
     * @return bool
     */
    public function getAttachment($attachment)
    {
        if (!isset($this->attachments[$attachment])) {
            return false;
        }

        if (in_array(substr($this->attachments[$attachment], 0, 1), ['[', '{'])) {
            return json_decode($this->attachments[$attachment], true);
        }

        return $this->attachments[$attachment];
    }

    /**
     * Returns if the entity can be edited
     * @param User|null $user
     * @return bool
     */
    public function canEdit(User $user = null)
    {
        return ACL::_()->write($this, $user);
    }

    /**
     * Defines the exportable members
     * @return array
     */
    public function getExportable()
    {
        return [
            'type',
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
            function ($export) {
                return $this->_extendExport($export);
            }
        ];
    }

    /**
     * @param array $export
     * @return array
     */
    protected function _extendExport(array $export)
    {
        $output = [];

        $output['_guid'] = (string) $export['guid'];
        $output['guid'] = $output['luid'] = (string) $this->getLuid();

        // Legacy
        $output['ownerObj'] = $this->getOwnerObj();
        $output['description'] = $this->getBody();

        if (!$output['ownerObj'] && !$this->getOwnerGuid()) {
            $unknown = Unknown::user();

            $output['ownerObj'] = $unknown->export();
            $output['owner_guid'] = $unknown->guid;
        }

        if ($export['attachments']) {
            foreach ($export['attachments'] as $key => $value) {
                $output['attachments'][$key] = $this->getAttachment($key);
                $output[$key] = $output['attachments'][$key];
            }
        }

        if (isset($output['custom_type']) && $output['custom_type'] === 'image') {
            $output['custom_type'] = 'batch';
            $output['custom_data'] = [ $output['custom_data'] ];
        }

        if (!Flags::shouldDiscloseStatus($this)) {
            unset($output['spam']);
            unset($output['deleted']);
        }

        if (!$this->isEphemeral()) {
            $output['thumbs:up:user_guids'] = $this->getVotesUp();
            $output['thumbs:up:count'] = count($this->getVotesUp());

            $output['thumbs:down:user_guids'] = $this->getVotesDown();
            $output['thumbs:down:count'] = count($this->getVotesDown());
        }

        $output['parent_guid'] = (string) $this->entityGuid;

        return $output;
    }
}
