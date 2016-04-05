<?php
/**
 * Common prepared cypher queries
 */
namespace Minds\Core\Data\Neo4j\Prepared;

use Minds\Core\Data\Interfaces;
use Minds\Entities;
use Minds\Core\Analytics\Timestamps;

class Common implements Interfaces\PreparedInterface
{
    private $template;
    private $values = array();

    public function build()
    {
        return array(
            'string' => $this->template,
            'values'=>$this->values
            );
    }

    /**
     * Create a user node
     * @param User $user
     * @return $this
     */
    public function createUser($user)
    {
        $this->template = "MERGE (user:User { guid: {guid}, username:{username} }) ON MATCH SET user += {hasAvatar: {hasAvatar}}";
        $this->values = array('guid'=>$user->guid, 'username'=>$user->username, 'hasAvatar'=>(bool) is_numeric($user->icontime));
        return $this;
    }

    /**
     * Import bulk users
     */
    public function createBulkUsers(array $users = array())
    {
        foreach ($users as $user) {
            $exp[] = array(
                    //    'username'=>$user->username,
                        'guid'=>$user
                        );
        }
        $this->template = "FOREACH (u IN " . preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($exp))  . " | MERGE(user:User {guid: str(u.guid)}))";
        return $this;
    }

    /**
     * Import bulk subscriptions
     */
     public function createBulkSubscriptions(array $subscriptions = array())
     {
         foreach ($subscriptions as $subscriber=> $array) {
             foreach ($array as $subscription_guid => $ts) {
                 $exp[] = array(
                            'guid'=> (string) $subscriber,
                            'subscription_guid' => (string) $subscription_guid
                            );
             }
         }

         $this->template = "UNWIND " . preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($exp)) . " AS row ".
                                "MATCH (u:User {guid:row.guid}), (subscription:User {guid:row.subscription_guid}) " .
                                "MERGE (u)-[:SUBSCRIBED]->(subscription) MERGE (u)-[:ACTED]->(subscription)";
         return $this;
     }

     /**
      * Import bulk subscriber
      */

    /**
     * Create a subscription
     * @param User or integer $user
     * @param User or integer $to
     * @return $this
     */
    public function createSubscription($user, $to)
    {
        $this->template =   "MATCH (user:User {guid: {user_guid}})," .
                            "(to:User {guid: {subscriber_guid}}) " .
                            "MERGE (user)-[:SUBSCRIBED]->(to) MERGE (user)-[:ACTED]->(to)";
        $this->values = array(
            'user_guid' => is_numeric($user) ? (string) $user : $user->guid,
            'subscriber_guid' => is_numeric($to) ? (string) $to: $to->guid,
            );
        return $this;
    }

    /**
     * Create a pass
     * @param integer $user
     * @param integer $to
     * @return $this
     */
    public function createPass($user, $to)
    {
        //error_log("NEO4j PASS Created for $user :: $to");
       $this->template =   "MATCH (user:User {guid: {user_guid}})," .
                            "(to {guid: {subscriber_guid}}) " .
                            "MERGE (user)-[:PASS]->(to) MERGE (user)-[:ACTED]->(to)";
        $this->values = array(
            'user_guid' => (string) $user,
            'subscriber_guid' => (string) $to,
            );
        return $this;
    }

    /**
     * Return subscribers
     * @param User $user
     * @return $this
     */
    public function getSubscribers(Entities\User $user)
    {
        $this->template = "MATCH (user {guid: {guid}})<-[:SUBSCRIBED]-(subscriber) RETURN subscriber";
        $this->values = array('guid'=>$user->guid);
        return $this;
    }

    /**
     * Return subscriptions of subscriptions
     * @param User $user
     * @param int $skip default 0
     * @return $this
     */
    public function getSubscriptionsOfSubscriptions(Entities\User $user, $skip = 0)
    {
        $timestamps = Timestamps::span(15, 'day');
    
        if ($user->getSubscriptionsCount() > 500) {
            //users with huge graphs take longer to discover friends of friends, so for now we just show who they aren't subscribed to for speed.
            //we can perhaps do background tasks for this in the future
            $this->template = "MATCH (user:User {guid: {guid}}), (fof:User {hasAvatar:true}) 
                            WHERE fof.last_active > {active}
                            AND NOT (user)-[:ACTED]->(fof)
                            AND NOT (fof.guid = user.guid)
                            RETURN fof 
                            SKIP {skip} 
                            LIMIT {limit}";
        } else {
            //error_log("loading default matches for $user->guid");
            $this->template = "MATCH (user:User {guid: {guid}})-[:SUBSCRIBED*2..2]->(fof:User {hasAvatar:true})
                             WHERE fof.last_active > {active}
                             AND NOT (user)-[:ACTED]->(fof)
                             AND NOT (fof.guid = user.guid)
                             RETURN fof
                             SKIP {skip}
                             LIMIT {limit}";
        }
        $this->values = [
            'guid' => (string) $user->guid,
            'active' => (int) $timestamps[15],
            'limit' => 16,
            'skip' => (int) $skip
        ];
        return $this;
    }

    /**
     * Return degree
     * @param User $user
     * @return $this
     */
    public function getDegree($a, $b)
    {
        $this->template = "MATCH (a {guid:{a_guid}}), (b {guid:{b_guid}}), " .
                            "p = shortestPath( a-[*..16]->b ) " .
                            "RETURN length(p)-1";
        $this->values = array(
                            'a_guid'=> (string) $a->guid,
                            'b_guid'=> (string) $b->guid
                            );
        return $this;
    }

    /**
     * Return if mutual
     * @param $a
     * @param $b
     * @return $this;
     */
    public function isMutual($a, $b)
    {
        $this->template = "MATCH (a {guid:{a_guid}}), (b {guid:{b_guid}}), " .
                            "p = shortestPath( a-[]->b ) " .
                            "RETURN length(p)-1";
        $this->values = array(
                            'a_guid'=> (string) $a,
                            'b_guid'=> (string) $b
                            );
        return $this;
    }

    /**
     * Create objects
     */
    public function createObject(\ElggObject $object)
    {
        $this->template = "MERGE (object:$object->subtype { guid: {guid} })";
        $this->values = array('guid'=>(string) $object->guid);
        return $this;
    }

    /**
     * Import bulk users
     */
    public function createBulkObjects(array $objects = array(), $subtype="video")
    {
        foreach ($objects as $object) {
            $exp[] = array(
                        'guid'=> $object->guid,
                        'subtype'=>$object->subtype
                        );
        }
        $this->template = "FOREACH (o IN " . preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($exp))  . " | MERGE(object:$subtype {guid: str(o.guid)}))";
        return $this;
    }

    /**
     * Return suggested content, based on
     */
    public function getSuggestedObjects($user_guid, $subtype = 'video', $skip = 0)
    {
        $this->template = "MATCH (a:User {guid:{user_guid}})-[:UP*..2]-(object:$subtype) " .
                            "WHERE NOT a-[:ACTED]->(object) " .
                            "RETURN object SKIP {skip} LIMIT 16 ";
        $this->values = array(
            'user_guid'=> (string) $user_guid,
             'skip' => (int) $skip
                            );
        return $this;
    }

    /**
     * To be used only when no suggested content is found..
     */
    public function getObjects($user_guid, $subtype='video')
    {
        //error_log("getting $user_guid $subtype");
        $this->template = "MATCH (object:$subtype), (user:User {guid:{user_guid}}) " .
                            "WHERE NOT user-[:ACTED]->(object) " .
                            "RETURN object LIMIT 12";
        $this->values = array(
                            'user_guid'=> (string) $user_guid
                            );
        return $this;
    }

    /**
     * Get trending objects
     */
    public function getTrendingObjects($subtype='video', $skip = 0, $limit = 12)
    {
        $this->template = "MATCH (object:$subtype)-[r:UP]-() RETURN object, count(r) as c ORDER BY c DESC, object.guid SKIP {skip} LIMIT {limit}";
        $this->values = array(
            'skip' => (int) $skip,
            'limit' => (int) $limit
        );
        return $this;
    }

    /**
     * Get trending users
     */
    public function getTrendingUsers($skip = 0, $limit = 12)
    {
        $this->template = "MATCH ()-[r:SUBSCRIBED]->(user:User) RETURN user, count(r) as c ORDER BY c DESC, user.guid SKIP {skip} LIMIT {limit}";
        $this->values = array(
            'skip' => (int) $skip,
            'limit' => (int) $limit
        );
        return $this;
    }

    /**
     * Create a vote on an object
     * @param int $guid
     * @param string $subtype
     * @param int (optional) $user_guid
     * @return $this
     */
    public function createVoteUP($guid, $subtype, $user_guid = null)
    {
        if (!$user_guid) {
            $user_guid = \Minds\Core\Session::getLoggedinUser()->guid;
        }
        //error_log("NEO4j vote up for $guid :: $subtype :: $user_guid");
        $this->template =   "MATCH (user:User {guid: {user_guid}})," .
                            "(object:$subtype {guid: {object_guid}}) " .
                            "MERGE (user)-[:UP]->(object) MERGE (user)-[:ACTED]->(object)";
        $this->values = array(
            'user_guid' => (string) $user_guid,
            'object_guid' => (string) $guid,
            );
        return $this;
    }

    /**
     * Create a down vote on an object
     * @param int $guid
     * @param string $subtype
     * @param int (optional) $user_guid
     * @return $this;
     */
    public function createVoteDOWN($guid, $subtype, $user_guid = null)
    {
        if (!$user_guid) {
            $user_guid = \Minds\Core\Session::getLoggedinUser()->guid;
        }
        //error_log("NEO4j vote down for $guid :: $subtype :: $user_guid");

        $this->template =   "MATCH (user:User {guid: {user_guid}})," .
                            "(object:$subtype {guid: {object_guid}}) " .
                            "MERGE (user)-[:DOWN]->(object) MERGE (user)-[:ACTED]->(object)";
        $this->values = array(
            'user_guid' => (string) $user_guid,
            'object_guid' => (string) $guid,
            );
        return $this;
    }

    /**
     * Return matching guids
     * @param array $guids
     * @param int $user_guid
     * @return $this
     */
    public function getActed($guids, $user_guid = null)
    {
        if (!$user_guid) {
            $user_guid = \Minds\Core\Session::getLoggedinUser()->guid;
        }

        foreach ($guids as $k => $guid) {
            $guids[$k] = (string) $guid;
        }

        $this->template =   "MATCH (user:User {guid: {user_guid}})-[:ACTED]-(items) WHERE items.guid IN {guids} RETURN items";
        $this->values = array(
            'user_guid' => (string) $user_guid,
            'guids' => $guids
            );
        return $this;
    }

    /**
     * Update a node
     * @param Entity $entity
     * @return $this
     */
    public function updateEntity($entity, $properties = array())
    {
        $this->template = "MERGE (entity { guid: {guid}}) SET entity += {properties} RETURN id(entity)";
        $this->values = array(
            'guid'=> (string) $entity->guid,
            'properties'=>$properties
            );
        return $this;
    }

    /**
     * Remove a node
     * @param Entity $entity
     * @return $this
     */
    public function removeEntity($guid)
    {
        $this->template = "MATCH (n { guid: {guid} })-[r]-() DELETE n, r";
        $this->values = array(
            'guid' => (string) $guid
        );
        return $this;
    }

    /**
     * Links a node to a geom layer
     * ** THIS REQUIRES YOU TO HAVE THE NODE ID, NODE ENTIY GUID **
     * @param int $node_id
     * @return $this
     */
    public function linkNodeToGeom($node_id)
    {
        $this->template = ':POST /db/data/index/node/geom
{
    "value" : "dummy",
    "key" : "dummy",
    "uri" : "http://localhost:7474/db/data/node/' . $node_id . '"
}';
        return $this;
    }

    /**
     * Return users via their location
     * @param User/string/int $user (can be an object or guid)
     * @param string (optional) $latlong
     * @param double (optional) $distance - distance to search
     * @return $this
     */
    public function getUserByLocation($user, $latlon = null, $distance = 100.0, $limit = 12, $skip = 0)
    {
        if (!$latlon) {
            $latlon = $user->coordinates;
        }

        if (!$latlon) {
            return false;
        } //should probably throw an exception instead

        $km = $distance * 1.609344;
        $distance =  number_format((float)$km, 2, '.', '');

        $timestamps = Timestamps::span(30, 'day');

        $this->template = "start n = node:geom({filter}) 
            MATCH (u:User {guid:{guid}}) 
            WHERE n.last_active > {active}
            AND NOT u-[:ACTED]->n 
            AND NOT u.guid = n.guid 
            AND (n.hasAvatar = true) 
            RETURN n as fof 
            SKIP {skip} 
            LIMIT {limit}";
        $this->values = array(
            "filter" => "withinDistance:[$latlon,$distance]",
            "guid" => is_object($user) ? (string) $user->guid : (string) $user,
            "active" => (int) $timestamps[15],
            "limit"=> (int) $limit,
            "skip" => (int) $skip
        );
        return $this;
    }
}
