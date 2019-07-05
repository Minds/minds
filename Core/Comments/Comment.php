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
 * @method Comment setParentGuidL1(int $value)
 * @method int getParentGuidL1()
 * @method Comment setParentGuidL2(int $value)
 * @method int getParentGuidL2()
 * @method Comment setGuid(int $value)
 * @method Comment setRepliesCount(int $value)
 * @method int getRepliesCount())
 * @method Comment setOwnerGuid(int $value)
 * @method int getOwnerGuid()
 * @method Comment setTimeCreated(int $value)
 * @method int getTimeCreated()
 * @method Comment setTimeUpdated(int $value)
 * @method int getTimeUpdated()
 * @method Comment setBody(string $value)
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
    protected $parentGuidL1;

    /** @var int */
    protected $parentGuidL2;

    /** @var int */
    protected $parentGuidL3 = 0; // Not supported yet

    /** @var int */
    protected $guid;

    /** @var int */
    protected $repliesCount = 0;

    /** @var int */
    protected $ownerGuid;

    /** @var int */
    protected $timeCreated;

    /** @var int */
    protected $timeUpdated;

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
    protected $groupConversation = false;

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

    /**
     * @return Luid
     * @throws \Minds\Exceptions\InvalidLuidException
     */
    public function getLuid()
    {
        $luid = new Luid();

        $luid
            ->setType('comment')
            ->setEntityGuid((string) $this->getEntityGuid())
            ->setPartitionPath($this->getPartitionPath())
            ->setParentPath($this->getParentPath())
            ->setChildPath($this->getChildPath())
            ->setGuid((string) $this->getGuid());

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
     * @throws \Exception
     */
    public function getOwnerObj()
    {
        if (!$this->ownerObj && $this->ownerGuid) {
            $user = new User($this->ownerGuid);
            $user->fullExport = false;
            $this->setOwnerObj($user->export());
        }

        return $this->ownerObj;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        if (strlen($this->body) > 1500) {
            return substr($this->body, 0, 1500) . '...';
        }
        return $this->body;
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
     * Get exact path, includes all the partition 
     * @return string
     */
    public function getPartitionPath()
    {
        return "{$this->getParentGuidL1()}:{$this->getParentGuidL2()}:{$this->getParentGuidL3()}";
    }

    /**
     * Return the partition path of the parent
     * that can be used to grab the parent thread
     * @return string
     */
    public function getParentPath()
    {
        if ($this->getParentGuidL2() == 0) {
            return "0:0:0";
        }
        return "{$this->getParentGuidL1()}:0:0";
    }

    /**
     * Return the partition path to be used to 
     * fetch child replies
     */
    public function getChildPath()
    {
        if ($this->getParentGuidL1() == 0) { //No parent so we are at the top level
            return "{$this->getGuid()}:0:0";
        }
        if ($this->getParentGuidL2() == 0) { //No level2 so we are at the l1 level
            return "{$this->getParentGuidL1()}:{$this->getGuid()}:0";
        }
        return "{$this->getParentGuidL1()}:{$this->getParentGuidL2()}:{$this->getGuid()}";
    }

    /**
     * Return the urn for the comment
     * @return string
     */
    public function getUrn()
    {
        return implode(':', [
            'urn',
            'comment',
            $this->getEntityGuid(),
            $this->getPartitionPath(),
            $this->getGuid(),
        ]);
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
            'parentGuidL1',
            'parentGuidL2',
            'guid',
            'repliesCount',
            'ownerGuid',
            'timeCreated',
            'timeUpdated',
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
     * @throws \Minds\Exceptions\InvalidLuidException
     */
    protected function _extendExport(array $export)
    {
        $output = [];

        $output['_guid'] = (string) $export['guid'];
        $output['guid'] = $output['luid'] = (string) $this->getLuid();

        $output['entity_guid'] = (string) $this->getEntityGuid();

        $output['parent_guid_l1'] = (string) $this->getParentGuidL1();
        $output['parent_guid_l2'] = (string) $this->getParentGuidL2();

        $output['partition_path'] = $this->getPartitionPath();
        $output['parent_path'] = $this->getParentPath();
        $output['child_path'] = $this->getChildPath();

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

        $output['can_reply'] = (bool) !$this->getParentGuidL2();

        //$output['parent_guid'] = (string) $this->entityGuid;

        return $output;
    }
}
