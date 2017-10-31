<?php

/**
 * Mapping for blog object documents
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

class ObjectBlogMapping extends EntityMapping implements MappingInterface
{
    /**
     * ObjectBlogMapping constructor.
     */
    public function __construct()
    {
        $this->mappings = array_merge($this->mappings, [
            'license' => [ 'type' => 'text', '$exportField' => 'license' ]
        ]);
    }
}
