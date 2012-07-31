<?php

function minds_view_comments_list($entity, $params) {
    $container_guid = elgg_extract('container_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);
    $annotation_name = elgg_extract('aname', $params, 'generic_comment');

    $options = array(
        'type' => 'object',
        'subtype' => 'comment',
        'owner_guid' => null,
        'container_guid' => $container_guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'annotation_name', 'value' => $annotation_name),
            array('name' => 'annotation_value', 'value' => '', 'operand' => '!='),
            array('name' => 'river_id', 'value' => $river_id)
        ),
        'count' => false,
        'limit' => 3,
        'order_by' => 'e.time_created desc'
    );

    $options['count'] = true;
    $count = elgg_get_entities_from_metadata($options);

    if (elgg_instanceof($entity, 'object', 'comment')) {
        $options['limit'] = 0;
    } else {
        $options['count'] = false;
        $comments = elgg_get_entities_from_metadata($options);
    }

    if ($annotation_name == 'generic_comment') {
        unset($params['aname']);
    }
    foreach ($params as $key => $option) {
        if ($option) {
            $data[$key] = $option;
        }
    }
    $vars['data-options'] = htmlentities(json_encode($data), ENT_QUOTES, 'UTF-8');
    $vars['sync'] = true;
    $vars['pagination'] = false;
    $vars['list_class'] = 'hj-comments';

    $vars['class'] = "elgg-comment-list-$annotation_name";

    $visible = elgg_view_entity_list($comments, $vars);

    $limit = elgg_extract('limit', $options, 0);
    if ($count > 0 && $count > $limit) {
        $remainder = $count - $limit;
        if ($limit > 0) {
            $summary = elgg_echo('hj:alive:comments:remainder', array($remainder));
        } else {
            $summary = elgg_echo('hj:alive:comments:viewall', array($remainder));
        }
    }

    return elgg_view('minds/comments/list', array(
                'summary' => $summary,
                'visible' => $visible,
                'hidden' => $hidden
            ));
}

/* Likes 
 */
 function minds_view_likes_list($params) {
    $container_guid = elgg_extract('container_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);

    $count = minds_get_likes($params, true);

    if ($count > 0) {
        $likes = minds_get_likes($params, false);

        $text_owner = elgg_echo('hj:alive:comments:lang:you');
        $text_and = elgg_echo('hj:alive:comments:lang:and');
        $text_others = elgg_echo('hj:alive:comments:lang:others');
        $text_others_one = elgg_echo('hj:alive:comments:lang:othersone');
        $text_people = elgg_echo('hj:alive:comments:lang:people');
        $text_people_one = elgg_echo('hj:alive:comments:lang:peopleone');
        $text_likethis = elgg_echo('hj:alive:comments:lang:likethis');
        $text_likesthis = elgg_echo('hj:alive:comments:lang:likesthis');

        $user = elgg_get_logged_in_user_entity();

        foreach ($likes as $like) {
            $owners[] = $like->owner_guid;
        }
        $owners = array_unique($owners);
        if (in_array($user->guid, $owners)) {
            if (sizeof($owners) > 1) {
                $key = array_search($user->guid, $owners);
                unset($owners[$key]);
            } else {
                unset($owners[0]);
            }
            $str_owner = $text_owner;
        }
        if (sizeof($owners) > 0) {
            $others = sizeof($owners);
            foreach ($owners as $guid) {
                $owner = get_entity($guid);
                $str[] = '<span class="likes_names">' . elgg_view('output/url', array('href' => $owner->getURL(), 'text' => $owner->name)) . '</span>';
            }
            $likes_long = htmlentities(implode(', ', $str), ENT_QUOTES, 'UTF-8');
        }

        if ($likes_long) {
            $link_pre = '<a href="javascript:void(0)" class="hj-swap-value" data-options="' . $likes_long . '">';
        }
        $link_post = '</a>';
        if (!empty($str_owner) && $others == 0) {
            $string = $str_owner . $text_likethis;
        } else if (!empty($str_owner) && $others == 1) {
            $likes_short = "$link_pre $others $text_others_one $link_post";
            $string = "$str_owner $text_and $likes_short $text_likethis";
        } else if (!empty($str_owner) && $others > 1) {
            $likes_short = "$link_pre $others $text_others $link_post";
            $string = "$str_owner $text_and $likes_short $text_likethis";
        } else if (empty($prefix) && $others == 1) {
            $likes_short = "$link_pre $others $text_people_one $link_post";
            $string = "$likes_short $text_likesthis";
        } else if (empty($prefix) && $others > 1) {
            $likes_short = "$link_pre $others $text_people $link_post";
            $string = "$likes_short $text_likethis";
        }
    }
    if (!$container_guid)
        unset($params['container_guid']);
    if (!$river_id)
        unset($params['river_id']);

    return elgg_view('minds/likes/list', array('value' => $string, 'count' => $count, 'params' => $params));
}

function minds_get_likes($params, $count = false) {
    $container_guid = elgg_extract('container_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);
    $options = array(
        'type' => 'object',
        'subtype' => 'comment',
        'owner_guid' => null,
        'container_guid' => $container_guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'annotation_name', 'value' => 'likes'),
            array('name' => 'annotation_value', 'value' => '1'),
            array('name' => 'river_id', 'value' => $river_id)
        ),
        'count' => $count,
        'limit' => 0
    );

    return $likes = elgg_get_entities_from_metadata($options);
}

function minds_does_user_like($params) {
    $container_guid = elgg_extract('container_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);
    $owner_guid = elgg_get_logged_in_user_guid();

    $options = array(
        'type' => 'object',
        'subtype' => 'comment',
        'owner_guid' => $owner_guid,
        'container_guid' => $container_guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'annotation_name', 'value' => 'likes'),
            array('name' => 'annotation_value', 'value' => '1'),
            array('name' => 'river_id', 'value' => $river_id)
        ),
        'count' => false,
        'limit' => 0
    );

    $likes = elgg_get_entities_from_metadata($options);

    if ($likes && sizeof($likes) > 0) {
        return array('self' => true, 'likes' => $likes);
    }
    return false;
}