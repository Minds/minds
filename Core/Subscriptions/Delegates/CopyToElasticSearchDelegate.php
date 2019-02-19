<?php
/**
 * Copy to elasticsearch
 */
namespace Minds\Core\Subscriptions\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared\Update as Prepared;

class CopyToElasticSearchDelegate
{

    /** @var ElasticSearch $es */
    private $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * Copy subscription
     * @param Subscription $subscrition
     * @return void
     */
    public function copy($subscription)
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
                    'guid' =>  (int) $subscription->getPublisherGuid(),
                ],
            ],
            'scripted_upsert' => true,
            'upsert' => [ 
                'guids' => []
            ],
        ];

        $query = [
            'index' => 'minds-graph',
            'type' => 'subscriptions',
            'id' => $subscription->getSubscriberGuid(),
            'body' => $body,
        ];
        
        $prepared = new Prepared();
        $prepared->query($query);
        $this->es->request($prepared);
    }

    public function remove($subscription)
    {
        $body = [
            'script' => [
                'inline' => "
                    if (!ctx._source.containsKey(\"guids\")) {
                        ctx._source.guids = [];
                    }
                    if (ctx._source.guids.contains(params.guid)) {
                        ctx._source.guids.remove(ctx._source.guids.indexOf(params.guid));
                    } else {
                        ctx.op = 'none';
                    }
                ",
                'lang' => 'painless',
                'params' => [
                    'guid' =>  (int) $subscription->getPublisherGuid(),
                ],
            ],
            'scripted_upsert' => true,
            'upsert' => [ 
                'guids' => []
            ],
        ];

        $query = [
            'index' => 'minds-graph',
            'type' => 'subscriptions',
            'id' => $subscription->getSubscriberGuid(),
            'body' => $body,
        ];
        
        $prepared = new Prepared();
        $prepared->query($query);
        try {
            $this->es->request($prepared);
        } catch (\Exception $e) { }
    }
}
