<?php
/**
 * Reported Entity
 */
namespace Minds\Core\Reports;

use Minds\Core\Reports\UserReport;
use Minds\Traits\MagicAttributes;

/**
 * @method Report getEntityGuid(): long
 * @method Report getReporterGuid(): long
 * @method Report getReasonCode(): int
 * @method Report getTimestamp: int
 */
class Report
{
    use MagicAttributes;

    /** @var long $timestamp -< in ms*/
    private $timestamp;

    /** @var long $reporterGuid */
    private $reporterGuid;

    /** @var long $entityGuid  */
    private $entityGuid;

    /** @var Entity $entity  */
    private $entity;

    /** @var int $reasonCode */
    private $reasonCode;

    /** @var int $subReasonCode */
    private $subReasonCode;

    /**
     * @return array
     */
    public function export()
    {
        $export = [
            'reporter_guid' => $this->reporterGuid,
            '@timestamp' => $this->timestamp,
            'entity_guid' => $this->entityGuid,
            'entity' => $this->entity ? $this->entity->export() : null,
            'reason_code' => $this->reasonCode,
            'sub_reason_code' => $this->subReasonCode,
        ];

        return $export;
    }

}
