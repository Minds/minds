<?php

namespace Spec\Minds\Core\Media;

use Minds\Core\Data\Call;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Media\Albums;
use Minds\Entities\Album;
use Minds\Entities\Image;
use PhpSpec\ObjectBehavior;

class AlbumsSpec extends ObjectBehavior
{
    /** @var Call */
    protected $db;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    function let(Call $db, EntitiesBuilder $entitiesBuilder)
    {
        $this->beConstructedWith($db, $entitiesBuilder);

        $this->db = $db;
        $this->entitiesBuilder = $entitiesBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Albums::class);
    }

    function it_should_get_all(Album $entity1, Album $entity2)
    {
        $this->entitiesBuilder->get([
            'subtype' => 'album',
            'owner_guid' => '123'
        ])
            ->shouldBeCalled()
            ->willReturn([$entity1, $entity2]);

        $this->getAll('123')
            ->shouldReturn([$entity1, $entity2]);
    }

    function it_should_get_the_children(Image $entity1, Image $entity2)
    {
        $this->db->getRow("object:container:123", [
            'limit' => 12,
            'offset' => ''
        ])
            ->shouldBeCalled()
            ->willReturn(['123' => [time()], '456' => [time()]]);

        $this->entitiesBuilder->get(['guids' => ['123', '456']])
            ->shouldBeCalled()
            ->willReturn([$entity1, $entity2]);


        $this->getChildren('123')->shouldReturn([$entity1, $entity2]);
    }

    function it_should_add_children(Album $album)
    {

        $album->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $album->canEdit()
            ->shouldBeCalled()
            ->willReturn(true);

        $album->addChildren(['123', '456'])
            ->shouldBeCalled();


        $this->addChildren($album, ['123', '456'])->shouldReturn(true);
    }

    function it_should_delete(Album $album)
    {
        $album->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $album->canEdit()
            ->shouldBeCalled()
            ->willReturn(true);

        $album->delete()
            ->shouldBeCalled();

        $this->delete($album);
    }
}
