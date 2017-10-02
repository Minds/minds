<?php

namespace Minds\Controllers\Cli\Migrations;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Core\Data\Cassandra\Prepared;

use Cassandra;

class Boosts extends Cli\Controller implements Interfaces\CliControllerInterface
{
    private static $limit = 72;

    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli migrations boosts [network|peer]');
    }

    public function exec() {
        $this->out('Syntax usage: cli migrations boosts [network|peer]');
    }

    public function network()
    {
        $this->out('Start migration?', $this::OUTPUT_INLINE);
        $answer = trim(readline('[y/N] '));

        if ($answer != 'y') {
            throw new Exceptions\CliException('Cancelled by user');
        }

        /** @var Core\Boost\Repository $repository */
        $repository = Di::_()->get('Boost\Repository');
        $db = new Core\Data\Call('entities_by_time');
        $rowKeys = [
            'boost:newsfeed',
            'boost:content'
        ];

        foreach ($rowKeys as $rowKey) {
            $offset = '';
            while (true) {
                $rows = $db->getRow($rowKey, ['limit' => 500, 'offset' => $offset]);

                if ($offset && isset($rows[$offset])) {
                    unset($rows[$offset]);
                }

                if (!$rows) {
                    break;
                }

                $guids = array_keys($rows);
                $offset = end($guids);

                foreach ($rows as $guid => $value) {
                    if ($value && is_string($value)) {
                        $value = json_decode($value, true);
                    }

                    if ($value) {
                        $this->out("Migrating {$value['guid']} ", $this::OUTPUT_INLINE);

                        $done = $repository->upsert($value['handler'], $value);
                        $this->out($done ? 'OK!' : 'Failed…');
                    }
                }

            }
        }

        $this->out('Done!');
    }

    public function peer()
    {
        $this->out('Start migration?', $this::OUTPUT_INLINE);
        $answer = trim(readline('[y/N] '));

        if ($answer != 'y') {
            throw new Exceptions\CliException('Cancelled by user');
        }

        /** @var Core\Boost\Repository $repository */
        $repository = Di::_()->get('Boost\Repository');
        $db = new Core\Data\Call('entities_by_time');

        $userOffset = '';
        while (true) {
            $users = $db->getRow('user', ['limit' => 500, 'offset' => $userOffset]);

            if ($userOffset && isset($users[$userOffset])) {
                unset($users[$userOffset]);
            }

            if (!$users) {
                break;
            }

            $userGuids = array_values($users);
            $userOffset = end($userGuids);

            foreach ($userGuids as $userGuid) {
                $this->out("Migrating user {$userGuid}…");
                $rowKey = 'boost:peer:' . $userGuid;

                $offset = '';
                while (true) {
                    $rows = $db->getRow($rowKey, ['limit' => 500, 'offset' => $offset]);

                    if ($offset && isset($rows[$offset])) {
                        unset($rows[$offset]);
                    }

                    if (!$rows) {
                        break;
                    }

                    $guids = array_keys($rows);
                    $offset = end($guids);

                    foreach ($rows as $guid => $value) {
                        if ($value && is_string($value)) {
                            $value = json_decode($value, true);
                        }

                        if ($value) {
                            $this->out("- Migrating boost {$value['guid']} ", $this::OUTPUT_INLINE);

                            $done = $repository->upsert($value['handler'], $value);
                            $this->out($done ? 'OK!' : 'Failed…');
                        }
                    }
                }
            }
        }

        $this->out('Done!');
    }
}
