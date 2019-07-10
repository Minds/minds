<?php

namespace Spec\Minds\Core\Media\ClientUpload;

use Minds\Core\Media\ClientUpload\Manager;
use Minds\Core\Media\ClientUpload\ClientUploadLease;
use Minds\Core\Media\Services\FFMpeg;
use Minds\Core\GuidBuilder;
use Minds\Core\Entities\Actions\Save;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $ffmpeg;
    private $guid;
    private $save;

    function let(FFMpeg $FFMpeg, GuidBuilder $guid, Save $save)
    {
        $this->beConstructedWith($FFMpeg, $guid, $save);
        $this->ffmpeg = $FFMpeg;
        $this->guid = $guid;
        $this->save = $save;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_return_an_upload_lease()
    {
        $this->guid->build()
            ->willReturn(123);

        $this->ffmpeg->setKey(123)
            ->shouldBeCalled();

        $this->ffmpeg->getPresignedUrl()
            ->willReturn('s3-url-here');

        $lease = $this->prepare('video');

        $lease->getMediaType()
            ->shouldBe('video');
        $lease->getGuid()
            ->shouldBe(123);
        $lease->getPresignedUrl()
            ->shouldBe('s3-url-here');
    }

    function it_should_complete_an_upload(ClientUploadLease $lease)
    {
        $lease->getMediaType()
            ->willReturn('video');

        $lease->getGuid()
            ->willReturn(456);
        
        $this->save->setEntity(Argument::that(function ($video) {
            return $video->guid == 456
                && $video->access_id == 0;
        }))
            ->shouldBeCalled()
            ->willReturn($this->save);

        $this->save->save()
            ->shouldBeCalled();

        $this->ffmpeg->setKey(456)
            ->shouldBeCalled();

        $this->ffmpeg->transcode()
            ->shouldBeCalled();

        $this->complete($lease)
            ->shouldReturn(true);
    }
}
