<?php
/**
 * Suggestions model
 */
namespace Minds\Core\Suggestions;

use Minds\Traits\MagicAttributes;

class Suggestion
{
    use MagicAttributes;

    /** @var int $entityGuid */
    protected $entityGuid;

    /** @var int $entityType */
    protected $entityType;

    /** @var Entity $entity */
    protected $entity;

    /** @var int $confidenceScore */
    protected $confidenceScore;

    /**
     * Export
     * @return array
     */
    public function export($fields = [])
    {
        return [
            'entity_guid' => $this->entityGuid,
            'entity_type' => $this->entityType,
            'entity' => $this->entity ? $this->entity->export() : null,
            'confidence_score' => $this->confidenceScore,
        ];
    }

}
