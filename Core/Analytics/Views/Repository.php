<?php
/**
 * Repository
 * @author edgebal
 */

namespace Minds\Core\Analytics\Views;

use Cassandra\Rows;
use Cassandra\Timeuuid;
use Cassandra\Tinyint;
use DateTime;
use DateTimeZone;
use Exception;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client as CassandraClient;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Repository
{
    /** @var CassandraClient */
    protected $db;

    /**
     * Repository constructor.
     * @param CassandraClient $db
     */
    public function __construct(
        $db = null
    )
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 500,
            'offset' => '',
            'year' => null,
            'month' => null,
            'day' => null,
            'from' => null,
        ], $opts) ;

        $cql = "SELECT * FROM views";
        $values = [];
        $cqlOpts = [];
        $where = [];

        // TODO: Implement constraints (by year/month/day/timeuuid)

        if ($opts['year']) {
            $where[] = 'year = ?';
            $values[] = (int) $opts['year'];
        }

        if ($opts['month']) {
            $where[] = 'month = ?';
            $values[] = new Tinyint($opts['month']);
        }

        if ($opts['day']) {
            $where[] = 'day = ?';
            $values[] = new Tinyint($opts['day']);
        }

        if ($opts['from']) {
            $where[] = 'uuid > ?';
            $values[] = new Timeuuid($opts['from'] * 1000);
        }

        if (count($where)) {
            $cql .= " WHERE " . implode(' AND ', $where);
        }

        if ($opts['limit']) {
            $cqlOpts['page_size'] = (int) $opts['limit'];
        }

        if ($opts['offset']) {
            $cqlOpts['paging_state_token'] = base64_decode($opts['offset']);
        }

        $prepared = new Custom();
        $prepared->query($cql, $values);
        $prepared->setOpts($cqlOpts);

        $response = new Response();

        try {
            /** @var Rows $rows */
            $rows = $this->db->request($prepared);

            foreach ($rows as $row) {
                $view = new View();
                $view
                    ->setYear((int) $row['year'] ?: null)
                    ->setMonth((int) $row['month'] ?: null)
                    ->setDay((int) $row['day'] ?: null)
                    ->setUuid($row['uuid']->uuid() ?: null)
                    ->setEntityUrn($row['entity_urn'])
                    ->setPageToken($row['page_token'])
                    ->setPosition((int) $row['position'])
                    ->setSource($row['platform'])
                    ->setSource($row['source'])
                    ->setMedium($row['medium'])
                    ->setCampaign($row['campaign'])
                    ->setDelta((int) $row['delta'])
                    ->setTimestamp($row['uuid']->time());

                $response[] = $view;
            }

            $response->setPagingToken(base64_encode($rows->pagingStateToken()));
            $response->setLastPage($rows->isLastPage());
        } catch (Exception $e) {
            $response->setException($e);
        }

        return $response;
    }

    /**
     * @param View $view
     * @return bool
     * @throws Exception
     */
    public function add(View $view)
    {
        $timestamp = $view->getTimestamp() ?: time();
        $date = new DateTime("@{$timestamp}", new DateTimeZone('utc'));

        $cql = "INSERT INTO views (year, month, day, uuid, entity_urn, page_token, position, platform, source, medium, campaign, delta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [
            (int) ($view->getYear() ?? $date->format('Y')),
            new Tinyint((int) ($view->getMonth() ?? $date->format('m'))),
            new Tinyint((int) ($view->getDay() ?? $date->format('d'))),
            new Timeuuid($view->getUuid() ?? $timestamp * 1000),
            $view->getEntityUrn() ?: '',
            $view->getPageToken() ?: '',
            (int) ($view->getPosition() ?? -1),
            $view->getPlatform() ?: '',
            $view->getSource() ?: '',
            $view->getMedium() ?: '',
            $view->getCampaign() ?: '',
            (int) ($view->getDelta() ?? 0),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $this->db->request($prepared, true);
            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
}
