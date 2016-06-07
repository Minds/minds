<?php
/**
 * Defacto entities controller for minds
 */
namespace Minds\Core;

use Minds\Core\data;

class Entities extends base
{
    public function init()
    {
    }


    public static function get(array $options = array())
    {
        return \elgg_get_entities($options);
    }

    public static function view($options)
    {
        //	$options['count'] = NULL;
        return \elgg_list_entities($options);
    }

    /**
     * Builds an entity object, based on the row
     *
     * @param mixed $row
     * @param bool $cache - cache or load from cache?
     * @return object
     */
    public static function build($row, $cache = true)
    {
        if (is_array($row)) {
            $row = (object) $row;
        }

        if (!is_object($row)) {
            return $row;
        }

        if (!isset($row->guid)) {
            return $row;
        }

        //plugins should, strictly speaking, handle the routing of entities by themselves..
        if (($new_entity = Events\Dispatcher::trigger('entities:map', 'all', [ 'row' => $row ]))
          || $new_entity = elgg_trigger_plugin_hook('entities_class_loader', 'all', $row)) {
            return $new_entity;
        }

        if (isset($row->subtype) && $row->subtype) {
            $sub = "Minds\\Entities\\" . ucfirst($row->type) . "\\" . ucfirst($row->subtype);
            if (class_exists($sub) && is_subclass_of($sub, 'ElggEntity')) {
                return new $sub($row, $cache);
            } elseif (is_subclass_of($sub, "Minds\\Entities\\DenormalizedEntity")) {
                return (new $sub())->loadFromArray((array) $row);
            }
        }

        $default = "Minds\\Entities\\" . ucfirst($row->type);
        if (class_exists($default) && is_subclass_of($default, 'ElggEntity')) {
            return new $default($row, $cache);
        } elseif (is_subclass_of($default, "Minds\\Entities\\DenormalizedEntity")) {
            return (new $default())->loadFromArray((array) $row);
        }
    }
    
    public static function buildNamespace(array $options)
    {
        $options = $options + [
            'type' => null,
            'subtype' => null,
            'owner_guid' => null,
            'container_guid' => null,
            'network' => null,
        ];
        $namespace = $options['type'] ?: 'object';
        
        if ($options['subtype']) {
            $namespace .= ':' . $option['subtype'];
        }
        
        if ($options['owner_guid']) {
            $namespace .= ':user:' . $options['owner_guid'];
        }
        
        if ($options['container_guid']) {
            $namespace .= ':container:' . $options['container_guid'];
        }
        if ($options['network']) {
            $namespace .= ':network:' . $options['network'];
        }
        
        return $namespace;
    }
}
