<?php
/**
 * Abuse Guard Recover
 */
namespace Minds\Core\Security\AbuseGuard;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers;

class Recover
{

    private $accused;

    public function __construct($client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
    }

    public function setAccused($accused)
    {
        $this->accused = $accused;
        return $this;
    }

    public function recover()
    {
        $user = $this->accused->getUser();
        foreach ($this->getComments() as $comment) {
            if ($comment->guid) {
                $comment->removeFromIndexes();
            }
        }

        foreach($this->getDownVotes() as $post) {
            if ($post) {
                Helpers\Counters::increment($post->guid, "thumbs:down", -1);
            }
        }

        return true;
    }

    private function getComments()
    {
        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'size' => 1000,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'term' => [ 
                                    'entity_type.keyword' => 'comment'
                                ],
                                'term' => [
                                    'user_guid.keyword' => $this->accused->getUser()->guid
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $comments = [];
        if ($result) {
            foreach ($result['hits']['hits'] as $row) {
                if (isset($row['_source']['comment_guid'])) {
                    $comments[] = new Entities\Comment($row['_source']['comment_guid']);
                }
            }
        }

        return $comments;
    }

    private function getDownVotes()
    {
        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'size' => 1000,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'term' => [ 
                                    'type.keyword' => 'vote:down'
                                ]
                            ],
                            [
                                'term' => [ 
                                    'user_guid.keyword' => $this->accused->getUser()->guid
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);
        
        $posts= [];
        if ($result) {
            foreach ($result['hits']['hits'] as $row) {
                if (isset($row['_source']['entity_guid'])) {
                    $posts[] = Entities\Factory::build($row['_source']['entity_guid']);
                }
            }
        }

        return $posts;
    }

}
