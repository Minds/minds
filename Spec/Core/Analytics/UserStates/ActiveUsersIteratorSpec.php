<?php

namespace Spec\Minds\Core\Analytics\UserStates;

use Minds\Core\Analytics\UserStates\ActiveUsersIterator;
use Minds\Core\Analytics\UserStates\UserActivityBuckets;
use PhpSpec\ObjectBehavior;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared\Search;

class ActiveUsersIteratorSpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    public function let(Client $client)
    {
        $this->beConstructedWith($client);
        $this->client = $client;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ActiveUsersIterator::class);
    }

    public function it_should_get_the_list()
    {
        $prepared = new Search();
        $prepared->query($this->getMockData('active_users_query.json'));
        $this->setReferenceDate(1549497600);
        $this->client->request($prepared)
        ->shouldBeCalled()
        ->willReturn($this->getMockData('active_users_results.json'));
        $this->next();
        $this->valid()->shouldBe(true);
        $this->current()->shouldHaveType(UserActivityBuckets::class);
        $this->current()->getUserGuid()->shouldEqual('934155581860614163');
    }

    private function getMockData($filename)
    {
        return json_decode(file_get_contents(__DIR__."/MockData/${filename}"), true);
    }
}
