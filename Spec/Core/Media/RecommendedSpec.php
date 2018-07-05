<?php

namespace Spec\Minds\Core\Media;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Media\Recommended;
use Minds\Entities\Video;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class RecommendedSpec extends ObjectBehavior
{
    /** @var Client */
    protected $db;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    function let(Client $db, EntitiesBuilder $entitiesBuilder)
    {
        $this->beConstructedWith($db, $entitiesBuilder);
        $this->db = $db;
        $this->entitiesBuilder = $entitiesBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Recommended::class);
    }

    function it_should_get_entities_by_owner(Video $entity1, Video $entity2)
    {
        $this->entitiesBuilder->get([
            'owner_guids' => ['123'],
            'type' => 'object',
            'subtype' => 'video',
            'limit' => 500
        ])
            ->shouldBeCalled()
            ->willReturn([$entity1, $entity2]);

        $this->getByOwner(500, '123', 'video')->shouldReturn([$entity1, $entity2]);
    }

    function it_should_get_featured_entities(Video $entity1, Video $entity2)
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT * FROM entities_by_time WHERE key = ? LIMIT ?"
                && $built['values'][0] === 'object:video:featured'
                && $built['values'][1] === 500;
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([['value' => '123'], ['value' => '456']], ''));

        $this->entitiesBuilder->get(['guids' => ['123', '456']])
            ->shouldBeCalled()
            ->willReturn([$entity1, $entity2]);

        $this->getFeatured(500, 'video')->shouldReturn([$entity1, $entity2]);
    }
}
