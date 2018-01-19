<?php

/**
 * Mapping for image object documents
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

class ObjectImageMapping extends EntityMapping implements MappingInterface
{
    /**
     * ObjectImageMapping constructor.
     */
    public function __construct()
    {
        $this->mappings = array_merge($this->mappings, [
            'license' => [ 'type' => 'text', '$exportField' => 'license' ],
            'rating' => [ 'type' => 'integer', '$exportField' => 'rating' ],
        ]);
    }
}
