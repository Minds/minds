<?php

namespace Spec\Minds\Core\Blockchain\Services;

use Minds\Core\Blockchain\Services\CoinMarketCap;
use Minds\Core\Data\cache\abstractCacher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Http\Curl\Json\Client;

class CoinMarketCapSpec extends ObjectBehavior
{
    /** @var Client */
    private $http;
    /** @var abstractCacher */
    private $cacher;

    function let(Client $http, abstractCacher $cacher)
    {
        $this->http = $http;
        $this->cacher = $cacher;

        $this->beConstructedWith($http, $cacher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoinMarketCap::class);
    }

    function it_should_get_the_cap()
    {
        $this->get()->shouldReturn(0.25);
    }

    // TODO: ****++++ UNCOMMENT THE FOLLOWING TESTS WHEN THE HARDCODE GETS REMOVED FROM get() ****++++
    //function it_should_get_the_cap_from_cache()
    //{
    //    $this->cacher->get('blockchain:cmc:rate:currency')
    //        ->shouldBeCalled()
    //        ->willReturn(serialize(10));
    //
    //    $this->setCurrency('currency');
    //    $this->get()->shouldReturn(10);
    //}
    //
    //function it_should_get_the_cap_from_endpoint()
    //{
    //    $this->cacher->get('blockchain:cmc:rate:currency')
    //        ->shouldBeCalled()
    //        ->willReturn(null);
    //
    //    $this->http->get('https://api.coinmarketcap.com/v1/ticker/currency',
    //        ['curl' => ['CURLOPT_FOLLOWLOCATION' => true]])
    //        ->shouldBeCalled()
    //        ->willReturn([['price_usd' => 10]]);
    //
    //        $this->cacher->set('blockchain:cmc:rate:currency', serialize(10.0), 15 * 60)
    //            ->shouldBeCalled();
    //
    //    $this->setCurrency('currency');
    //    $this->get()->shouldReturn(10.0);
    //}
    //
    //function it_should_fail_to_get_if_theres_no_currency_set() {
    //    $this->shouldThrow(new \Exception('Currency is required'))->during('get');
    //}
    //
    //function it_should_fail_to_get_if_theres_an_invalid_response_from_the_endpoint() {
    //
    //    $this->cacher->get('blockchain:cmc:rate:currency')
    //        ->shouldBeCalled()
    //        ->willReturn(null);
    //
    //    $this->http->get('https://api.coinmarketcap.com/v1/ticker/currency',
    //        ['curl' => ['CURLOPT_FOLLOWLOCATION' => true]])
    //        ->shouldBeCalled()
    //        ->willReturn(null);
    //
    //    $this->setCurrency('currency');
    //    $this->shouldThrow(new \Exception('Invalid CoinMarketCap response'))->during('get');
    //}
}
