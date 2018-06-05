<?php

namespace Spec\Minds\Core\Comments;

use Minds\Common\Repository\Response;
use Minds\Core\Comments\Comment;
use Minds\Core\Comments\Legacy\Repository as LegacyRepository;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $cql;

    /** @var LegacyRepository */
    protected $legacyRepository;

    function let(
        Client $cql,
        LegacyRepository $legacyRepository
    ) {
        $this->beConstructedWith(
            $cql,
            $legacyRepository
        );

        $this->cql = $cql;
        $this->legacyRepository = $legacyRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Repository');
    }

    function it_should_get_list()
    {
        $row = [
            'entity_guid' => null,
            'parent_guid' => null,
            'guid' => null,
            'has_children' => null,
            'owner_guid' => null,
            'container_guid' => null,
            'time_created' => null,
            'time_updated' => null,
            'access_id' => null,
            'body' => null,
            'attachments' => null,
            'mature' => null,
            'edited' => null,
            'spam' => null,
            'deleted' => null,
            'owner_obj' => null,
            'votes_up' => null,
            'votes_down' => null,
            'flags' => null,
        ];

        $rows = new Rows([ $row, $row ], 'phpspec');

        $this->legacyRepository->isLegacy(5000)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->legacyRepository->getList(Argument::cetera())
            ->shouldNotBeCalled();

        $this->cql->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getList([
            'entity_guid' => 5000
        ]);

        $return->shouldBeAnInstanceOf(Response::class);
        expect($return->getWrappedObject()->toArray())->shouldBeAnArrayOf(2, Comment::class);
        expect($return->getWrappedObject()->getPagingToken())->shouldBe(base64_encode('phpspec'));
    }

    function it_should_get_legacy_list()
    {
        $this->legacyRepository->isLegacy(5000)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->legacyRepository->getList(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn([]);

        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->getList([
                'entity_guid' => 5000
            ])
            ->shouldReturn([]);
    }

    function it_should_get(
        Comment $comment
    )
    {
        $row = [
            'entity_guid' => null,
            'parent_guid' => null,
            'guid' => null,
            'has_children' => null,
            'owner_guid' => null,
            'container_guid' => null,
            'time_created' => null,
            'time_updated' => null,
            'access_id' => null,
            'body' => null,
            'attachments' => null,
            'mature' => null,
            'edited' => null,
            'spam' => null,
            'deleted' => null,
            'owner_obj' => null,
            'votes_up' => null,
            'votes_down' => null,
            'flags' => null,
        ];

        $rows = new Rows([ $row, $row ], 'phpspec');

        $this->legacyRepository->isLegacy(5000)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->legacyRepository->getList(Argument::cetera())
            ->shouldNotBeCalled();

        $this->cql->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this
            ->get(5000, 0, 6000)
            ->shouldReturnAnInstanceOf(Comment::class);
    }

    function it_should_get_legacy(
        Comment $comment
    )
    {
        $this->legacyRepository->isLegacy(5000)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->legacyRepository->getList(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn([ $comment ]);

        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->get(5000, 0, 6000)
            ->shouldReturn($comment);
    }

    function it_should_get_null()
    {
        $this->legacyRepository->getList(Argument::cetera())
            ->shouldNotBeCalled();

        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->get(0, 0, 0)
            ->shouldReturn(null);
    }

    function it_should_count()
    {
        $this->legacyRepository->isLegacy(5000)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->legacyRepository->count(5000)
            ->shouldNotBeCalled();

        $this->cql->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willReturn([[ 'count' => 3 ]]);

        $this
            ->count(5000)
            ->shouldReturn(3);
    }

    function it_should_return_zero_if_no_entity_during_count()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->count(0)
            ->shouldReturn(0);
    }

    function it_should_count_legacy()
    {
        $this->legacyRepository->isLegacy(5000)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->legacyRepository->count(5000)
            ->willReturn(3);

        $this
            ->count(5000)
            ->shouldReturn(3);
    }

    function it_should_return_zero_if_parent_guid_during_count_legacy()
    {
        $this->legacyRepository->isLegacy(5000)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->legacyRepository->count(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->count(5000, 4999)
            ->shouldReturn(0);
    }

    function it_should_return_zero_if_no_result_during_count()
    {
        $this->legacyRepository->isLegacy(5000)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->legacyRepository->count(5000)
            ->shouldNotBeCalled();

        $this->cql->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->count(5000)
            ->shouldReturn(0);
    }

    function it_should_add(
        Comment $comment
    )
    {
        $comment->getHasChildren()
            ->shouldBeCalled()
            ->willReturn(false);

        $comment->getOwnerGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $comment->getContainerGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $comment->getTimeCreated()
            ->shouldBeCalled()
            ->willReturn(123123);

        $comment->getTimeUpdated()
            ->shouldBeCalled()
            ->willReturn(123145);

        $comment->getAccessId()
            ->shouldBeCalled()
            ->willReturn(2);

        $comment->getBody()
            ->shouldBeCalled()
            ->willReturn('hello');

        $comment->getAttachments()
            ->shouldBeCalled()
            ->willReturn([]);

        $comment->isMature()
            ->shouldBeCalled()
            ->willReturn(false);

        $comment->isEdited()
            ->shouldBeCalled()
            ->willReturn(false);

        $comment->isSpam()
            ->shouldBeCalled()
            ->willReturn(false);

        $comment->isDeleted()
            ->shouldBeCalled()
            ->willReturn(false);

        $comment->getOwnerObj()
            ->shouldBeCalled()
            ->willReturn([]);

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $comment->getParentGuid()
            ->shouldBeCalled()
            ->willReturn(0);

        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $this->cql->request(Argument::type(Custom::class));

        $this
            ->add($comment)
            ->shouldReturn(true);
    }

    function it_should_add_with_a_single_attribute(
        Comment $comment
    )
    {
        $comment->getBody()
            ->shouldBeCalled()
            ->willReturn('hello');

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $comment->getParentGuid()
            ->shouldBeCalled()
            ->willReturn(0);

        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $this->cql->request(Argument::type(Custom::class));

        $this
            ->add($comment, [ 'body' ])
            ->shouldReturn(true);
    }

    // Update is not tested because it's just a wrapper for add()

    function it_should_delete(
        Comment $comment
    )
    {
        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $comment->getParentGuid()
            ->shouldBeCalled()
            ->willReturn(0);

        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn(6000);

        $comment->setEphemeral(true)
            ->shouldBeCalled()
            ->willReturn($comment);

        $this->legacyRepository->isFallbackEnabled()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->legacyRepository->delete($comment)
            ->shouldNotBeCalled();

        $this->cql->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->delete($comment)
            ->shouldReturn(true);
    }

    function it_should_return_false_if_throws_during_delete(
        Comment $comment
    )
    {
        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $comment->getParentGuid()
            ->shouldBeCalled()
            ->willReturn(0);

        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn(6000);

        $this->legacyRepository->isFallbackEnabled()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->legacyRepository->delete($comment)
            ->shouldNotBeCalled();

        $this->cql->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this
            ->delete($comment)
            ->shouldReturn(false);
    }

    function it_should_delete_with_legacy(
        Comment $comment
    )
    {
        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $comment->getParentGuid()
            ->shouldBeCalled()
            ->willReturn(0);

        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn(6000);

        $comment->setEphemeral(true)
            ->shouldBeCalled()
            ->willReturn($comment);

        $this->legacyRepository->isFallbackEnabled()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->legacyRepository->delete($comment)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->delete($comment)
            ->shouldReturn(true);
    }

    function getMatchers()
    {
        $matchers = [];

        $matchers['beAnArrayOf'] = function ($subject, $count, $class) {
            if (!is_array($subject) || ($count !== null && count($subject) !== $count)) {
                throw new FailureException("Subject should be an array of $count elements");
            }

            $validTypes = true;

            foreach ($subject as $element) {
                if (!($element instanceof $class)) {
                    $validTypes = false;
                    break;
                }
            }

            if (!$validTypes) {
                throw new FailureException("Subject should be an array of {$class}");
            }

            return true;
        };

        return $matchers;
    }
}
