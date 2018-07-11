<?php
namespace Minds\Core\Media;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;

class Thumbnails
{
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    public function get($guid, $size)
    {
        $entity = Entities\Factory::build($guid);
        if (!$entity || !Core\Security\ACL::_()->read($entity)) {
            return false;
        }

        $loggedInUser = Core\Session::getLoggedinUser();

        if (!Di::_()->get('Wire\Thresholds')->isAllowed($loggedInUser, $entity)) {
            return false;
        }

        $user = $entity->getOwnerEntity(false);
        $userGuid = $user->guid;

        if ($user->legacy_guid) {
            $userGuid = $user->legacy_guid;
        }

        $thumbnail = new \ElggFile();
        $thumbnail->owner_guid = $userGuid;
        $thumbnail->setFilename("/archive/thumbnails/$entity->guid.jpg");

        switch ($entity->subtype) {
            case 'image':
                if ($entity->filename) {
                    $thumbnail->setFilename($entity->filename);
                }

                if ($size && !$entity->gif) {
                    if (!isset($entity->batch_guid)) {
                        $entity->batch_guid = $this->container_guid;
                    }

                    $thumbnail->setFilename("image/$entity->batch_guid/$entity->guid/$size.jpg");
                } elseif ($entity->gif) {
                    $thumbnail->setFilename(str_replace('xlarge.jpg', 'master.jpg', $entity->filename));
                }
                break;

            case 'album':
                // Album thumbnails are the first image in it
                $imageGuids = $entity->getChildrenGuids();

                $thumbnail = $imageGuids ? $this->config->get('cdn_url') . 'fs/v1/thumbnail/' . $imageGuids[0] : false;
                break;

            case 'video':
                if (!$entity->thumbnail) {
                    $thumbnail = $this->config->get('cinemr_url') . $entity->cinemr_guid . '/thumbnail-00001.png';

                    break;
                }

                break;

            case 'audio':
                $thumbnail = false;
                break;

            case 'file':
                $thumbnail = $thumbnail->filename;
                break;
        }

        return $thumbnail;
    }
}
