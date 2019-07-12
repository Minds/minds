<?php

/**
 * Mapping for group documents
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

class GroupMapping extends EntityMapping implements MappingInterface
{
    /**
     * GroupMapping constructor.
     */
    public function __construct()
    {
        $this->mappings = array_merge($this->mappings, [
            'brief_description' => [ 'type' => 'text', '$exportField' => 'brief_description' ],
            'membership' => [ 'type' => 'integer', '$exportField' => 'membership' ],
        ]);
    }

    public function map(array $defaultValues = [])
    {
        $map = parent::map($defaultValues);

        $map['name'] = (string) $this->entity->getName();
        $map['brief_description'] = (string) $this->entity->getBriefDescription();

        $map = parent::map($map);

        $map['membership'] = (int) $this->entity->getMembership();
        $map['public'] = $map['membership'] == ACCESS_PUBLIC;

        $map['tags'] = array_values(array_unique(array_merge($map['tags'], $this->entity->getTags())));
        $map['rating'] = $this->entity->getRating();
        
        return $map;
    }
}
