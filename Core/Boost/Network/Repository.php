<?php
/**
 * Cassandra Repository for Boost
 */
namespace Minds\Core\Boost\Network;

use Minds\Common\Urn;
use Minds\Common\Repository\Response;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared; 
use Cassandra;

class Repository
{
    /** @var Client $client */
    private $client;

    /** @var Urn $urn */
    private $urn;

    public function __construct($client = null, $urn = null)
    {
        $this->client = $client ?: Di::_()->get('Database\Cassandra\Cql');
        $this->urn = $urn ?: new Urn(); 
    }

    /**
     * Return a list of boosts
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'token' => null
        ], $opts);

        $template = "SELECT * FROM boosts WHERE type = ?";
        $values = [ (string) $opts['type'] ];

        if ($opts['guids']) {
            $collection = Cassandra\Type::collection(Cassandra\Type::varint())->create(...array_values(array_map(function ($guid) {
                return new Cassandra\Varint($guid);
            }, $opts['guids'])));

            $template .= " AND guid IN ?";
            $values[] = $collection;
        }

        $query = new Prepared\Custom();
        $query->query($template, $values);
        
        $query->setOpts([
            'page_size' => (int) $opts['limit'],
            'paging_state_token' => base64_decode($opt['token'])
        ]);

        $response = new Response();

        try {
            $result = $this->client->request($query);

            foreach ($result as $row) {
                $boost = new Boost(); 
                $data = json_decode($row['data'], true);

                if (!isset($data['schema']) && $data['schema'] != '04-2019') {
                    $data['entity_guid'] = $data['entity']['guid'];
                    $data['owner_guid'] = $data['owner']['guid'];
                    $data['@created'] = $data['time_created'] * 1000;
                    $data['@reviewed'] = $data['state'] === 'accepted' ? ($data['last_updated'] * 1000) : null;
                    $data['@revoked'] = $data['state'] === 'revoked' ? ($data['last_updated'] * 1000) : null;
                    $data['@rejected'] = $data['state'] === 'rejected' ? ($data['last_updated'] * 1000) : null;
                    $data['@completed'] = $data['state'] === 'completed' ? ($data['last_updated'] * 1000) : null;
                }

                if ($data['@created'] < 1055503139000) {
                    $data['@created'] = $data['@created'] * 1000;
                }

                $boost->setGuid((string) $row['guid'])
                    ->setMongoId($data['_id'])
                    ->setEntityGuid($data['entity_guid'])
                    ->setOwnerGuid($data['owner_guid'])
                    ->setType($row['type'])
                    ->setCreatedTimestamp($data['@created'])
                    ->setReviewedTimestamp($data['@reviewed'])
                    ->setRevokedTimestamp($data['@revoked'])
                    ->setRejectedTimestamp($data['@rejected'])
                    ->setCompletedTimestamp($data['@completed'])
                    ->setBid($data['bid'])
                    ->setBidType($data['bidType'])
                    ->setImpressions($data['impressions'])
                    ->setTransactionId($data['transactionId'])
                    ->setPriority($data['priority'])
                    ->setRating($data['rating'])
                    ->setTags($data['tags'])
                    ->setNsfw($data['nsfw'])
                    ->setRejectReason($data['rejection_reason'])
                    ->setChecksum($data['checksum']); 
                
                $response[] = $boost;
            }

            $response->setPagingToken(base64_encode($result->pagingStateToken()));
        } catch (\Exception $e) {
            // TODO: Log or warning
        }

        return $response;
    }

    /**
     * Return a single boost via urn
     * @param string $urn
     * @return Boost
     */
    public function get($urn)
    {
        list($type, $guid) = explode(':', $this->urn->setUrn($urn)->getNss(), 2);
        return $this->getList([
            'type' => $type,
            'guids' => [ $guid ],
        ])[0];
    }

    /**
     * Add a boost
     * @param Boost $boost
     * @return bool
     */
    public function add($boost)
    {
        if (!$boost->getType()) {
            throw new \Exception('Type is required');
        }

        if (!$boost->getGuid()) {
            throw new \Exception('GUID is required');
        }

        if (!$boost->getOwnerGuid()) {
            throw new \Exception('Owner is required');
        }

        $template = "INSERT INTO boosts
            (type, guid, owner_guid, destination_guid, mongo_id, state, data)
            VALUES
            (?, ?, ?, ?, ?, ?, ?)
        ";

        $data = [
            'guid' => $boost->getGuid(),
            'schema' => '04-2019',
            '_id' => $boost->getMongoId(), //TODO: remove once on production
            'entity_guid' => $boost->getEntityGuid(),
            'entity' => $boost->getEntity() ? $boost->getEntity()->export() : null, //TODO: remove once on production
            'bid' => $boost->getBid(),
            'impressions' => $boost->getImpressions(),
            //'bidType' => $boost->getBidType(),
            'bidType' => in_array($boost->getBidType(), [ 'onchain', 'offchain' ]) ? 'tokens' : $boost->getBidType(), //TODO: remove once on production
            'owner_guid' => $boost->getOwnerGuid(),
            'owner' => $boost->getOwner() ? $boost->getOwner()->export() : null, //TODO: remove once on production
            '@created' => $boost->getCreatedTimestamp(),
            'time_created' => $boost->getCreatedTimestamp(), //TODO: remove once on production
            'last_updated' => time(), //TODO: remove once on production
            '@reviewed' => $boost->getReviewedTimestamp(),
            '@rejected' => $boost->getRejectedTimestamp(),
            '@revoked' => $boost->getRevokedTimestamp(),
            '@completed' => $boost->getCompletedTimestamp(),
            'transactionId' => $boost->getTransactionId(),
            'type' => $boost->getType(),
            'handler' => $boost->getType(), //TODO: remove once on production
            'state' => $boost->getState(), //TODO: remove once on production
            'priority' => $boost->isPriority(),
            'rating' => $boost->getRating(),
            'tags' => $boost->getTags(),
            'nsfw' => $boost->getNsfw(),
            'rejection_reason'=> $boost->getRejectReason(),
            'checksum' => $boost->getChecksum(),
        ];

        $values = [
            (string) $boost->getType(),
            new Cassandra\Varint($boost->getGuid()),
            new Cassandra\Varint($boost->getOwnerGuid()),
            null,
            (string) $boost->getMongoId(),
            (string) $boost->getState(),
            json_encode($data)
        ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        try {
            $success = $this->client->request($query);
        } catch (\Exception $e) {
            return false;
        }

        return $success;
    }

    /**
     * Update a boost
     * @param Boost $boost
     * @return bool
     */
    public function update($boost, $fields = [])
    {
        return $this->add($boost);
    }

    /**
     * void
     */
    public function delete($boost)
    {
    }

}

