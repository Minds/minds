<?php
namespace Minds\Helpers;

use Minds\Core;
use Minds\Core\Security;
use Minds\Core\Events;
use Minds\Entities\User;
use Minds\Helpers\Wallet;

/**
 * Helper for Users subscriptions
 * @todo Avoid static and use proper DI
 */
class Subscriptions
{
    /**
     * Subscribe a user to a user
     * @param  mixed $user_guid - the user who is doing the action, eg. me
     * @param  mixed $to_guid   - the user to subscribe to
     * @param  array $data      - metadata. Optional.
     * @return boolean
     */
    public static function subscribe($user_guid, $to_guid, $data = array())
    {
        if (static::isSubscribed($user_guid, $to_guid)) {
            return false;
        }

        $return = false;
        if (empty($data)) {
            $data = time();
        }

        $friends = new Core\Data\Call('friends');
        $friendsof = new Core\Data\Call('friendsof');


        if (is_array($data)) {
            $data = json_encode($data);
        }

        if ($friends->insert($user_guid, array($to_guid=>$data)) && $friendsof->insert($to_guid, array($user_guid=>$data))) {
            $return =  true;
        }

        try {
            $prepared = new Core\Data\Neo4j\Prepared\Common();
            Core\Data\Client::build('Neo4j')->requestWrite($prepared->createSubscription($user_guid, $to_guid));
        } catch (\Exception $e) {
            error_log("could not write $user_guid subscription to $to_guid in neo4j");
        }
        //grab the newsfeed
        $nf = new Core\Data\Call('entities_by_time');
        $feed = $nf->getRow("activity:user:$to_guid", array('limit'=>12));
        if ($feed) {
            $nf->insert("activity:network:$user_guid", $feed);
        }

        $cacher = Core\Data\cache\factory::build();
        $cacher->set("$user_guid:isSubscribed:$to_guid", true);
        $cacher->set("$to_guid:isSubscriber:$user_guid", true);
        //$cacher->destroy("friendsof:$to_guid");
        //$cacher->destroy("friends:$user_guid");

        $cacher->destroy("$to_guid:friendsofcount");
        $cacher->destroy("$user_guid:friendscount");

        //\Minds\Core\Data\cache\factory::build()->set("$user_guid:friendof:$to_guid", 'yes');
        if ($return) {
            Events\Dispatcher::trigger('subscribe', 'all', array('user_guid'=>$user_guid, 'to_guid'=>$to_guid));
        }
        Events\Dispatcher::trigger('notification', 'subscription', array(
                'to'=>array($to_guid),
                'entity' => $user_guid,
                'notification_view' => 'friends',
                'from' => $user_guid,
                'params' => array()
                ));

        return (bool) $return;
    }

    /**
     * Unsubscribes a user from a user
     * @param  mixed $user - the user who is doing the action, eg. me
     * @param  mixed $from - the user to unsubscribe from
     * @return boolean
     */
    public static function unSubscribe($user, $from)
    {
        if (!static::isSubscribed($user, $from)) {
            return false;
        }

        $return = false;

        $friends = new Core\Data\Call('friends');
        $friendsof = new Core\Data\Call('friendsof');
        error_log("$user is unsubscribing from $from");
        $friends->removeAttributes($user, array($from));
        $friendsof->removeAttributes($from, array($user));
        $return = true;

        //@todo make unsubscribe work with neo4j
        //$prepared = new Core\Data\Neo4j\Prepared\Common();
        //$return =  Core\Data\Client::build('Neo4j')->request($prepared->createSubscription($user_guid, $to_guid));

        //grab the newsfeed
        $nf = new Core\Data\Call('entities_by_time');
        $feed = $nf->getRow("activity:user:$from", array('limit'=>12));
        if ($feed) {
            $nf->removeAttributes("activity:network:$user", array_keys($feed));
        }

        $cacher = Core\Data\cache\factory::build();
        $cacher->set("$user:isSubscribed:$from", false);
        $cacher->set("$from:isSubscriber:$user", false);
        //$cacher->destroy("friendsof:$from");
        //$cacher->destroy("friends:$user");

        $cacher->destroy("$from:friendsofcount");
        $cacher->destroy("$user:friendscount");

        if ($return) {
            Events\Dispatcher::trigger('unsubscribe', 'all', [ 'user_guid'=>$user, 'from_guid'=>$from]);
        }

        //\Minds\Core\Data\cache\factory::build()->set("$user:friendof:$from", 'no');
        return (bool) $return;
    }

