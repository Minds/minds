<?php

namespace Spec\Minds\Core\Media;

use Minds\Core\Data\Call;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Media\Feeds;
use Minds\Entities\Image;
use PhpSpec\ObjectBehavior;

class FeedsSpec extends ObjectBehavior
{
    /** @var Call */
    protected $indexDb;
    /** @var Call */
    protected $entityDb;
    /** @var EventsDispatcher */
    protected $dispatcher;

    function let(Call $indexDb, Call $entityDb, EventsDispatcher $dispatcher)
    {
        $this->beConstructedWith($indexDb, $entityDb, $dispatcher);

        $this->indexDb = $indexDb;
        $this->entityDb = $entityDb;
        $this->dispatcher = $dispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Feeds::class);
    }

    function it_should_update_activities(Image $entity)
    {
        $entity->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $entity->get('title')
            ->shouldBeCalled()
            ->willReturn('title');

        $this->indexDb->getRow('activity:entitylink:123')
            ->shouldBeCalled()
            ->willReturn(['456', '789']);

        $this->entityDb->insert('456', ['message' => $entity->title]);
        $this->entityDb->insert('789', ['message' => $entity->title]);

        $this->setEntity($entity);
        $this->updateActivities()->shouldReturn(true);
    }

    function it_should_dispatch(Image $entity)
    {
        $entity->get('title')
            ->shouldBeCalled()
            ->willReturn('title');

        $entity->getIconUrl()
            ->shouldBeCalled()
            ->willReturn('https://icon.url');

        $entity->getUrl()
            ->shouldBeCalled()
            ->willReturn('https://url');

        $this->dispatcher->trigger('social', 'dispatch', [
            'entity' => $entity,
            'services' => [
                'facebook' => true,
                'twitter' => false
            ],
            'data' => [
                'message' => $entity->title,
                'thumbnail_src' => $entity->getIconUrl(),
                'perma_url' => $entity->getURL()
            ]
        ]);

        $this->setEntity($entity);
        $this->dispatch(['facebook' => true])->shouldReturn(true);
    }
}
