<?php

namespace Minds\Core\Provisioner\Provisioners;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Exceptions\ProvisionException;

class CockroachProvisioner implements ProvisionerInterface
{
    protected $config;
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO'); // Should be created on-the-fly at provision()
    }

    public function provision()
    {
        $schema = explode(';', file_get_contents(dirname(__FILE__) . '/cockroach-provision.sql'));

        foreach ($schema as $query) {
            if (trim($query) === '') {
                continue;
            }
            $statement = $this->db->prepare($query);

            $statement->execute();
        }
    }

}