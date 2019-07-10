<?php

namespace Spec\Minds\Core\Media\Services;

use Minds\Core\Media\Services\FFMpeg;
use FFMpeg\FFMpeg as FFMpegClient;
use FFMpeg\FFProbe as FFProbeClient;
use Minds\Core\Queue\Interfaces\QueueClient;
use Psr\Http\Message\RequestInterface;
use Aws\S3\S3Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FFMpegSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FFMpeg::class);
    }

    function it_should_get_a_presigned_urn(
        QueueClient $queue,
        FFMpegClient $ffmpeg,
        FFProbeClient $ffprobe,
        S3Client $s3,
        \Aws\CommandInterface $cmd,
        RequestInterface $request
    )
    {
        $this->beConstructedWith($queue, $ffmpeg, $ffprobe, $s3);

        $s3->getCommand('PutObject', [
            'Bucket' => 'cinemr',
            'Key' => "/123/source",
        ])
            ->shouldBeCalled()
            ->willReturn($cmd);
        
        $s3->createPresignedRequest(Argument::any(), Argument::any())
            ->willReturn($request);
            
        $request->getUri()
            ->willReturn('aws-signed-url');

        $this->setKey(123);

        $this->getPresignedUrl()
            ->shouldReturn('aws-signed-url');
    }

}
