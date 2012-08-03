<?php

function hj_alive_view_likes_list($params) {
    $container_guid = elgg_extract('container_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);

    $count = hj_alive_get_likes($params, true);

    if ($count > 0) {
        $likes = hj_alive_get_likes($params, false);

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

    return elgg_view('hj/likes/list', array('value' => $string, 'count' => $count, 'params' => $params));
}

function hj_alive_get_likes($params, $count = false) {
    $container_guid = elgg_extract('container_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);
    $options = array(
        'type' => 'object',
        'subtype' => 'hjannotation',
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

function hj_alive_does_user_like($params) {
    $container_guid = elgg_extract('container_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);
    $owner_guid = elgg_get_logged_in_user_guid();

    $options = array(
        'type' => 'object',
        'subtype' => 'hjannotation',
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

function hj_alive_does_user_dislike($params) {
    $container_guid = elgg_extract('container_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);
    $owner_guid = elgg_get_logged_in_user_guid();

    $options = array(
        'type' => 'object',
        'subtype' => 'hjannotation',
        'owner_guid' => $owner_guid,
        'container_guid' => $container_guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'annotation_name', 'value' => 'dislikes'),
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