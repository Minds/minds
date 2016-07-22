<?php
/**
 * Lists of guids/entities of thumbed content
 */
 
namespace minds\plugin\thumbs\helpers;

use Minds\Core\Data;
use Minds\Core\entities;

class lists
{

    /*
     * Return a list of guids that a user has thumbed
     */
    public static function getUserThumbsGuids($user, $type = null, $params = array())
    {
        $params = array_merge(array('limit'=>3, 'offset' => '', 'reversed'=>true), $params);
        if ($type) {
            $guids = Data\indexes::fetch("thumbs:up:user:$user->guid:$type", $params);
        } else {
            $guids = Data\indexes::fetch("thumbs:up:user:$user->guid", $params);
        }

        if ($guids) {
            return array_keys($guids);
        }
        return;
    }
    
    /**
     * Return entitis that a user has thumbs
     */
    public static function getUserThumbs($user, $type = null, $options = array())
    {
        $guids = self::getUserThumbsGuids($user, $type, $options);
        
        if ($guids) {
            return Entities::get(array('guids'=>$guids));
        }
        
        return false;
    }
}
