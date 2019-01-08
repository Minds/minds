<?php

namespace Spec\Minds\Core\Security\SpamBlocks;

use Minds\Core\Security\SpamBlocks\SpamBlock;
use Minds\Core\Security\SpamBlocks\Repository;
use Minds\Core\Data\Cassandra\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{

    private $client;
    
    function let(Client $client)
    {
        $this->beConstructedWith($client);
        $this->client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_a_single_spam_block()
    {
        $this->client->request(Argument::any())
            ->willReturn([
                [
                    'key' => 'k1',
                    'value' => 'v1',
                ]
            ]);
        
        $model = $this->get('k1', 'v1');
        $model->getKey()->shouldBe('k1');
        $model->getValue()->shouldBe('v1');
    }

    function it_should_add_a_model()
    {
        $this->client->request(Argument::that(function($query) {
                $values = $query->build()['values'];
                return $values[0] == 'k1'
                    && $values[1] == 'v1';
            }))
            ->willReturn(true);
        
        $model = new SpamBlock;
        $model->setKey('k1')
            ->shouldBeCalled();
        $model->setValue('v1')
            ->shouldBeCalled();

        $this->add($model)->shouldReturn(true);
    }

}
