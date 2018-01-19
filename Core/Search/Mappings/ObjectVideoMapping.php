<?php

/**
 * Mapping for video object documents
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

class ObjectVideoMapping extends EntityMapping implements MappingInterface
{
    /**
     * ObjectVideoMapping constructor.
     */
    public function __construct()
    {
        $this->mappings = array_merge($this->mappings, [
            'license' => [ 'type' => 'text', '$exportField' => 'license' ],
            'rating' => [ 'type' => 'integer', '$exportField' => 'rating' ],
        ]);
    }
}
