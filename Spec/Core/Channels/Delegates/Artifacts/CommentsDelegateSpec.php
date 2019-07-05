<?php

namespace Spec\Minds\Core\Channels\Delegates\Artifacts;

use Minds\Core\Channels\Delegates\Artifacts\CommentsDelegate;
use Minds\Core\Channels\Snapshots\Repository;
use Minds\Core\Channels\Snapshots\Snapshot;
use Minds\Core\Comments\Comment;
use Minds\Core\Comments\Manager as CommentManager;
use Minds\Core\Data\ElasticSearch\Client as ElasticSearchClient;
use Minds\Core\Data\ElasticSearch\Prepared\Search as PreparedSearch;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommentsDelegateSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var ElasticSearchClient */
    protected $elasticsearch;

    /** @var CommentManager */
    protected $commentManager;

    function let(
        Repository $repository,
        ElasticSearchClient $elasticsearch,
        CommentManager $commentManager
    )
    {
        $this->beConstructedWith($repository, $elasticsearch, $commentManager);
        $this->repository = $repository;
        $this->elasticsearch = $elasticsearch;
        $this->commentManager = $commentManager;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommentsDelegate::class);
    }

    function it_should_snapshot(
        Comment $commentMock
    )
    {
        $this->elasticsearch->request(Argument::that(function (PreparedSearch $search) {
            $query = $search->build();


            return $query['index'] === 'minds-metrics-*';
        }))
            ->shouldBeCalled()
            ->willReturn(
                [
                    'aggregations' => [
                        'comment_luids' => [
                            'buckets' => [
                                ['key' => 'a0000001'],
                                ['key' => 'a0000002'],
                            ],
                        ],
                    ],
                ]
            );

        $this->commentManager->getByLuid('a0000001')
            ->shouldBeCalled()
            ->willReturn($commentMock);

        $this->commentManager->getByLuid('a0000002')
            ->shouldBeCalled()
            ->willReturn($commentMock);

        $this->repository->add(Argument::that(function (Snapshot $snapshot) use ($commentMock) {
            return $snapshot->getJsonData() === ['comment' => serialize($commentMock->getWrappedObject())];
        }))
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $this
            ->snapshot(1000)
            ->shouldReturn(true);
    }

    function it_should_restore(
        Snapshot $snapshotMock,
        Comment $commentMock
    )
    {
        $this->repository->getList([
            'user_guid' => 1000,
            'type' => 'comments',
        ])
            ->shouldBeCalled()
            ->willReturn([
                $snapshotMock,
                $snapshotMock,
            ]);

        $snapshotMock->getJsonData()
            ->shouldBeCalledTimes(2)
            ->willReturn(['comment' => serialize($commentMock->getWrappedObject())]);

        $this->commentManager->restore(Argument::type(Comment::class))
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $this
            ->restore(1000)
            ->shouldReturn(true);
    }

    function it_should_hide(
        Comment $commentMock
    )
    {
        $this->elasticsearch->request(Argument::that(function (PreparedSearch $search) {
            $query = $search->build();


            return $query['index'] === 'minds-metrics-*';
        }))
            ->shouldBeCalled()
            ->willReturn(
                [
                    'aggregations' => [
                        'comment_luids' => [
                            'buckets' => [
                                ['key' => 'a0000001'],
                                ['key' => 'a0000002'],
                            ],
                        ],
                    ],
                ]
            );

        $this->commentManager->getByLuid('a0000001')
            ->shouldBeCalled()
            ->willReturn($commentMock);

        $this->commentManager->getByLuid('a0000002')
            ->shouldBeCalled()
            ->willReturn($commentMock);

        $this->commentManager->delete($commentMock, [ 'force' => true ])
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $this
            ->hide(1000)
            ->shouldReturn(true);
    }

    function it_should_delete(
        Comment $commentMock
    )
    {
        $this->elasticsearch->request(Argument::that(function (PreparedSearch $search) {
            $query = $search->build();


            return $query['index'] === 'minds-metrics-*';
        }))
            ->shouldBeCalled()
            ->willReturn(
                [
                    'aggregations' => [
                        'comment_luids' => [
                            'buckets' => [
                                ['key' => 'a0000001'],
                                ['key' => 'a0000002'],
                            ],
                        ],
                    ],
                ]
            );

        $this->commentManager->getByLuid('a0000001')
            ->shouldBeCalled()
            ->willReturn($commentMock);

        $this->commentManager->getByLuid('a0000002')
            ->shouldBeCalled()
            ->willReturn($commentMock);

        $this->commentManager->delete($commentMock, [ 'force' => true ])
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $this
            ->delete(1000)
            ->shouldReturn(true);
    }
}
