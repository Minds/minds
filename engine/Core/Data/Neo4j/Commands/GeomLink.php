<?php

namespace Minds\Core\Data\Neo4j\Commands;

use Neoxygen\NeoClient\Command\AbstractCommand;

/**
* Class that is used to get the extensions listed in the API
*/
class GeomLink extends AbstractCommand
{
    public $node_id;

    public function execute()
    {
        $method = 'POST';
        $path = '/db/data/index/node/geom';
        $data = json_encode(array(
                    "value" => "dummy",
                    "key" => "dummy",
                    "uri" => "http://localhost:7474/db/data/node/' . $this->node_id . '"
                ));

        // The arguments for the send method of the http client are
        // $method, $path, $body = null, $connectionAlias = null

        return $this->httpClient->send($method, $path, $data, $this->connection);
    }
}

