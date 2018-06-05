<?php

/**
 * PostSubscription Entity
 *
 * @author emi
 */

namespace Minds\Core\Notification\PostSubscriptions;

use Minds\Traits\MagicAttributes;

/**
 * Class PostSubscription
 * @package Minds\Core\Notification\PostSubscriptions
 * @method PostSubscription setEntityGuid(int $value)
 * @method int getEntityGuid()
 * @method PostSubscription setUserGuid(int $value)
 * @method int getUserGuid()
 * @method PostSubscription setFollowing(bool $value)
 * @method bool isFollowing()
 * @method PostSubscription setEphemeral(bool $value)
 * @method bool isEphemeral()
 */
class PostSubscription implements \JsonSerializable
{
    use MagicAttributes;

    /** @var int */
    protected $entityGuid;

    /** @var int */
    protected $userGuid;

    /** @var bool */
    protected $following;

    /** @var bool */
    protected $ephemeral = false;

    /**
     * Exports the entity
     * @return array
     */
    public function export()
    {
        return [
            'entity_guid' => (string) $this->entityGuid,
            'user_guid' => (string) $this->userGuid,
            'following' => $this->following,
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->export();
    }
}