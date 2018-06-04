<?php
namespace Minds\Controllers\Cli\Migrations;

use Cassandra\Map;
use Cassandra\Set;
use Cassandra\Type;
use Cassandra\Timestamp;
use Cassandra\Varint;
use Minds\Cli;
use Minds\Core\Data\Iterators;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Entities\User;
use Minds\Exceptions\CliException;
use Minds\Interfaces;

class Comments extends Cli\Controller implements Interfaces\CliControllerInterface
{
    /** @var int */
    const IN_MEMORY_JSON_CACHE_POOL = 1000;

    /** @var Client $client */
    protected $client;

    public function help($command = null)
    {
        $this->out('Syntax usage: cli migrations comments migrate [--offset=?] [--dry]');
    }

    public function exec()
    {
        $this->help();
    }

    public function migrate()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $this->client = Di::_()->get('Database\Cassandra\Cql');

        $dry = !!$this->getOpt('dry');
        $offset = $this->getOpt('offset') ?: '';
        $fetchLimit = (int) $this->getOpt('fetch-limit') ?: 200;

        $this->out('');

        if ($dry) {
            $this->out('/!\\ This is going to be a dry run (no actual DB changes)');
        }

        /*$prompt = !$offset ? 'Start migration?' : "Start migration from token ${offset}?";
        $this->out($prompt, $this::OUTPUT_INLINE);
        $answer = trim(readline('[y/N] '));

        if ($answer != 'y') {
            throw new CliException('Cancelled by user');
        }*/

        $iterator = new Iterators\Entities();
        $iterator->setType('comment');
        $iterator->setFetchLimit($fetchLimit);

        if ($offset) {
            $iterator->setOffset($offset);
        }

        foreach ($iterator as $entityFuture) {
            $entity = [];

            foreach ($entityFuture->get() as $column) {
                $entity['guid'] = $column['key'];
                $entity[$column['column1']] = $column['value'];
            }

            $i = $iterator->getI() + 1;
            $this->out("[{$i}]; {$entity['guid']} ( offset=\"{$iterator->getOffset()}\" )");
            
            $this->_saveComment($entity, $dry);
        }

        $this->out('');
        $this->out("Migrated {$i} comments!");
    }

    protected function _saveComment($data, $dry = false)
    {
        $attachments = new Map(Type\Map::text(), Type\Map::text());
        $flags = new Map(Type\Map::text(), Type\Map::boolean());
        $votes_up = $this->_gatherVotes('up', $data['guid']);
        $votes_down = $this->_gatherVotes('down', $data['guid']);
        $owner_obj = isset($data['ownerObj']) ? (string) $data['ownerObj'] : $this->_gatherOwnerObj($data['owner_guid']);

        if (isset($data['custom_type']) || isset($data['custom_data'])) {
            $attachments->set('custom_type', (string) $data['custom_type']);
            $attachments->set('custom_data', (string) $data['custom_data']);
        }

        if (
            isset($data['title']) ||
            isset($data['blurb']) ||
            isset($data['perma_url']) ||
            isset($data['thumbnail_src'])
        ) {
            $attachments->set('title', (string) $data['title']);
            $attachments->set('blurb', (string) $data['blurb']);
            $attachments->set('perma_url', (string) $data['perma_url']);
            $attachments->set('thumbnail_src', (string) $data['thumbnail_src']);
        }

        if (isset($data['attachment_guid'])) {
            $attachments->set('attachment_guid', (string) $data['attachment_guid']);
        }

        if (isset($data['mature']) && $data['mature']) {
            $flags->set('mature', true);
        }

        if (isset($data['edited']) && $data['edited']) {
            $flags->set('edited', true);
        }

        if (isset($data['deleted']) && $data['deleted']) {
            $flags->set('deleted', true);
        }

        if (isset($data['spam']) && $data['spam']) {
            $flags->set('spam', true);
        }

        if (!isset($data['parent_guid']) || !$data['owner_guid']) {
            return false;
        }

        if (!is_numeric($data['parent_guid']) || !is_numeric($data['owner_guid'])) {
            return false;
        }

        $cql = "INSERT INTO comments
          (
            entity_guid,
            parent_guid,
            guid,
            has_children,
            owner_guid,
            container_guid,
            time_created,
            time_updated,
            access_id,
            body,
            attachments,
            flags,
            votes_up,
            votes_down,
            owner_obj
          )
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [
            new Varint($data['parent_guid']),
            new Varint(0),
            new Varint($data['guid']),
            false,
            new Varint($data['owner_guid']),
            new Varint($data['container_guid']),
            new Timestamp($data['time_created']),
            new Timestamp($data['time_updated']),
            new Varint($data['access_id']),
            (string) $data['description'],
            $attachments,
            $flags,
            $votes_up,
            $votes_down,
            $owner_obj
        ];

        $query = new Custom();
        $query->query($cql, $values);
        
        if (!$dry) {
            try { 
                $this->client->request($query);
            } catch (\Exception $e) {
                var_dump($e); 
            }
        }
    }

    protected function _gatherVotes($direction, $guid)
    {
        $set = new Set(Type\Set::varint());

        $cql = "SELECT * FROM entities_by_time WHERE key = ?";
        $values = [ "thumbs:{$direction}:entity:{$guid}" ];

        $query = new Custom();
        $query->query($cql, $values);

        $rows = $this->client->request($query);

        if ($rows) {
            foreach ($rows as $row) {
                $set->add(new Varint($row['column1']));
            }
        }

        return $set;
    }

    protected function _gatherOwnerObj($user_guid)
    {
        if (($value = static::_getFromJsonCache($user_guid)) !== null) {
            return $value;
        }

        $value = '';

        if ($user_guid) {
            try {
                $value = json_encode((new User($user_guid, false))->export());
            } catch (\Exception $e) {
                error_log(get_class($e) . ': ' . $e->getMessage());
            }
        }

        static::_saveToJsonCache($user_guid, $value);

        return $value;
    }

    // Poor-man's in memory json cache

    static $jsonCache = [];

    public static function _saveToJsonCache($key, $value)
    {
        if (count(static::$jsonCache) > static::IN_MEMORY_JSON_CACHE_POOL) {
            reset(static::$jsonCache);
            unset(static::$jsonCache[key(static::$jsonCache)]);
        }

        static::$jsonCache[(string) $key] = $value;
    }

    public static function _getFromJsonCache($key)
    {
        return isset(static::$jsonCache[(string) $key]) ?
            static::$jsonCache[(string) $key] :
            null;
    }
}
