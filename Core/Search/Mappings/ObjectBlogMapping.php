<?php

/**
 * Mapping for blog object documents
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

use Minds\Core\Blogs\Blog;

class ObjectBlogMapping extends EntityMapping implements MappingInterface
{
    /** @var array $mappings */
    protected $mappings = [
        '@timestamp' => [ 'type' => 'date' ],
        'interactions' => [ 'type' => 'integer', '$exportGetter' => 'getInteractions' ],
        'guid' => [ 'type' => 'text', '$exportGetter' => 'getGuid' ],
        'type' => [ 'type' => 'text', '$exportGetter' => 'getType' ],
        'subtype' => [ 'type' => 'text', '$exportGetter' => 'getSubtype' ],
        'taxonomy' => [ 'type' => 'text' ],
        'time_created' => [ 'type' => 'integer', '$exportGetter' => 'getTimeCreated' ],
        'access_id' => [ 'type' => 'text', '$exportGetter' => 'getAccessId' ],
        'public' => [ 'type' => 'boolean' ],
        'owner_guid' => [ 'type' => 'text', '$exportGetter' => 'getOwnerGuid' ],
        'container_guid' => [ 'type' => 'text', '$exportGetter' => 'getContainerGuid' ],
        'mature' => [ 'type' => 'boolean', '$exportGetter' => 'isMature' ],
        'title' => [ 'type' => 'text', '$exportGetter' => 'getTitle' ],
        'description' => [ 'type' => 'text', '$exportGetter' => 'getBody' ],
        'paywall' => [ 'type' => 'boolean', '$exportGetter' => 'isPaywall' ],
        'tags' => [ 'type' => 'text' ],
        'license' => [ 'type' => 'text', '$exportGetter' => 'getLicense' ],
    ];

    /** @var Blog $entity */
    protected $entity;

    /**
     * Sets the entity to be mapped
     *
     * @param Blog $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Gets the search type for the map
     * @return string
     * @throws \Exception
     */
    public function getType()
    {
        if (!$this->entity) {
            throw new \Exception('Entity is required');
        }

        $type = $this->entity->getType() . ':' . $this->entity->getSubtype();

        return $type;
    }

    /**
     * Gets the search id for the map
     * @return string
     * @throws \Exception
     */
    public function getId()
    {
        if (!$this->entity) {
            throw new \Exception('Entity is required');
        }

        return (string) $this->entity->getGuid();
    }

    /**
     * Get the ES mappings
     * @return array
     */
    public function getMappings()
    {
        return $this->mappings;
    }

    /**
     * Performs the mapping procedure
     * @param array $defaultValues
     * @return array
     * @throws \Exception
     */
    public function map(array $defaultValues = [])
    {
        if (!$this->entity) {
            throw new \Exception('Entity is required');
        }

        // Auto populate based on $exportField
        $map = array_merge($defaultValues, $this->autoMap());

        // Basics (taxonomy and timestamp)

        $taxonomy = [ $this->entity->getType(), $this->entity->getSubtype() ];

        if ($this->entity->getTimeCreated()) {
            $map['@timestamp'] = $this->entity->getTimeCreated() * 1000;
        }

        $map['taxonomy'] = implode(':', $taxonomy);

        // Public

        $map['public'] = $this->entity->getAccessId() == ACCESS_PUBLIC;

        // Mature

        $map['mature'] = $this->entity->isMature();

        // Paywall

        $map['paywall'] = $this->entity->isPaywall();

        // Text

        if (isset($map['description'])) {
            $map['description'] = strip_tags($map['description']);
        }

        // Hashags (should be the last)

        $fullText = '';

        if (isset($map['title'])) {
            $fullText .= ' ' . $map['title'];
        }

        $htRe = '/(^|\s||)#(\w*[a-zA-Z_]+\w*)/';
        $matches = [];

        preg_match_all($htRe, $fullText, $matches);

        $tags = [];

        if (isset($matches[2]) && $matches[2]) {
            $tags = array_values(array_unique($matches[2]));
        }

        $map['tags'] = array_map('strtolower', $tags);

        //

        return $map;
    }

    /**
     * @param array $defaultValues
     * @return array
     */
    public function suggestMap(array $defaultValues = [])
    {
        return $defaultValues;
    }

    /**
     * @return array
     */
    protected function autoMap()
    {
        $map = [];

        foreach ($this->mappings as $key => $mapping) {
            if (!is_array($mapping) || !isset($mapping['$exportGetter'])) {
                continue;
            }

            $getter = $mapping['$exportGetter'];
            $type = isset($mapping['type']) ? $mapping['type'] : 'text';

            switch ($type) {
                case 'text':
                    $map[$key] = (string) $this->entity->{$getter}();
                    break;
                case 'boolean':
                    $map[$key] = !!$this->entity->{$getter}();
                    break;
                case 'integer':
                    $map[$key] = (int) $this->entity->{$getter}();
                    break;
                default:
                    $map[$key] = $this->entity->{$getter}();
            }
        }

        return $map;
    }
}
