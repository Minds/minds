<?php

namespace Minds\Core\Provisioner\Provisioners;

use Minds\Core\Di\Di;
use PDO;

class CockroachProvisioner implements ProvisionerInterface
{
    protected $config;

    public function provision(bool $cleanData)
    {
        $config = Di::_()->get('Config')->get('database');
        $host = isset($config['host']) ? $config['host'] : 'cockroachdb';
        $port = isset($config['port']) ? $config['port'] : 26257;
        $dbName = isset($config['name']) ? $config['name'] : 'minds';
        $sslMode = isset($config['sslmode']) ? $config['sslmode'] : 'disable';
        $username = isset($config['username']) ? $config['username'] : 'php';

        // Using root account because only superusers have permission to create databases.
        $adminDb = new PDO("pgsql:host=$host;port=$port;dbname=$dbName;sslmode=$sslMode",
            'root',
            null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_PERSISTENT => true,
            ]);

        $adminDb->prepare("CREATE USER IF NOT EXISTS $username")->execute();
        if ($cleanData)
        {
            $adminDb->prepare("DROP DATABASE IF EXISTS $dbName")->execute();
        }
        $adminDb->prepare("CREATE DATABASE IF NOT EXISTS $dbName")->execute();
        $adminDb->prepare("GRANT ALL ON DATABASE $dbName TO $username")->execute();
        $schema = explode(';', file_get_contents(dirname(__FILE__) . '/cockroach-provision.sql'));

        foreach ($schema as $query) {
            if (trim($query) === '') {
                continue;
            }
            try {
                $statement = $adminDb->prepare($query);
                $statement->execute();
            } catch (\Exception $ex) {
                error_log("Error running cockroach statement: " . $ex->getMessage());
            }
        }
    }
}
