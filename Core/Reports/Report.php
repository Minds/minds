<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports;

use Minds\Core\Reports\UserReport;
use Minds\Traits\MagicAttributes;

class ReportedEntity
{
    use MagicAttributes;

    /** @var date $timestamp */
    private $timestamp;

    /** @var long $guid */
    private $guid;

    /** @var long $entityGuid  */
    private $entityGuid;

    /** @var Entity $entity  */
    private $entity;

    /** @var string $state */
    private $state;

    /** @var array<EntityAction> $entityActions */
    private $entityActions;

    /** @var int $reportCount  */
    private $reportCount;

    /** @var array<UserReport> $userReports */
    private $userReports;

    public function export()
    {
        $export = [
            'guid' => $this->guid,
            'time_created' => $this->timestamp,
            'entity_guid' => $this->entityGuid,
            'entity' => $this->entity->export(),
            'state' => $this->state,
        ];

        return $export;
    }

}
