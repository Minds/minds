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

    public function __construct(
        $repository = null
    )
    {
        $this->repository = $repository ?: new Repository();
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
}
