<?php

namespace Minds\Core\Data\Neo4j\Commands;

use Neoxygen\NeoClient\Command\AbstractCommand;

/**
* Class that is used to get the extensions listed in the API
*/
class GeomLink extends AbstractCommand
{


    public $node_id;

    private $nodeUri;
    public function setArguments($nodeUri){
        $this->nodeUri = (string) $nodeUri;
    }

    public function execute()
    {
        $method = 'POST';
        $path = '/db/data/index/node/geom';
        $data = json_encode(array(
                    "value" => "dummy",
                    "key" => "dummy",
                    "uri" => $this->nodeUri 
                ));

        // The arguments for the send method of the http client are
        // $method, $path, $body = null, $connectionAlias = null

        return $this->process($method, $path, $data, $this->connection);
//        return $this->httpClient->sendRequest($request);
    }
}

