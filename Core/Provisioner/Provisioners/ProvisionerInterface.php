<?php
namespace Minds\Core\Provisioner\Provisioners;

use Minds\Core\Provisioner\Tasks\TaskInterface;

interface ProvisionerInterface
{
    public function provision(array $options = []);
}
