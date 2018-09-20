<?php
/**
 * 
 */
namespace Minds\Core\Data\SQL;

class Client
{
    /** @var PDO $dbh */
    private $dbh;

    public function __construct($dbh = null)
    {
        $this->dbh = $dbh ?: new PDO('pgsql:host=cockroachdb;port=26257;dbname=minds;sslmode=disable',
            'maxroach',
            null, 
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => true,
            ]);
    }

    /**
     * Request
     * @param $request
     * @return Response
     */
    public function request($prepared)
    {
        $this->dbh->exec("SELECT * FROM suggested");
    }

}
