<?php

// $search_type == all || entities || trigger plugin hook
$search_type = get_input('search_type', 'all');

// @todo there is a bug in get_input that makes variables have slashes sometimes.
// @todo is there an example query to demonstrate ^
// XSS protection is more important that searching for HTML.
$query = stripslashes(get_input('term', get_input('tag', '')));

// @todo - create function for sanitization of strings for display in 1.8
// encode <,>,&, quotes and characters above 127
$display_query = mb_convert_encoding($query, 'HTML-ENTITIES', 'UTF-8');
$display_query = htmlspecialchars($display_query, ENT_QUOTES, 'UTF-8', false);

// get limit and offset.  override if on search dashboard, where only 2
// of each most recent entity types will be shown.
$limit = ($search_type == 'all') ? 2 : get_input('limit', 10);
$offset = ($search_type == 'all') ? 0 : get_input('offset', 0);

$entity_type = get_input('entity_type', ELGG_ENTITIES_ANY_VALUE);
$entity_subtype = get_input('entity_subtype', ELGG_ENTITIES_ANY_VALUE);
$owner_guid = get_input('owner_guid', ELGG_ENTITIES_ANY_VALUE);
$container_guid = get_input('container_guid', ELGG_ENTITIES_ANY_VALUE);
$friends = get_input('friends', ELGG_ENTITIES_ANY_VALUE);
$sort = get_input('sort');
switch ($sort) {
    case 'relevance':
    case 'created':
    case 'updated':
    case 'action_on':
    case 'alpha':
        break;

    default:
        $sort = 'relevance';
        break;
}

$order = get_input('sort', 'desc');
if ($order != 'asc' && $order != 'desc') {
    $order = 'desc';
}

// set up search params
$params = array(
    'query' => $query,
    'offset' => $offset,
    'limit' => $limit,
    'sort' => $sort,
    'order' => $order,
    'search_type' => $search_type,
    'type' => $entity_type,
    'subtype' => $entity_subtype,
//	'tag_type' => $tag_type,
    'owner_guid' => $owner_guid,
    'container_guid' => $container_guid,
//	'friends' => $friends
    'pagination' => ($search_type == 'all') ? FALSE : TRUE
);

$types = get_registered_entity_types();
$custom_types = elgg_trigger_plugin_hook('search_types', 'get_types', $params, array());

// start the actual search
$results_html = '';

if ($search_type == 'all' || $search_type == 'entities') {
    // to pass the correct current search type to the views
    $current_params = $params;
    $current_params['search_type'] = 'entities';

    // foreach through types.
    // if a plugin returns FALSE for subtype ignore it.
    // if a plugin returns NULL or '' for subtype, pass to generic type search function.
    // if still NULL or '' or empty(array()) no results found. (== don't show??)
    foreach ($types as $type => $subtypes) {
        if ($search_type != 'all' && $entity_type != $type) {
            continue;
        }

        if (is_array($subtypes) && count($subtypes)) {
            foreach ($subtypes as $subtype) {
                // no need to search if we're not interested in these results
                // @todo when using index table, allow search to get full count.
                if ($search_type != 'all' && $entity_subtype != $subtype) {
                    continue;
                }
                $current_params['subtype'] = $subtype;
                $current_params['type'] = $type;

                $results = elgg_trigger_plugin_hook('search', "$type:$subtype", $current_params, NULL);
                if ($results === FALSE) {
                    // someone is saying not to display these types in searches.
                    continue;
                } elseif (is_array($results) && !count($results)) {
                    // no results, but results searched in hook.
                } elseif (!$results) {
                    // no results and not hooked.  use default type search.
                    // don't change the params here, since it's really a different subtype.
                    // Will be passed to elgg_get_entities().
                    $results = elgg_trigger_plugin_hook('search', $type, $current_params, array());
                }

                if (is_array($results['entities']) && $results['count']) {
                    $results_html[] = array('label' => elgg_view('livesearch/list', array(
                        'results' => $results,
                        'params' => $current_params,
                            )));
                }
            }
        }

        // pull in default type entities with no subtypes
        $current_params['type'] = $type;
        $current_params['subtype'] = ELGG_ENTITIES_NO_VALUE;

        $results = elgg_trigger_plugin_hook('search', $type, $current_params, array());
        if ($results === FALSE) {
            // someone is saying not to display these types in searches.
            continue;
        }

        if (is_array($results['entities']) && $results['count']) {
            $results_html[] = array('label' => elgg_view('livesearch/list', array(
                'results' => $results,
                'params' => $current_params,
                    )));
        }
    }
}

// call custom searches
if ($search_type != 'entities' || $search_type == 'all') {
    if (is_array($custom_types)) {
        foreach ($custom_types as $type) {
            if ($search_type != 'all' && $search_type != $type) {
                continue;
            }

            $current_params = $params;
            $current_params['search_type'] = $type;
            // custom search types have no subtype.
            unset($current_params['subtype']);

            $results = elgg_trigger_plugin_hook('search', $type, $current_params, array());

            if ($results === FALSE) {
                // someone is saying not to display these types in searches.
                continue;
            }

            if (is_array($results['entities']) && $results['count']) {
                $results_html[] = array('label' => elgg_view('livesearch/list', array(
                    'results' => $results,
                    'params' => $current_params,
                        )));
            }
        }
    }
}

print json_encode($results_html);
return true;