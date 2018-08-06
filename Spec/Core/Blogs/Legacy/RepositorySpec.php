<?php

namespace Spec\Minds\Core\Blogs\Legacy;

use Minds\Common\Repository\Response;
use Minds\Core\Blogs\Blog;
use Minds\Core\Blogs\Legacy;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Security\ACL;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\FutureRow;
use Spec\Minds\Mocks\Cassandra\Rows;
use Minds\Core\Feeds\Legacy\Repository as FeedsRepository;
use Minds\Core\Feeds\FeedItem;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $cql;

    /** @var Legacy\Entity */
    protected $entity;

    /** @var FeedsRepository */
    protected $feedsRepo;

    /** @var ACL */
    protected $acl;

    function let(
        Client $cql,
        Legacy\Entity $legacyEntity,
        FeedsRepository $feedsRepo,
        ACL $acl
    ) {
        $this->beConstructedWith($cql, $legacyEntity, $feedsRepo, $acl);

        $this->cql = $cql;
        $this->entity = $legacyEntity;
        $this->feedsRepo = $feedsRepo;
        $this->acl = $acl;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blogs\Legacy\Repository');
    }

    function it_should_get_list()
    {
        $ebtResponse = new Rows([
            [ 'column1' => '5000' ],
            [ 'column1' => '5001' ],
        ], '');

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();

            return (
                stripos($query['string'], 'select * from entities') === 0 &&
                in_array($query['values'][0], [ '5000', '5001' ])
            );
        }), Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(new FutureRow([]));

        $response = new Response();
        $response[] = (new FeedItem())
            ->setType('foo')
            ->setSubtype('bar')
            ->setContainerGuid(0)
            ->setFeed('all')
            ->setGuid(5000);
        $response[] = (new FeedItem())
            ->setType('foo')
            ->setSubtype('bar')
            ->setContainerGuid(0)
            ->setFeed('all')
            ->setGuid(5001);

        $this->feedsRepo->getList(Argument::any())
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->getList([ 'all' => true ])
            ->shouldReturnAnInstanceOf(Response::class);
    }

    /*function it_should_throw_if_no_index_during_get_list()
    {
        $this
            ->shouldThrow(new \Exception('Missing index constraint'))
            ->duringGetList([]);
    }*/

    function it_should_get(Blog $blog)
    {
        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'select * from') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [ 'key' => 5000, 'column1' => 'title', 'value' => 'phpspec' ],
                [ 'key' => 5000, 'column1' => 'description', 'value' => 'body' ],
            ]);

        $this->entity->build([
            'guid' => 5000,
            'title' => 'phpspec',
            'description' => 'body',
        ])
            ->shouldBeCalled()
            ->willReturn($blog);

        $blog->setEphemeral(false)
            ->shouldBeCalled()
            ->willReturn($blog);

        $this
            ->get(5000)
            ->shouldReturn($blog);
    }

    function it_should_add(Blog $blog)
    {
        $blog->getGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $blog->getTitle()
            ->shouldBeCalled()
            ->willReturn('phpspec');

        $blog->getBody()
            ->shouldBeCalled()
            ->willReturn('description');

        $this->cql->batchRequest(Argument::type('array'), Argument::cetera())
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->add($blog, [ 'title', 'body' ])
            ->shouldReturn(5000);
    }

    // update() is a wrapper for add()

    function it_should_delete(Blog $blog)
    {
        $blog->getGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();

            return stripos($query['string'], 'delete from') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->delete($blog)
            ->shouldReturn(true);
    }

    function it_should_return_false_on_exception_during_delete(Blog $blog)
    {
        $blog->getGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->cql->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this
            ->delete($blog)
            ->shouldReturn(false);
    }
}
