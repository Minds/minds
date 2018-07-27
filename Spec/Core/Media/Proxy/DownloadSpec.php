<?php

namespace Spec\Minds\Core\Media\Proxy;

use Minds\Core\Config\Config;
use Minds\Core\Media\Proxy\Download;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DownloadSpec extends ObjectBehavior
{

    function let(Config $config)
    {
        $this->beConstructedWith(null, [ 'blacklist-domain' ]);
    }    

    function it_is_initializable()
    {
        $this->shouldHaveType(Download::class);
    }

    function it_should_not_allow_from_private_subnet()
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

    function it_should_not_allow_from_blacklisted_domain(Config $config)
    {
        $this->beConstructedWith(null, [
            'internal-hostname'
        ]);

        $this->setSrc('http://internal-hostname');
        $this->isValidSrc()->shouldBe(false);
    }

}
