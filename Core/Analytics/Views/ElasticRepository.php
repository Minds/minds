<?php
/**
 * ElasticRepository
 * @author Mark 
 */

namespace Minds\Core\Analytics\Views;

use DateTime;
use DateTimeZone;
use Exception;
use Minds\Common\Repository\Response;
use Minds\Core\Data\ElasticSearch\Client as ElasticClient;
use Minds\Core\Di\Di;

class ElasticRepository
{
    /** @var ElasticClient */
    protected $es;

    /** @var array $pendingBulkInserts * */
    private $pendingBulkInserts = [];

    /**
     * Repository constructor.
     * @param ElasticClient $es 
     */
    public function __construct(
        $es = null
    )
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $response = new Response();

        return $response;
    }

    /**
     * @param View $view
     * @return bool
     * @throws Exception
     */
    public function add(View $view)
    {
        $index = 'minds-views-' . date('m-Y', $view->getTimestamp());

        $body = [
            'uuid' => $view->getUuid(),
            '@timestamp' => $view->getTimestamp() * 1000,
            'entity_urn' => $view->getEntityUrn(),
            'page_token' => $view->getPageToken(),
            'campaign' => $view->getCampaign(),
            'delta' => (int) $view->getDelta(),
            'medium' => $view->getMedium(),
            'platform' => $view->getPlatform(),
            'position' => (int) $view->getPosition(),
            'source' => $view->getSource(),
        ];

        $body = array_filter($body, function($val) {
            if ($val === '' || $val === null) {
                return false;
            }
            return true;
        });

        $this->pendingBulkInserts[] = [
            'update' => [
                '_id' => (string) $view->getUuid(),
                '_index' => $index,
                '_type' => '_doc',
            ],
        ];

        $this->pendingBulkInserts[] = [
            'doc' => $body,
            'doc_as_upsert' => true,
        ];

        if (count($this->pendingBulkInserts) > 2000) { //1000 inserts
            $this->bulk();
        }
    }

    /**
     * Bulk insert results
     */
    public function bulk()
    {
        if (count($this->pendingBulkInserts) > 0) {
            $res = $this->es->bulk(['body' => $this->pendingBulkInserts]);
            $this->pendingBulkInserts = [];
        }
    }
}
