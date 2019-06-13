<?php
/**
 * Manager
 * @author edgebal
 */

namespace Minds\Core\Analytics\Views;

use Exception;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var ElasticRepository */
    protected $elasticRepository;

    public function __construct(
        $repository = null,
        $elasticRepository = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->elasticRepository = $elasticRepository ?: new ElasticRepository();
    }

    /**
     * @param View $view
     * @return bool
     * @throws Exception
     */
    public function record(View $view)
    {
        // Reset time fields and use current timestamp
        $view
            ->setYear(null)
            ->setMonth(null)
            ->setDay(null)
            ->setUuid(null)
            ->setTimestamp(time());

        // Add to repository
        $this->repository->add($view);

        return true;
    }

    /**
     * Synchronise views from cassandra to elastic
     * @param int $from
     * @param int $to
     * @return void
     */
    public function syncToElastic($opts = [])
    {
        $opts = array_merge([
            'from' => null,
            'to' => $to,
            'day' => 5,
            'month' => 6,
            'year' => 2019,
            'limit' => 1000,
            'offset' => '',
        ], $opts);
        
        while (true) {
            $result = $this->repository->getList($opts);

            $opts['offset'] = $result->getPagingToken();

            foreach ($result as $view) {
                $this->elasticRepository->add($view);
                yield $view;
            }

            if ($result->isLastPage()) {
                break;
            }
        }
        $this->elasticRepository->bulk(); // Save the final batch
    }

}
