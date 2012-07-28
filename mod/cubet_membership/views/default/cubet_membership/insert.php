<?php
   /**
    * Elgg Membership plugin
    * Membership Save page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php");

    $usertype=$_REQUEST['val_param'];

     echo $usertype;
     $entity = new ElggObject();
     $entity->title =  $usertype;
     $entity->description = "";
     $entity->subtype = "membership";
     $entity->access_id = ACCESS_PUBLIC;
     $entity->owner_guid = get_loggedin_userid();

     //save to database
     $entity->save();
?>
