<?php

namespace Spec\Minds\Core\Provisioner\Provisioners;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Config\Config;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra;
use Minds\Exceptions;

class CassandraProvisionerSpec extends ObjectBehavior
{
    private $_db;
    private $_client;

    function let(
        Config $config,
        Data\Call $db,
        Cassandra\Client $client
    )
    {
        $this->beConstructedWith($config, $db, $client);
        $this->_db = $db;
        $this->_client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Provisioner\Provisioners\CassandraProvisioner');
    }

    function it_should_provision()
    {
        $this->_db->keyspaceExists()->shouldBeCalled()->willReturn(false);
        $this->_db->createKeyspace(Argument::type('array'))->shouldBeCalled()->willReturn(true);

        $this->_client->request(Argument::type(Cassandra\Prepared\System::class))
            ->shouldBeCalledTimes(40)
            ->willReturn(null);

        $this->provision()->shouldReturn(true);
    }

    function it_should_not_provision_if_keyspace_exists()
    {
        $this->_db->keyspaceExists()->shouldBeCalled()->willReturn(true);

        $this
            ->shouldThrow(Exceptions\ProvisionException::class)
            ->duringProvision();
    }
}
