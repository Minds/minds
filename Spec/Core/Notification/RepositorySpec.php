<?php

namespace Spec\Minds\Core\Notification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PhpSpec\Exception\Example\FailureException;

use Minds\Core\Data\Cassandra;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;
use Spec\Minds\Mocks\Cassandra\Rows;

class RepositorySpec extends ObjectBehavior
{
    protected $_client;

    function let(Cassandra\Client $client)
    {
        $this->beConstructedWith($client);
        $this->_client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Notification\Repository');
    }

    // setOwner

    function it_should_set_owner()
    {
        $this
            ->setOwner(1000)
            ->shouldReturn($this);
    }

    // getAll

    function it_should_get_an_empty_list_of_notifications()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(new Rows([], ''));

        $this
            ->setOwner(1000)
            ->getAll()
            ->shouldReturn(['notifications' => [], 'token' => '']);
    }

    function it_should_get_a_list_of_notifications()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(
                new Rows([
                    [ 'data' => [ ] ],
                    [ 'data' => [ ] ],
                    [ 'data' => [ ] ],
                    [ 'data' => [ ] ],
                    [ 'data' => [ ] ],
                ], ''));

        $result = $this->setOwner(1000)
            ->getAll(null, ['limit' => 5]);
        $result['notifications']->shouldBeAnArrayOf(5, Entities\Notification::class);
    }

    function it_should_not_get_a_list_of_notifications_if_theres_no_owner()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringGetAll(null, [ 'limit' => 5 ]);
    }

    // getEntity

    function it_should_return_a_single_notification()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([
                [ 'data' => [ ] ],
            ]);

        $this
            ->setOwner(1000)
            ->getEntity(2000)
            ->shouldReturnAnInstanceOf(Entities\Notification::class);
    }

    function it_should_not_return_a_single_notification_if_doesnt_exist()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([]);

        $this
            ->setOwner(1000)
            ->getEntity(2000)
            ->shouldReturn(false);
    }

    function it_should_not_return_a_single_notification_if_falsy()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([ '' ]);

        $this
            ->setOwner(1000)
            ->getEntity(2000)
            ->shouldReturn(false);
    }

    function it_should_not_return_a_single_notification_if_no_guid()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->setOwner(1000)
            ->getEntity('')
            ->shouldReturn(false);
    }

    function it_should_not_return_a_single_notification_if_no_owner()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringGetEntity(2000);
    }

    // store

    function it_should_store()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setOwner(1000)
            ->store([ 'guid' => 2000 ])
            ->shouldReturn(true);
    }

    function it_should_store_if_ttl_is_not_past()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        //$ttl = $this->getWrappedObject()::NOTIFICATION_TTL - 1;

        $this
            ->setOwner(1000)
            ->store([ 'guid' => 2000 ])
            ->shouldReturn(true);
    }

    function it_should_not_store_if_ttl_is_past()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $ttl = \Minds\Core\Notification\Repository::NOTIFICATION_TTL + 1;

        $this
            ->setOwner(1000)
            ->store([ 'guid' => 2000 ], $ttl)
            ->shouldReturn(false);
    }

    function it_should_not_store_if_no_guid()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->setOwner(1000)
            ->store([])
            ->shouldReturn(false);
    }

    function it_should_not_store_if_no_owner()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringStore([ 'guid' => 2000 ]);
    }

    // delete

    function it_should_delete()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setOwner(1000)
            ->delete(2000)
            ->shouldReturn(true);
    }

    function it_should_not_delete_if_no_guid()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->setOwner(1000)
            ->delete('')
            ->shouldReturn(false);
    }

    function it_should_not_delete_if_no_owner()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringDelete(2000);
    }

    // count

    function it_should_count()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([ [ 'count' => 5 ] ]);

        $this
            ->setOwner(1000)
            ->count()
            ->shouldReturn(5);
    }

    function it_should_not_count_if_no_owner()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringCount();
    }

    //

    function getMatchers()
    {
        $matchers['beAnArrayOf'] = function ($subject, $count, $class) {
            if (!is_array($subject) || ($count !== null && count($subject) !== $count)) {
                throw new FailureException("Subject should be an array of $count elements");
            }

            $validTypes = true;

            foreach ($subject as $element) {
                if (!($element instanceof $class)) {
                    $validTypes = false;
                    break;
                }
            }

            if (!$validTypes) {
                throw new FailureException("Subject should be an array of {$class}");
            }

            return true;
        };

        return $matchers;
    }
}
