<?php

namespace Spec\Minds\Core\Sessions;

use Minds\Core\Sessions\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Sessions\Session;
use Minds\Core\Data\Cassandra\Client;
use Cassandra\Varint;
use Cassandra\Timestamp;

class RepositorySpec extends ObjectBehavior
{

    /** @var Client $client */
    private $client;

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function let(Client $client) {
        $this->client = $client;
        $this->beConstructedWith($this->client);
    }

    function it_should_return_a_session_from_id()
    {
        $this->client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] == new Varint(123)
                && $values[1] == 'sess_id';
            }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'id' => 'sess_id',
                    'user_guid' => new Varint(1001),
                    'expires' => new Timestamp(time())
                ]
            ]);
        
        $session = $this->get(123, 'sess_id');
        $session->getId()
            ->shouldReturn('sess_id');
    }

    function it_should_not_return_a_session_from_id()
    {
        $this->client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] == new Varint(123)
                && $values[1] == 'sess_id';
            }))
            ->shouldBeCalled()
            ->willReturn([
            ]);
        
        $session = $this->get(123, 'sess_id');
        $session->shouldReturn(null);
    }   
    
    function it_should_save_session_to_database()
    {
        $time = time();
        $this->client->request(Argument::that(function($prepared) use ($time) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] == new Varint(123)
                && $values[1] == 'sess_id'
                && $values[2] == new Timestamp($time);
            }))
            ->shouldBeCalled();

        $session = new Session();
        $session->setId('sess_id')
            ->setUserGuid(123)
            ->setExpires($time);
        
        $this->add($session);
    }

    function it_should_remove_session_from_database()
    {
        $time = time();
        $this->client->request(Argument::that(function($prepared) use ($time) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $template == "DELETE FROM jwt_sessions WHERE user_guid = ? AND id = ?"
                && $values[0] == new Varint(123)
                && $values[1] == 'sess_id';
            }))
            ->shouldBeCalled();

        $session = new Session();
        $session->setId('sess_id')
            ->setUserGuid(123)
            ->setExpires($time);
        
        $this->delete($session);
    }

}
