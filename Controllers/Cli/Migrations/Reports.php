<?php

namespace Minds\Controllers\Cli\Migrations;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Core\Data\Cassandra\Prepared;

use Cassandra;

class Reports extends Cli\Controller implements Interfaces\CliControllerInterface
{
    private static $limit = 72;

    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli migrations reports');
    }

    public function exec()
    {
        $this->out('Start migration?', $this::OUTPUT_INLINE);
        $answer = trim(readline('[y/N] '));

        if ($answer != 'y') {
            throw new Exceptions\CliException('Cancelled by user');
        }

        $db = new Core\Data\Call('entities_by_time');

        $states = [
            'archive' => 'archived',
            'history' => 'actioned',
        ];

        $reasons = [
            'spam' => 8,
            'sensitive' => 2
        ];

        /** @var Core\Data\Cassandra\Client $client */
        $client = Di::_()->get('Database\Cassandra\Cql');

        $offset = '';
        while (true) {
            $rows = $db->getRow('reports', [ 'limit' => 500, 'offset' => $offset ]);

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
                    // Conversions

                    $state = 'review';
                    $reason = 11;
                    $reason_note = '';
                    $action = '';

                    if ($value['state'] && isset($states[$value['state']])) {
                        $state = $states[$value['state']];
                    }

                    if ($value['subject'] && isset($reasons[$value['subject']])) {
                        $reason = $reasons[$value['subject']];
                    } else {
                        $reason_note = "Legacy '{$value['subject']}'";
                    }

                    // CQL operation
                    $template = "INSERT INTO reports (
                        guid,
                        entity_guid,
                        time_created,
                        reporter_guid,
                        owner_guid,
                        state,
                        reason,
                        reason_note,
                        action
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $values = [
                        new Cassandra\Varint($value['guid']),
                        new Cassandra\Varint($value['entity']['guid']),
                        new Cassandra\Timestamp($value['time_created']),
                        new Cassandra\Varint($value['from']['guid']),
                        new Cassandra\Varint($value['entity']['owner_guid']),
                        (string) $state,
                        (string) $reason,
                        (string) $reason_note,
                        (string) $value['action']
                    ];

                    $query = new Prepared\Custom();
                    $query->query($template, $values);
                    $this->out("Migrating {$value['guid']}…", $this::OUTPUT_INLINE);

                    try {
                        $success = $client->request($query);
                    } catch (\Exception $e) {
                        $success = false;
                    }

                    $this->out($success ? 'OK' : 'Fail');
                }
            }

        }

        $this->out('Done!');
    }
}
