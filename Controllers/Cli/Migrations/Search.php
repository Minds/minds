<?php

namespace Minds\Controllers\Cli\Migrations;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Entities\Factory as EntityFactory;
use Minds\Exceptions;

class Search extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli migrations search [dev]');
    }

    public function exec()
    {
        $this->out('Not implemented. Use: `cli migrations search dev` for dev environment migrations.');
    }


    public function dev()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->out('!!! WARNING !!!: This feature is intended for development environments only.');
        $this->out('Start migration?', $this::OUTPUT_INLINE);
        $answer = trim(readline('[y/N] '));

        if ($answer != 'y') {
            throw new Exceptions\CliException('Cancelled by user');
        }

        ///////////

        $allowedTypes = [
            'activity',
            'user',
            'group',
            'object:blog',
            'object:image',
            'object:album',
            'object:video'
        ];

        $this->out('Setting up mappings…', static::OUTPUT_INLINE);
        Di::_()->get('Search\Provisioner')->setUp();
        $this->out('OK!');

        //

        $client = Di::_()->get('Database\ElasticSearch');
        $esIndex = Di::_()->get('Config')->elasticsearch['index']; 


        /** @var Core\Data\Call $db */
        $db = Core\Di\Di::_()->get('Database\Cassandra\Entities');

        $offset = '';
        while (true) {
            $columns = $db->get($offset);

            $result = [];

            foreach ($columns as $guid => $column) {
                $result[] = array_merge([ 'guid' => (string) $guid ], $column);
            }

            if ($offset && $result) {
                array_shift($result);
            }

            if (!$result) {
                break;
            }

            foreach ($result as $row) {
                $offset = $row['guid'];

                if (!isset($row['type'])) {
                    continue;
                }

                $key = $row['type'];

                if (isset($row['subtype']) && $row['subtype']) {
                    $key .= ':' . $row['subtype'];
                }

                if (!in_array($key, $allowedTypes)) {
                    continue;
                }

                try {
                    $query = null;
                    $entity = \Minds\Entities\Factory::build($row);
                    /** @var Core\Search\Mappings\MappingInterface $mapper */
                    $mapper = Di::_()->get('Search\Mappings')->build($entity);

                    $this->out("Indexing {$mapper->getType()}/{$mapper->getId()}…", static::OUTPUT_INLINE);

                    $body = $mapper->map();

                    if ($suggest = $mapper->suggestMap()) {
                        $body = array_merge($body, [
                            'suggest' => $suggest
                        ]);
                    }

                    $query = [
                        'index' => $esIndex,
                        'type' => $mapper->getType(),
                        'id' => $mapper->getId(),
                        'body' => $body
                    ];

                    $prepared = new Core\Data\ElasticSearch\Prepared\Index();
                    $prepared->query($query);

                    $result = (bool) $client->request($prepared);

                    $this->out($result ? 'OK!' : 'Failed');
                } catch (\Exception $e) {
                    var_dump($query);
                    var_dump([get_class($e), $e->getMessage()]);
                }
            }
        }

        ///////////

        $this->out('Done!');
    }


    public function prod()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $type = $this->getOpt('type');
        $offset = $this->getOpt('offset') ?: '';
        $esIndex = Di::_()->get('Config')->elasticsearch['index'];

        $this->out("Running migrations for $type");

        $indexes = new Core\Data\Call('entities_by_time');
        $client = Di::_()->get('Database\ElasticSearch');
    
        $sFails = 0;
        while(true){
            $guids = $indexes->getRow($type, array('limit' => 750, 'offset' => $offset, 'reversed'=> true));
            if(count($guids) <= 1)
                break;
        
            foreach($guids as $guid => $ts){

                if ($sFails > 5) {
                    $this->out("Too many failures [pausing for 5 seconds]");
                    sleep(5);
                }

                try {

                    $entity = EntityFactory::build($guid);
                    $mapper = Di::_()->get('Search\Mappings')->build($entity);

                    echo "\r{$mapper->getType()}/{$mapper->getId()}…";
                    $body = $mapper->map();
                    
                    if ($suggest = $mapper->suggestMap()) {
                        $body = array_merge($body, [
                            'suggest' => $suggest
                        ]);
                    }

                    $query = [
                        'index' => $esIndex,
                        'type' => $mapper->getType(),
                        'id' => $mapper->getId(),
                        'body' => $body
                        ];

                    $prepared = new Core\Data\ElasticSearch\Prepared\Index();
                    $prepared->query($query);

                    $result = (bool) $client->request($prepared);

                    echo " [done]";
                    $sFails = 0;
                } catch (\Exception $e) {
                    echo " [failed]";
                    $sFails++;
                }

            }
        
            end($guids);
            $offset = key($guids);
        
        }
        

    }

}
