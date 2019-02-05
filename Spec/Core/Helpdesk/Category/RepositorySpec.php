<?php

namespace Spec\Minds\Core\Helpdesk\Category;

use Cassandra\Uuid;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Helpdesk\Category\Category;
use Minds\Core\Helpdesk\Category\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    private $client;

    function let(Client $client)
    {
        $this->client = $client;

        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_by_uuid()
    {
        $this->client->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(new Rows([
                [
                    'uuid' => new Uuid('f990a87d-1255-42e5-a78e-4a2256569e8a'),
                    'title' => 'title',
                    'parent' => null,
                    'branch' => 'f990a87d-1255-42e5-a78e-4a2256569e8a',
                ]
            ], ''));

        $this->get('f990a87d-1255-42e5-a78e-4a2256569e8a')
            ->shouldBeAnInstanceOf(Category::class);
    }

    function it_should_add(Category $category)
    {
        $category->getUuid()
            ->shouldBeCalled()
            ->willReturn(null);

        $category->getParentUuid()
            ->shouldBeCalled()
            ->willReturn(null);

        $category->getTitle()
            ->shouldBeCalled()
            ->willReturn('title');

        $this->client->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($category)->shouldBeString();
    }
}
