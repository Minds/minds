<?php

namespace Minds\Core\Analytics\Graphs;

use Minds\Core\Di\Di;

class Manager
{
    /** @var Repository */
    protected $repository;
    /** @var Mappings */
    protected $mappings;

    public function __construct($repository = null, $mappings = null)
    {
        $this->repository = $repository ?: Di::_()->get('Analytics\Graphs\Repository');
        $this->mappings = $mappings ?: new Mappings();
    }

    /**
     * @param string $urn
     * @return Graph[]|null
     * @throws \Exception
     */
    public function get($urn)
    {
        return $this->repository->get($urn);
    }

    public function add(Graph $metric)
    {
        return $this->repository->add($metric);
    }

    /**
     * @param array $opts
     * @return void
     * @throws \Exception
     */
    public function sync(array $opts = [])
    {
        $opts = array_merge([
            'aggregate' => null,
            'all' => false,
            'span' => 12,
            'unit' => 'month', // day / month
            'key' => null,
        ], $opts);

        $aggregate = $this->getAggregate($opts['aggregate']);

        if ($opts['all']) {
            $response = $aggregate->fetchAll();
        } else {
            $response = [
                static::buildKey($opts) => $aggregate->fetch($opts),
            ];
        }

        foreach ($response as $key => $data) {
            $graph = (new Graph())
                ->setLastSynced(time())
                ->setKey($key)
                ->setData($data);

            $this->add($graph);
        }

    }

    /**
     * @param string $report
     * @return \Minds\Core\Analytics\Graphs\Interfaces\Graph
     * @throws \Exception
     */
    private function getAggregate(string $report)
    {
        $aggregate = $this->mappings->getMapping($report);

        if (!$aggregate) {
            throw new \Exception('Aggregate not found');
        }

        return $aggregate;
    }

    /**
     * Returns the key to save the report as
     * @param array $opts
     * @return string
     */
    public static function buildKey($opts)
    {
        return implode('-', [
            $opts['aggregate'],
            $opts['key'],
            $opts['unit'],
        ]);
    }

}
