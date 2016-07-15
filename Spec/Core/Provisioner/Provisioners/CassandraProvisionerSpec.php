<?php

namespace Spec\Minds\Core\Provisioner\Provisioners;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CassandraProvisionerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Provisioner\Provisioners\CassandraProvisioner');
    }
}
