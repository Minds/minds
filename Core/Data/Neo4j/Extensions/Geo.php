<?php

namespace Minds\Core\Data\Neo4j\Extensions;

use Neoxygen\NeoClient\Extension\AbstractExtension;
use Neoxygen\NeoClient\Extension\NeoClientExtensionInterface;

class Geo extends AbstractExtension
{
    public static function getAvailableCommands()
    {
        return array(
            "geolink" => array(
                'class'=>'Minds\Core\Data\Neo4j\Commands\GeomLink'
            )
        );
    }

    public function geoLink($nodeId, $conn = null)
    {
        $nodeUri = $this->connectionManager->getConnection($conn)->getBaseUrl().'/db/data/node/'. (int) $nodeId;
        $command = $this->invoke('geolink', $conn);
        $command->setArguments($nodeUri);
        $response = $command->execute();
        return $this->handleHttpResponse($response);
    }
}
