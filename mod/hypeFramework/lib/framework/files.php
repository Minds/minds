<?php
/**
 * hjFile helper functions
 */

/**
 * Get an array of hjFileFolder for a particular user in a given container
 *
 * @param ElggUser $user
 * @param ElggEntity $container_guid
 * @return type
 */
function hj_framework_get_user_file_folders($format = 'options_array', $owner_guid = NULL, $container_guid = NULL, $limit = 0) {
    if (!$owner_guid && elgg_is_logged_in()) {
        $owner_guid = elgg_get_logged_in_user_entity()->guid;
    } else {
        return true;
    }

    $filefolders = hj_framework_get_entities_by_priority('object', 'hjfilefolder', $owner_guid, $container_guid, $limit);
    switch ($format) {
        case 'options_array' :
            if (is_array($filefolders)) {
                $result[] = elgg_echo("hj:framework:newfolder");
                foreach ($filefolders as $filefolder) {
                    $result[$filefolder->getGUID()] = $filefolder->title;
                }
            }
            break;

        case 'entities_array' :
            $result = $filefolders;
            break;
    }
    return $result;
}

function hj_framework_allow_file_download($file_guid) {
    return elgg_trigger_plugin_hook('hj:framework:allowdownload', 'all', array('file_guid' => $file_guid), true);
}