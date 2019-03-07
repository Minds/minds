<?php

namespace Spec\Minds\Core\Media\Proxy;

use Minds\Core\Config\Config;
use Minds\Core\Media\Proxy\Download;
use PhpSpec\ObjectBehavior;
use Minds\Core\Http\Curl\Client;
use Minds\Core\Http\Curl\CurlWrapper;
use Prophecy\Argument;

class DownloadSpec extends ObjectBehavior
{
    private $config;
    private $curl;

    public function let(CurlWrapper $curl, Config $config)
    {
        $client = new Client($curl->getWrappedObject());
        $this->beConstructedWith($client, ['blacklist-domain']);
        $this->curl = $curl;
        $this->config = $config;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Download::class);
    }

    public function it_should_not_allow_from_private_subnet()
    {
        $this->setSrc('http://10.56.0.1');
        $this->isValidSrc()->shouldBe(false);

        $this->setSrc('https://10.56.10.1');
        $this->isValidSrc()->shouldBe(false);

        $this->setSrc('http://192.168.0.1');
        $this->isValidSrc()->shouldBe(false);

        $this->setSrc('https://192.168.50.1');
        $this->isValidSrc()->shouldBe(false);

        $this->setSrc('http://172.16.0.1');
        $this->isValidSrc()->shouldBe(false);

        $this->setSrc('https://172.16.1.3');
        $this->isValidSrc()->shouldBe(false);

        $this->setSrc('https://minds.com');
        $this->isValidSrc()->shouldBe(true);
    }

    public function it_should_not_allow_from_blacklisted_domain(Config $config)
    {
        $this->beConstructedWith(null, [
            'internal-hostname',
        ]);

        $this->setSrc('http://internal-hostname');
        $this->isValidSrc()->shouldBe(false);
    }

    public function it_should_download()
    {
        $testSrc = 'http://test/test.jpg';
        $this->setSrc($testSrc);
        $this->curl->setOpt(CURLOPT_URL, $testSrc)->shouldBeCalled();
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, 1)->shouldBeCalled();
        $this->curl->setOpt(CURLOPT_HTTPGET, true)->shouldBeCalled();
        $this->curl->setOpt(CURLOPT_HTTPHEADER, Argument::any())->shouldBeCalled();
        $this->curl->setOptArray(Argument::any())->shouldBeCalled();
        $this->curl->execute()->shouldBeCalled()->willReturn($this->mockReturnedImage());
        $this->curl->getErrorNumber()->shouldBeCalled();
        $this->curl->getError()->shouldBeCalled();
        $this->downloadBinaryString()->shouldEqual($this->mockReturnedImage());
    }

    public function it_should_require_a_src()
    {
        $this->shouldThrow('\Exception')->during('downloadBinaryString');
    }

    public function it_should_require_a_valid_src()
    {
        $this->setSrc('hot garbage');
        $this->shouldThrow('\Exception')->during('downloadBinaryString');
    }

    public function it_should_limit()
    {
        $testSrc = 'http://test/test.jpg';
        $this->setLimitKb(1);
        $this->setSrc($testSrc);
        $this->curl->setOpt(CURLOPT_URL, $testSrc)->shouldBeCalled();
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, 1)->shouldBeCalled();
        $this->curl->setOpt(CURLOPT_HTTPGET, true)->shouldBeCalled();
        $this->curl->setLimit(1)->shouldBeCalled();
        $this->curl->setOpt(CURLOPT_HTTPHEADER, Argument::any())->shouldBeCalled();
        $this->curl->setOptArray(Argument::any())->shouldBeCalled();
        $this->curl->execute()->shouldBeCalled()->willReturn($this->mockReturnedImage());
        $this->curl->getErrorNumber()->shouldBeCalled();
        $this->curl->getError()->shouldBeCalled();
        $this->downloadBinaryString()->shouldEqual($this->mockReturnedImage());
    }

    private function mockReturnedImage()
    {
        ob_start();
        $img = imagecreatetruecolor(120, 20);
        imagepng($img);
        $bufferedData = ob_get_contents();
        ob_end_clean();

        return $bufferedData;
    }
}
