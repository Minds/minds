<?php

namespace Minds\Controllers\api\v2\newsfeed;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Security;
use Minds\Entities;
use Minds\Entities\Activity;
use Minds\Helpers;
use Minds\Helpers\Counters;
use Minds\Interfaces;
use Minds\Interfaces\Flaggable;

class remind implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        $embedded = new Entities\Entity($pages[0]);
        $embedded = core\Entities::build($embedded); //more accurate, as entity doesn't do this @todo maybe it should in the future

        //check to see if we can interact with the parent
        if (!Security\ACL::_()->interact($embedded)) {
            return false;
        }

        Counters::increment($embedded->guid, 'remind');

        if ($embedded->owner_guid != Core\Session::getLoggedinUser()->guid) {
            Core\Events\Dispatcher::trigger('notification', 'remind', [
                'to' => [$embedded->owner_guid],
                'notification_view' => 'remind',
                'params' => ['title' => $embedded->title ?: $embedded->message],
                'entity' => $embedded
            ]);
        }

        $message = '';

        if (isset($_POST['message'])) {
            $message = rawurldecode($_POST['message']);
        }

        /*if ($embeded->owner_guid != Core\Session::getLoggedinUser()->guid) {
            $cacher = \Minds\Core\Data\cache\Factory::build();
            if (!$cacher->get(Core\Session::getLoggedinUser()->guid . ":hasreminded:$embeded->guid")) {
                $cacher->set(Core\Session::getLoggedinUser()->guid . ":hasreminded:$embeded->guid", true);

                Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, 1, $embeded->guid, 'remind');
                Helpers\Wallet::createTransaction($embeded->owner_guid, 1, $embeded->guid, 'remind');
            }
        }*/

        $activity = new Activity();
        switch ($embedded->type) {
            case 'activity':
                if ($message) {
                    $activity->setMessage($message);
                }

                if ($embedded->remind_object) {
                    $activity->setRemind($embedded->remind_object)->save();
                    Counters::increment($embedded->remind_object['guid'], 'remind');
                } else {
                    $activity->setRemind($embedded->export())->save();
                }
                $activity->save();
                break;
            default:
                /**
                 * The following are actually treated as embeded posts.
                 */
                switch ($embedded->subtype) {
                    case 'blog':
                        /** @var Core\Blogs\Blog $embedded */
                        if ($embedded->getOwnerGUID() == Core\Session::getLoggedInUserGuid()) {
                            $activity->setTitle($embedded->getTitle())
                                ->setBlurb(strip_tags($embedded->getBody()))
                                ->setURL($embedded->getURL())
                                ->setThumbnail($embedded->getIconUrl())
                                ->setFromEntity($embedded)
                                ->setMessage($message)
                                ->save();
                        } else {
                            $activity->setRemind((new Activity())
                                ->setTimeCreated($embedded->getTimeCreated())
                                ->setTitle($embedded->getTitle())
                                ->setBlurb(strip_tags($embedded->getBody()))
                                ->setURL($embedded->getURL())
                                ->setThumbnail($embedded->getIconUrl())
                                ->setFromEntity($embedded)
                                ->export())
                                ->setMessage($message)
                                ->save();
                        }
                        break;
                    case 'video':
                        if ($embedded->owner_guid == Core\Session::getLoggedInUserGuid()) {
                            $activity->setFromEntity($embedded)
                                ->setCustom('video', [
                                    'thumbnail_src' => $embedded->getIconUrl(),
                                    'guid' => $embedded->guid,
                                    'mature' => $embedded instanceof Flaggable ? $embedded->getFlag('mature') : false
                                ])
                                ->setTitle($embedded->title)
                                ->setBlurb($embedded->description)
                                ->setMessage($message)
                                ->save();
                        } else {
                            $activity = new Activity();
                            $activity->setRemind(
                                (new Activity())
                                    ->setTimeCreated($embedded->time_created)
                                    ->setFromEntity($embedded)
                                    ->setCustom('video', [
                                        'thumbnail_src' => $embedded->getIconUrl(),
                                        'guid' => $embedded->guid,
                                        'mature' => $embedded instanceof Flaggable ? $embedded->getFlag('mature') : false
                                    ])
                                    ->setMature($embedded instanceof Flaggable ? $embedded->getFlag('mature') : false)
                                    ->setTitle($embedded->title)
                                    ->setBlurb($embedded->description)
                                    ->export()
                                )
                                ->setMessage($message)
                                ->save();
                        }
                        break;
                    case 'image':
                        if ($embedded->owner_guid == Core\Session::getLoggedInUserGuid()) {
                            $activity->setCustom('batch', [
                                [
                                    'src' => elgg_get_site_url() . 'fs/v1/thumbnail/' . $embedded->guid,
                                    'href' => elgg_get_site_url() . 'media/' . $embedded->container_guid . '/' . $embedded->guid,
                                    'mature' => $embedded instanceof Flaggable ? $embedded->getFlag('mature') : false,
                                    'width' => $embedded->width,
                                    'height' => $embedded->height,
                                ]
                            ])
                                ->setFromEntity($embedded)
                                ->setTitle($embedded->title)
                                ->setBlurb($embedded->description)
                                ->setMessage($message)
                                ->save();
                        } else {
                            $activity->setRemind(
                                (new Activity())
                                    ->setTimeCreated($embedded->time_created)
                                    ->setCustom('batch', [
                                        [
                                            'src' => elgg_get_site_url() . 'fs/v1/thumbnail/' . $embedded->guid,
                                            'href' => elgg_get_site_url() . 'media/' . $embedded->container_guid . '/' . $embedded->guid,
                                            'mature' => $embedded instanceof Flaggable ? $embedded->getFlag('mature') : false,
                                            'width' => $embedded->width,
                                            'height' => $embedded->height,
                                        ]
                                    ])
                                    ->setMature($embedded instanceof Flaggable ? $embedded->getFlag('mature') : false)
                                    ->setFromEntity($embedded)
                                    ->setTitle($embedded->title)
                                    ->setBlurb($embedded->description)
                                    ->export()
                                )
                                ->setMessage($message)
                                ->save();
                        }
                        break;
                }
        }

        $event = new Core\Analytics\Metrics\Event();
        $event->setType('action')
            ->setAction('remind')
            ->setProduct('platform')
            ->setUserGuid((string) Core\Session::getLoggedInUser()->guid)
            ->setUserPhoneNumberHash(Core\Session::getLoggedInUser()->getPhoneNumberHash())
            ->setEntityGuid((string) $embedded->guid)
            ->setEntityContainerGuid((string) $embedded->container_guid)
            ->setEntityType($embedded->type)
            ->setEntitySubtype((string) $embedded->subtype)
            ->setEntityOwnerGuid((string) $embedded->ownerObj['guid'])
            ->push();

        $mature_remind =
            ($embedded instanceof Flaggable ? $embedded->getFlag('mature') : false) ||
            (isset($embedded->remind_object['mature']) && $embedded->remind_object['mature']);

        $user = Core\Session::getLoggedInUser();
        if (!$user->getMatureContent() && $mature_remind) {
            $user->setMatureContent(true);
            $user->save();
        }

        if ($embedded->owner_guid != Core\Session::getLoggedinUser()->guid) {
            Helpers\Wallet::createTransaction($embedded->owner_guid, 5, $activity->guid, 'Remind');
        }

        return Factory::response(['guid' => $activity->guid]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}
