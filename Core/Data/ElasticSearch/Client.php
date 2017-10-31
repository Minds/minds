<?php

/**
 * ElasticSearch Client
 *
 * @author emi
 */

namespace Minds\Core\Data\ElasticSearch;

use Elasticsearch;

use Minds\Core\Data\Interfaces;
use Minds\Core\Di\Di;

class Client implements Interfaces\ClientInterface
{
    /** @var Elasticsearch\Client $elasticsearch */
    protected $elasticsearch;

    /**
     * Client constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $hosts = Di::_()->get('Config')->elasticsearch['hosts'];

        $this->elasticsearch = Elasticsearch\ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    }

    /**
     * @param Interfaces\PreparedMethodInterface $query
     * @return mixed
     */
    public function request(Interfaces\PreparedMethodInterface $query)
    {
        return $this->elasticsearch->{$query->getMethod()}($query->build());
    }

    /**
     * @return Elasticsearch\Client
     */
    public function getClient()
    {
        return $this->elasticsearch;
    }
}
