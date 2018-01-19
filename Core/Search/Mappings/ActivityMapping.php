<?php

/**
 * Mapping for activity documents
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

class ActivityMapping extends EntityMapping implements MappingInterface
{
    /**
     * ActivityMapping constructor.
     */
    public function __construct()
    {
        $this->mappings = array_merge($this->mappings, [
            'rating' => [ 'type' => 'integer', '$exportField' => 'rating' ],
        ]);
    }
}
