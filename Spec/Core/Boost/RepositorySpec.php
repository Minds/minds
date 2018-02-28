<?php

namespace Spec\Minds\Core\Boost;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PhpSpec\Exception\Example\FailureException;

use Minds\Core\Data\Cassandra;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;

use Spec\Minds\Mocks;

class RepositorySpec extends ObjectBehavior
{
    private static $boostEntityDataMock = [
        'guid' => 1000,
        '_id' => 'm1000',
        'entity' => false,
        'bid' => 1000,
        'bidType' => 'points',
        'impressions' => 1000,
        'owner' => false,
        'state' => 'tested',
        'time_created' => '10000000',
        'last_updated' => '10000001',
        'transactionId' => '',
        'handler' => 'network',
        'rating' => 0,
        'quality' => 0,
        'priorityRate' => 1,
        'checksum' => 'testme',
        'categories' => [],
        'rejection_reason' => []
    ];

    protected $_client;

    function let(Cassandra\Client $client)
    {
        $this->beConstructedWith($client);

        $this->_client = $client;
    }

    // getAll()

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Repository');
    }

    function it_should_get_a_list_of_boosts()
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
        ], '');

        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll('network');

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Boost\Network::class);
        $return['next']->shouldReturn('');
    }

    function it_should_get_a_list_of_boosts_by_owner()
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
        ], '');

        $this->_client->request(Argument::that(function ($query) {
            if (!($query instanceof Prepared\Custom)) {
                return false;
            }

            $template = $query->build();

            return strpos($template['string'], 'owner_guid = ?') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll('network', [
            'owner_guid' => 1000
        ]);

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Boost\Network::class);
        $return['next']->shouldReturn('');
    }

    function it_should_get_a_list_of_boosts_by_destination()
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
        ], '');

        $this->_client->request(Argument::that(function ($query) {
            if (!($query instanceof Prepared\Custom)) {
                return false;
            }

            $template = $query->build();

            return strpos($template['string'], 'destination_guid = ?') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll('network', [
            'destination_guid' => 1000
        ]);

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Boost\Network::class);
        $return['next']->shouldReturn('');
    }

    function it_should_get_a_list_of_boosts_by_owner_and_destination()
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
        ], '');

        $this->_client->request(Argument::that(function ($query) {
            if (!($query instanceof Prepared\Custom)) {
                return false;
            }

            $template = $query->build();

            return strpos($template['string'], 'owner_guid = ?') !== false &&
                strpos($template['string'], 'destination_guid = ?') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll('network', [
            'owner_guid' => 1000,
            'destination_guid' => 1001
        ]);

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Boost\Network::class);
        $return['next']->shouldReturn('');
    }

    function it_should_get_a_list_of_specific_boosts_by_guid()
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
            [ 'type' => 'network', 'data' => $this::$boostEntityDataMock ],
        ], '');

        $this->_client->request(Argument::that(function ($query) {
            if (!($query instanceof Prepared\Custom)) {
                return false;
            }

            $template = $query->build();

            return strpos($template['string'], ' guid IN ?') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll('network', [
            'guids' => [ 1000, 1001 ]
        ]);

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Boost\Network::class);
        $return['next']->shouldReturn('');
    }

    function it_should_not_get_a_list_of_boosts_if_no_type()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringGetAll(null);
    }

    // getEntity()

    function it_should_get_a_single_boost()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([
                [ 'type' => 'network', 'data' => [ ] ],
            ]);

        $this
            ->getEntity('network', 2000)
            ->shouldReturnAnInstanceOf(Entities\Boost\Network::class);
    }

    function it_should_not_get_a_single_boost_if_no_type()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->getEntity(null, 2000)
            ->shouldReturn(false);
    }

    function it_should_not_get_a_single_boost_if_no_guid()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->getEntity('network', null)
            ->shouldReturn(false);
    }

    function it_should_not_get_a_single_boost_if_not_exists()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([]);

        $this
            ->getEntity('network', 2404)
            ->shouldReturn(false);
    }

    // getEntityById()

    function it_should_get_a_single_boost_based_on_mongo()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([
                [ 'type' => 'network', 'data' => [ ] ],
            ]);

        $this
            ->getEntityById('network', 'm2000')
            ->shouldReturnAnInstanceOf(Entities\Boost\Network::class);
    }

    function it_should_not_get_a_single_boost_based_on_mongo_if_no_type()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->getEntityById(null, 'm2000')
            ->shouldReturn(false);
    }

    function it_should_not_get_a_single_boost_based_on_mongo_if_no_id()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->getEntityById('network', null)
            ->shouldReturn(false);
    }

    function it_should_not_get_a_single_boost_based_on_mongo_if_not_exists()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([]);

        $this
            ->getEntityById('network', 'm2404')
            ->shouldReturn(false);
    }
 
    // upsert()

    function it_should_store_a_boost()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->upsert('network', [
                'guid' => 2000,
                'owner' => [ 'guid' => 1000 ],
                '_id' => 'x1282334',
                'state' => 'tested'
            ])
            ->shouldReturn(true);
    }

    function it_should_store_a_boost_with_a_destination()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->upsert('network', [
                'guid' => 2000,
                'owner' => [ 'guid' => 1000 ],
                'destination' => [ 'guid' => 1001 ],
                '_id' => 'x2335466',
                'state' => 'tested'
            ])
            ->shouldReturn(true);
    }

    function it_should_not_store_a_boost_if_no_type()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringUpsert(null, [
               'guid' => 2000,
               'owner' => [ 'guid' => 1000 ],
               'state' => 'tested'
            ]);
    }

    function it_should_not_store_a_boost_if_no_guid()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringUpsert('network', [
                'owner' => [ 'guid' => 1000 ],
                'state' => 'tested'
            ]);
    }

    function it_should_not_store_a_boost_if_no_owner()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringUpsert('network', [
                'guid' => 2000,
                'owner' => [ ],
                'state' => 'tested'
            ]);
    }

    function it_should_not_store_a_boost_if_no_state()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringUpsert('network', [
                'guid' => 2000,
                'owner' => [ 'guid' => 1000 ],
            ]);
    }

    function it_should_not_store_a_boost_if_db_fails()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->upsert('network', [
                'guid' => 2000,
                'owner' => [ 'guid' => 1000 ],
                '_id' => 'x34506043',
                'state' => 'tested'
            ])
            ->shouldReturn(false);
    }

    //

    function getMatchers()
    {
        $matchers = [];

        $matchers['haveKeys'] = function($subject, array $keys) {
            $valid = true;

            foreach ($keys as $key) {
                $valid = $valid && array_key_exists($key, $subject);
            }

            return $valid;
        };

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
