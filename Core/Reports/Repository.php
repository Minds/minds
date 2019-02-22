<?php
/**
 * Reported entities 
 */
namespace Minds\Core\Reports;

use Minds\Core\Di\Di;
use Minds\Common\Repository\Response;
use Minds\Core\Data\ElasticSearch\Prepared\Search as Prepared;

class Repository
{

    /** @var $es */
    private $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * Return a list
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => 0,
            'user_guid' => null,
            'paging-token' => '',
        ], $opts);

        if ($opts['offset']) {
            $opts['limit'] += $opts['offset'];
        }

        
        $query = [
            'index' => 'minds-metrics-*',
            'size' => 0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'must_not' => $must_not,
                    ],
                ],
                'aggs' => [
                    'subscriptions' => [
                        'terms' => [
                            'field' => 'entity_guid.keyword',
                            'size' => $opts['limit'],
                            'order' => [
                                '_count' =>  'desc',
                            ], 
                        ],
                    ],
                ],
            ],
        ];
        
        $prepared = new Prepared();
        $prepared->query($query);

        $result = $this->es->request($prepared);

        $response = new Response();
        
        return $response;
    }

    /**
     * Return a single report
     * @return Suggestion
     */
    public function get($guid)
    {
        // Not implemented
    }

    public function add($suggestion)
    {
        // Not implemented
    }

    public function update($suggestion)
    {
        // Not implemented
    }


    public function delete($suggestion)
    {
        // Not implemented
    }

}
