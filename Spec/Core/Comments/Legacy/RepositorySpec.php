<?php

namespace Spec\Minds\Core\Comments\Legacy;

use Minds\Common\Repository\Response;
use Minds\Core\Comments\Comment;
use Minds\Core\Comments\Legacy\Entity;
use Minds\Core\Data\Call;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $cql;

    /** @var Call */
    protected $entities;

    /** @var Call */
    protected $indexes;

    /** @var int */
    protected $ltGuid = 100000000000005000;

    /** @var Entity */
    protected $legacyEntity;

    /** @var bool */
    protected $fallbackEnabled = false;

    public function let(
        Client $cql,
        Call $entities,
        Call $indexes,
        Entity $legacyEntity
    )
    {
        $this->beConstructedWith(
            $cql,
            $entities,
            $indexes,
            $this->ltGuid,
            $legacyEntity,
            $this->fallbackEnabled
        );

        $this->cql = $cql;
        $this->entities = $entities;
        $this->indexes = $indexes;
        $this->legacyEntity = $legacyEntity;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Legacy\Repository');
    }

    function it_should_check_if_is_legacy()
    {
        $this
            ->isLegacy($this->ltGuid - 1)
            ->shouldReturn(true);

        $this
            ->isLegacy($this->ltGuid)
            ->shouldReturn(false);

        $this
            ->isLegacy($this->ltGuid + 1)
            ->shouldReturn(false);
    }

    function it_should_check_if_legacy_is_disabled()
    {
        $this->beConstructedWith(
            $this->cql,
            $this->entities,
            $this->indexes,
            null,
            $this->legacyEntity
        );

        $this
            ->isLegacy(0)
            ->shouldReturn(false);

        $this
            ->isLegacy($this->ltGuid)
            ->shouldReturn(false);

        $this
            ->isLegacy($this->ltGuid + 1)
            ->shouldReturn(false);
    }

    function it_should_get_list(
        Comment $comment1,
        Comment $comment2
    )
    {
        $comment2->getGuid()
            ->shouldBeCalled()
            ->willReturn(6001);

        $this->indexes->getRow('comments:5000', Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn([ 6000 => 6000, 6001 => 6001 ]);

        $this->entities->getRows([ 6000, 6001 ])
            ->shouldBeCalled()
            ->willReturn([ 6000 => [], 6001 => [] ]);

        $this->legacyEntity->build([ 'guid' => 6000 ])
            ->shouldBeCalled()
            ->willReturn($comment1);

        $this->legacyEntity->build([ 'guid' => 6001 ])
            ->shouldBeCalled()
            ->willReturn($comment2);

        $return = $this->getList([
            'entity_guid' => 5000
        ]);

        $return
            ->shouldBeAnInstanceOf(Response::class);

        expect($return->getWrappedObject()->toArray())
            ->toBe([ $comment1, $comment2 ]);

        expect($return->getWrappedObject()->getPagingToken())
            ->toBe(base64_encode(6001));
    }

    function it_should_get_empty_list()
    {
        $this->indexes->getRow('comments:5000', Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn([]);

        $this->entities->getRows(Argument::any())
            ->shouldNotBecalled();

        $return = $this->getList([
            'entity_guid' => 5000
        ]);

        $return
            ->shouldBeAnInstanceOf(Response::class);

        expect($return->getWrappedObject()->toArray())
            ->toBe([]);

    }

    function it_should_count()
    {
        $this->indexes->countRow('comments:5000')
            ->shouldBeCalled()
            ->willReturn('5');

        $this
            ->count(5000)
            ->shouldReturn(5);
    }

    function it_should_add(
        Comment $comment
    )
    {
        $fields = [
            'owner_guid' => 1000,
            'container_guid' => 1000,
            'time_created' => 123123123,
            'time_updated' => 123123124,
            'access_id' => 2,
            'description' => 'phpspec',
            'mature' => false,
            'edited' => true,
            'spam' => false,
            'deleted' => false,
            'owner_obj' => [],
            'parent_guid' => 5000,
            'guid' => 6000,
        ];

        $comment->getOwnerGuid()
            ->shouldBeCalled()
            ->willReturn($fields['owner_guid']);

        $comment->getContainerGuid()
            ->shouldBeCalled()
            ->willReturn($fields['container_guid']);

        $comment->getTimeCreated()
            ->shouldBeCalled()
            ->willReturn($fields['time_created']);

        $comment->getTimeUpdated()
            ->shouldBeCalled()
            ->willReturn($fields['time_updated']);

        $comment->getAccessId()
            ->shouldBeCalled()
            ->willReturn($fields['access_id']);

        $comment->getBody()
            ->shouldBeCalled()
            ->willReturn($fields['description']);

        $comment->getAttachments()
            ->shouldBeCalled()
            ->willReturn([]);

        $comment->isMature()
            ->shouldBeCalled()
            ->willReturn($fields['mature']);

        $comment->isEdited()
            ->shouldBeCalled()
            ->willReturn($fields['edited']);

        $comment->isSpam()
            ->shouldBeCalled()
            ->willReturn($fields['spam']);

        $comment->isDeleted()
            ->shouldBeCalled()
            ->willReturn($fields['deleted']);

        $comment->getOwnerObj()
            ->shouldBeCalled()
            ->willReturn($fields['owner_obj']);

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn($fields['parent_guid']);

        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn($fields['guid']);

        $this->entities->insert('6000', $fields)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->insert('comments:5000', [ '6000' => '6000' ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->insert('migration:comments', [ '5000:6000' => 'added'])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->add($comment, [
                'ownerGuid',
                'containerGuid',
                'timeCreated',
                'timeUpdated',
                'accessId',
                'body',
                'attachments',
                'mature',
                'edited',
                'spam',
                'deleted',
                'ownerObj',
            ], false)
            ->shouldReturn(true);
    }

    function it_should_ignore_if_no_fields_during_add(
        Comment $comment
    )
    {
        $this->entities->insert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->add($comment, [])
            ->shouldReturn(true);
    }

    function it_should_delete(
        Comment $comment
    )
    {
        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn(6000);

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->indexes->insert('migration:comments', [ '5000:6000' => 'deleted' ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->entities->removeRow('6000')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->removeAttributes('comments:5000', [ '6000' ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->delete($comment)
            ->shouldReturn(true);
    }

    function it_should_return_false_if_no_guid_during_delete(
        Comment $comment
    )
    {
        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn(null);

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this
            ->delete($comment)
            ->shouldReturn(false);
    }

    function it_should_return_false_if_no_entity_guid_during_delete(
        Comment $comment
    )
    {
        $comment->getGuid()
            ->shouldBeCalled()
            ->willReturn(6000);

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->delete($comment)
            ->shouldReturn(false);
    }

    function it_should_get_by_guid(
        Comment $comment
    )
    {
        $this->entities->getRow('6000')
            ->shouldBeCalled()
            ->willReturn([ 'type' => 'comment' ]);

        $this->legacyEntity->build([ 'type' => 'comment', 'guid' => '6000' ])
            ->shouldBeCalled()
            ->willReturn($comment);

        $this
            ->getByGuid(6000)
            ->shouldReturn($comment);
    }

    function it_should_catch_exception_and_return_null_during_get_by_guid()
    {
        $this->entities->getRow('6001')
            ->shouldBeCalled()
            ->willReturn([ 'type' => 'comment' ]);

        $this->legacyEntity->build(Argument::cetera())
            ->shouldBeCalled()
            ->willThrow(new \Exception(''));

        $this
            ->getByGuid(6001)
            ->shouldReturn(null);
    }

    function it_should_return_null_if_not_a_comment_row_during_get_by_guid()
    {
        $this->entities->getRow('5000')
            ->shouldBeCalled()
            ->willReturn([ 'type' => 'activity' ]);

        $this->legacyEntity->build(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->getByGuid(5000)
            ->shouldReturn(null);
    }
}
