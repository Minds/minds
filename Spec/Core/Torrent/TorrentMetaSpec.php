<?php

namespace Spec\Minds\Core\Torrent;

use Minds\Core\Torrent\TorrentBuilders\TorrentBuilderInterface;
use Minds\Core\Torrent\TorrentFile;
use Minds\Entities\Video;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TorrentMetaSpec extends ObjectBehavior
{
    protected $torrentBuilder;

    function let(
        TorrentBuilderInterface $torrentBuilder
    ) {
        $this->beConstructedWith($torrentBuilder);

        $this->torrentBuilder = $torrentBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Torrent\TorrentMeta');
    }

    function it_should_get_name()
    {
        $this
            ->setSource('https://minds.test/phpspec/360.mp4')
            ->getName()
            ->shouldReturn('a181d1d8d208e9b1717b2d9a56b7ca1afbe6510a.mp4');
    }

    function it_should_get_torrent(
        Video $video
    )
    {
        $video->get('guid')
            ->willReturn('5000');

        $this->torrentBuilder->setKey('5000')
            ->shouldBeCalled()
            ->willReturn($this->torrentBuilder);

        $this->torrentBuilder->setFile('360.mp4')
            ->shouldBeCalled()
            ->willReturn($this->torrentBuilder);

        $this->torrentBuilder->build()
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->setEntity($video)
            ->setFile('360.mp4')
            ->setSource('https://minds.test/phpspec/360.mp4')
            ->getTorrent()
            ->shouldHaveType(TorrentFile::class);
    }
}