    /**
     * Checks if a user is subscribed to another.
     * @param  mixed $user - the user who is doing the action, eg. me
     * @param  mixed $to   - the user to check subscription to
     * @return boolean
     */
    public static function isSubscribed($user, $to)
    {
        $cacher = Core\Data\cache\factory::build();

        if ($cacher->get("$user:isSubscribed:$to")) {
            return true;
        }
        if ($cacher->get("$user:isSubscribed:$to") === 0) {
            return false;
        }

        $return = 0;
        $db = new Core\Data\Call('friends');
        $row = $db->getRow($user, array('limit'=> 1, 'offset'=>$to));
        if ($row && key($row) == $to) {
            $return = true;
        }

        $cacher->set("$user:isSubscribed:$to", $return);

        return (bool) $return ;
    }

    /**
     * Checks if a user is subscribed to another in a
     * reversed way than isSubscribed()
     * @param  mixed $user - the user who is doing the action, eg. me
     * @param  mixed $to   - the user to check subscription to
     * @return boolean
     */
    public static function isSubscriber($user, $to)
    {
        $cacher = Core\Data\cache\factory::build();

        if ($cacher->get("$user:isSubscriber:$to")) {
            return true;
        }
        if ($cacher->get("$user:isSubscriber:$to") === 0) {
            return false;
        }

        $return = 0;
        $db = new Core\Data\Call('friendsof');
        $row = $db->getRow($user, array('limit'=> 1, 'offset'=>$to));
        if ($row && key($row) == $to) {
            $return = true;
        }

        $cacher->set("$user:isSubscriber:$to", $return);

        return (bool) $return;
    }

    /**
     * TBD. Not implemented.
     * @param  mixed $user
     * @return mixed
     */
    public static function getSubscriptions($user)
    {
    }

    /**
     * TBD. Not implemented.
     * @param  mixed $user
     * @return mixed
     */
    public static function getSubscribers($user)
    {
    }

    public static function registerEvents()
    {
        Events\Dispatcher::register('subscribe', 'all', function (Events\Event $event) {
            $params = $event->getParameters();

            Wallet::createTransaction($params['to_guid'], 5, $params['user_guid'], 'Subscription');
        });

        Events\Dispatcher::register('unsubscribe', 'all', function (Events\Event $event) {
            $params = $event->getParameters();

            Wallet::createTransaction($params['from_guid'], -5, $params['user_guid'], 'Subscription Removed');
        });

        Events\Dispatcher::register('subscription:dispatch', 'all', function(Events\Event $event) {
            $params = $event->getParameters();

            $currentUser = new User($params['currentUser']);
            $guids = $params['guids'];

            if (!is_array($guids)) {
                $guids = [ $guids ];
            }

            if (!$currentUser || !($currentUser instanceof User)) {
                $event->setResponse([ 'done' => false, 'error' => 'INVALID_USER' ]);
                return;
            }

            $results = [];

            foreach ($guids as $guid) {
                try {
                    $target = new User($guid);

                    if (!($target instanceof User) || !$target->guid) {
                        $results[] = [
                            'guid' => $guid,
                            'done' => false,
                            'error' => 'INVALID_TARGET'
                        ];
                        continue;
                    }

                    $canSubscribe = Security\ACL::_()->interact($currentUser, $target) &&
                        Security\ACL::_()->interact($target, $currentUser);

                    if (!$canSubscribe) {
                        $results[] = [
                            'guid' => $guid,
                            'done' => false,
                            'error' => 'BLOCKED_USER'
                        ];
                        continue;
                    }

                    $success = $currentUser->subscribe($target->guid);

                    $results[] = [ 'guid' => $guid, 'done' => true ];
                } catch (\Exception $e) {
                    $results[] = [
                        'guid' => $guid,
                        'done' => false,
                        'exception' => $e
                    ];
                }
            }

            $event->setResponse([ 'done' => true, 'results' => $results ]);
        });
    }
}
