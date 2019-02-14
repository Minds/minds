<?php
/**
 * Pass Repository
 */
namespace Minds\Core\Suggestions\Pass;

use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared\Update as Prepared;

class Repository
{

    /** @var ElasticSearch $es */
    private $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * Return a list of passes
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {

    }

    /**
     * Return a single pass
     * @param Pass $pass
     * @return Pass
     */
    public function get($pass)
    {
    }

    /**
     * Add a pass
     * @param Param $pass
     * @return boolean
     */
    public function add(Pass $pass)
    {
        $body = [
            'script' => [
                'inline' => "
                    if (!ctx._source.containsKey(\"guids\")) {
                        ctx._source.guids = [];
                    }
                    if (ctx._source.guids.contains(params.guid)) {
                        ctx.op = 'none';
                    } else {
                        ctx._source.guids.add(params.guid);
                    }
                ",
                'lang' => 'painless',
                'params' => [
                    'guid' =>  (int) $pass->getSuggestedGuid(),
                ],
            ],
            'scripted_upsert' => true,
            'upsert' => [
                'guids' => []
            ],
        ];

        $query = [
            'index' => 'minds-graph',
            'type' => 'pass',
            'id' => $pass->getUserGuid(),
            'body' => $body,
        ];

        $prepared = new Prepared();
        $prepared->query($query);

        return (bool) $this->es->request($prepared);
    }

    /**
     * Update a pass
     * @param Param $pass
     * @param array $fields
     * @return boolean
     */
    public function update(Pass $pass, $fields = [])
    {
    }

    /**
     * Delete a pass
     * @param Pass $pass
     * @return boolean
     */
    public function delete(Pass $Pass)
    {

    }

}

