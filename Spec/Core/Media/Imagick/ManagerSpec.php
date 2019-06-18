<?php

namespace Spec\Minds\Core\Media\Imagick;

use Minds\Core\Media\Imagick\Autorotate;
use Minds\Core\Media\Imagick\Resize;
use Minds\Core\Media\Imagick\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Autorotate */
    private $autorotate;

    /** @var Resize */
    private $resize;


    function let(Autorotate $autorotate, Resize $resize)
    {
        $this->autorotate = $autorotate;
        $this->resize = $resize;

        $this->beConstructedWith($autorotate, $resize);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_autorotate_the_image()
    {
        $this->autorotate->setImage(Argument::any())
            ->shouldBeCalled();

        $this->autorotate->autorotate()
            ->shouldBeCalled()
            ->willReturn([]);

        $this->autorotate()->shouldReturn($this);
    }

    function it_should_resize_the_image()
    {
        $this->resize->setImage(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->setUpscale(true)
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->setSquare(true)
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->setWidth(10)
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->setHeight(10)
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->resize()
            ->shouldBeCalled();

        $this->resize(10, 10, true, true);
    }

    function it_should_resize_the_image_with_no_upscaling_and_no_squaring_if_unspecified()
    {
        $this->resize->setImage(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->setUpscale(false)
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->setSquare(false)
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->setWidth(10)
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->setHeight(10)
            ->shouldBeCalled()
            ->willReturn($this->resize);

        $this->resize->resize()
            ->shouldBeCalled();

        $this->resize(10, 10, false, false);
    }
}
