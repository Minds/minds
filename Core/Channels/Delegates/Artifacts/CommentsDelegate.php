<?php
/**
 * CommentsDelegate.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Delegates\Artifacts;

use Minds\Core\Channels\Snapshots\Repository;
use Minds\Core\Channels\Snapshots\Snapshot;
use Minds\Core\Comments\Comment;
use Minds\Core\Comments\Manager as CommentManager;
use Minds\Core\Data\ElasticSearch\Client as ElasticSearchClient;
use Minds\Core\Data\ElasticSearch\Prepared\Search;
use Minds\Core\Di\Di;

class CommentsDelegate implements ArtifactsDelegateInterface
{
    /** @var Repository */
    protected $repository;

    /** @var ElasticSearchClient */
    protected $elasticsearch;

    /** @var CommentManager */
    protected $commentManager;

    /**
     * CommentsDelegate constructor.
     * @param Repository $repository
     * @param ElasticSearchClient $elasticsearch
     * @param CommentManager $commentManager
     */
    public function __construct(
        $repository = null,
        $elasticsearch = null,
        $commentManager = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->elasticsearch = $elasticsearch ?: Di::_()->get('Database\ElasticSearch');
        $this->commentManager = $commentManager ?: new CommentManager();
    }

    /**
     * @param string|int $userGuid
     * @return bool
     * @throws \Exception
     */
    public function snapshot($userGuid)
    {
        foreach ($this->fetchComments($userGuid) as $commentLuid) {
            $comment = $this->commentManager->getByLuid($commentLuid);
            if (!$comment) {
                continue;
            }

            try {
                $snapshot = new Snapshot();
                $snapshot
                    ->setUserGuid($userGuid)
                    ->setType('comments')
                    ->setKey($commentLuid)
                    ->setJsonData([ 'comment' => serialize($comment) ]);

                $this->repository->add($snapshot);
            } catch (\Exception $e) {
                error_log((string) $e);
            }
        }

        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     * @throws \Exception
     */
    public function restore($userGuid)
    {
        /** @var Snapshot $snapshot */
        foreach ($this->repository->getList([
            'user_guid' => $userGuid,
            'type' => 'comments',
        ]) as $snapshot) {
            $jsonData = $snapshot->getJsonData();

            try {
                $comment = unserialize($jsonData['comment']);

                if (!$comment || !($comment instanceof Comment)) {
                    throw new \Exception('Invalid serialized comment');
                }

                $this->commentManager->restore($comment);
            } catch (\Exception $e) {
                error_log((string) $e);
            }
        }

        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     * @throws \Exception
     */
    public function hide($userGuid)
    {
        return $this->delete($userGuid);
    }

    /**
     * @param string|int $userGuid
     * @return bool
     * @throws \Exception
     */
    public function delete($userGuid)
    {
        foreach ($this->fetchComments($userGuid) as $commentLuid) {
            $comment = $this->commentManager->getByLuid($commentLuid);

            if (!$comment) {
                continue;
            }

            $this->commentManager->delete($comment, [
                'force' => true,
            ]);
        }

        return true;
    }

    /**
     * @param string|int $userGuid
     * @return \Generator
     * @throws \Exception
     */
    protected function fetchComments($userGuid)
    {
        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        'term' => [
                            'user_guid.keyword' => $userGuid,
                        ],
                    ],
                ],
            ],
            'aggs' => [
                'comment_luids' => [
                    'terms' => [
                        'field' => 'comment_guid.keyword',
                        'size' => 500000,
                    ]
                ]
            ]
        ];

        $query = [
            'body' => $body,
            'size' => 0,
            'index' => 'minds-metrics-*',
            'type' => 'action',
        ];

        $prepared = new Search();
        $prepared->query($query);

        try {
            $result = $this->elasticsearch->request($prepared);
        } catch (\Exception $e) {
            error_log((string) $e);
            throw $e; // Re-throw
        }

        foreach ($result['aggregations']['comment_luids']['buckets'] as $row) {
            yield $row['key'];
        }
    }
}
