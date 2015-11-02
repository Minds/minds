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
        if ($new_entity = elgg_trigger_plugin_hook('entities_class_loader', 'all', $row)) {
            return $new_entity;
        }

        if (isset($row->subtype) && $row->subtype) {
            $sub = "Minds\\Entities\\" . ucfirst($row->type) . "\\" . ucfirst($row->subtype);
            if (class_exists($sub)) {
                return new $sub($row, $cache);
            }
        }

        $default = "Minds\\Entities\\" . ucfirst($row->type);
        if (class_exists($default)) {
            return new $default($row, $cache);
        }
    }
}
