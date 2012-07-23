<?php
error_reporting(E_ALL ^ E_NOTICE);
// switch to local development mode
define ("DEVELOPMENT", false);

if (!class_exists ("fileAttachment")) {
  require_once("htmlMimeMail5/htmlMimeMail5.php");
  // newer version is here
  // http://www.phpguru.org/static/Rmail
}
if (!isset($embedded)) {
  require_once(dirname(__FILE__) . "/../../../../engine/start.php");
}
define ("ADMIN_ERROR", elgg_echo('ElggMan_:adminError'));
define ("MAXENTRIES", 9999);

$adminOnlyOption = westorElggMan_get_plugin_setting('adminOnlyOption', 'westorElggMan');
$admin = westorElggMan_isAdmin($_SESSION['user']);

if ($adminOnlyOption == 'yes' && !$admin) {
  define ("SESSION_ERROR", ADMIN_ERROR);
} else {
  define ("SESSION_ERROR", elgg_echo('ElggMan_:sessionError'));
}

function dbcallElggMan($sql)
{
  mysql_select_db($CONFIG->dbname);
  $result = mysql_query($sql);
  if (!$result) {
    error_log("SQL: $sql [Fehler Nr: " . mysql_errno() . "] in Script: " . $_SERVER["SCRIPT_NAME"]);
  }
  return $result;
}

class WestorObject extends ElggObject {
  function __construct() {
    parent::__construct();
  }

  public function changeOwner($newOwnerGuid) {
    global $CONFIG;
    $prefix = $CONFIG->dbprefix;

    $this->owner_guid = $newOwnerGuid;
    $this->container_guid = $newOwnerGuid;

    $this->save();
    $sql = "UPDATE {$prefix}metadata set owner_guid = $newOwnerGuid WHERE entity_guid = {$this->guid}";
    try {
      $result = get_data($sql);
    } catch (Exception $e) {
      throw new Exception($e);
    }
  }
}

class class_elggMan {
  /**
   *
   * @param params $ An array containing the parameters to this method
   *             $params[0]
   *               Array containing the components of the path from which directory
   *               entries are to be read
   *
   *             $params[1]
   *               Boolean indicating whether attribute details are desired.
   *                 true  : obtain and return details
   *                 false : no details needed
   * @param error $ An object of class JsonRpcError.
   * @return
   */

  private $varColumns;
  private $varColumnsUser;

  function __construct()
  {

    // valid columns
    $columns = array(
      "username" => elgg_echo("ElggMan_:cUserName"),
      "email" => elgg_echo("ElggMan_:cEmail"),
      "mobile" => elgg_echo("ElggMan_:cMobile"),
      "time_created" => elgg_echo("ElggMan_:cSince"),
      "last_login" => elgg_echo("ElggMan_:cLastLogin"),
      "last_action" => elgg_echo("ElggMan_:cLastAction"),
      "location" => elgg_echo("ElggMan_:cLocation")
    );

    // columns initial for admin
    $columnsAdmin = array(
      "username" => elgg_echo("ElggMan_:cUserName"),
      "email" => elgg_echo("ElggMan_:cEmail"),
    //    "mobile" => elgg_echo("ElggMan_:cMobile"),
      "time_created" => elgg_echo("ElggMan_:cSince"),
      "last_login" => elgg_echo("ElggMan_:cLastLogin"),
    //    "last_action" => elgg_echo("ElggMan_:cLastAction"),
    //    "location" => elgg_echo("ElggMan_:cLocation")
    );

    // columns initial for user
    $columnsUser = array(
    //    "username" => elgg_echo("ElggMan_:cUserName"),
    //    "email" => elgg_echo("ElggMan_:cEmail"),
    //    "mobile" => elgg_echo("ElggMan_:cMobile"),
      "time_created" => elgg_echo("ElggMan_:cSince"),
      "last_login" => elgg_echo("ElggMan_:cLastLogin"),
    //    "last_action" => elgg_echo("ElggMan_:cLastAction"),
      "location" => elgg_echo("ElggMan_:cLocation")
    );

    $varColumns = array();

    if ($this->is_admin()) {
      foreach($columns as $columnname => $columntext){
        $settingName = "varColumnsUser_$columnname";
        $setting = westorElggMan_get_plugin_setting($settingName, 'westorElggMan');
        if ($setting) {
          if ($setting == "on") {
            $varColumns[$columnname] = $columntext;
          }
        } else {
          if ($columnsUser[$columnname]) {
            $varColumns[$columnname] = $columntext;
          }
        }
      }
      $this->varColumnsUser = $varColumns;

      $varColumns = array();
      foreach($columns as $columnname => $columntext){
        $settingName = "varColumnsAdmin_$columnname";
        $setting = westorElggMan_get_plugin_setting($settingName, 'westorElggMan');
        if ($setting) {
          if ($setting == "on") {
            $varColumns[$columnname] = $columntext;
          }
        } else {
          if ($columnsAdmin[$columnname]) {
            $varColumns[$columnname] = $columntext;
          }
        }
      }
    } else {
      foreach($columns as $columnname => $columntext){
        $settingName = "varColumnsUser_$columnname";
        $setting = westorElggMan_get_plugin_setting($settingName, 'westorElggMan');
        if ($setting) {
          if ($setting == "on") {
            $varColumns[$columnname] = $columntext;
          }
        } else {
          if ($columnsUser[$columnname]) {
            $varColumns[$columnname] = $columntext;
          }
        }
      }
    }
    $this->varColumns = $varColumns;
  }

  private function createAnswerStr($msg, $description=null) {
    $retmsg = "";
    if (count($msg["yes"])) {
      $retmsg = "Sucessfully done: " . implode(', ' , $msg["yes"]);
    }
    if (count($msg["no"])) {
      if ($retmsg)
      $retmsg .= "\n";
      $retmsg .= "Not succesfully done: " . implode(', ' , $msg["no"]);
      $retmsg .= "\n";
    }
    return($retmsg);
  }

  private function is_loggedin()
  {
    // check general permission to this plugin
    $adminOnlyOption = westorElggMan_get_plugin_setting('adminOnlyOption', 'westorElggMan');
    if ($adminOnlyOption == 'yes' && ! $this->is_admin()) {
      return(false);
    }
    if (!is_object($_SESSION['user'])) {
      return(false);
    }

    return(true);
  }

  private function is_admin()
  {
  	return westorElggMan_isAdmin($_SESSION['user']);
  }

  private function getVarColumns()
  {
    return($this->varColumns);
  }

  private function getVarColumnsUser()
  {
    return($this->varColumnsUser);
  }

  private function getEntityProperty($entity, $property) {
    global $CONFIG;
    switch($property){
      case 'userIcon':
        if ($entity->getSubtype() == "PrivateContact") {
          //          $value = $CONFIG->url . "mod/westorElggMan/graphics/contact.png";
          $value = "../graphics/contact.png";
        } else {
          $value = westorElggMan_getIcon($entity,'topbar');
          // deliver own default graphics for shorter url
          if (strpos($value, 'defaulttopbar.gif') || !$value) {
            $value = "../graphics/d.gif";
          }
        }
        //          $value = $entity->getIcon('topbar')
        //            ? $entity->getIcon('topbar') : $CONFIG->url . "mod/westorElggMan/graphics/inactiveuser.png";
        break;
      case 'smsIcon':
        if ($this->getEntityProperty($entity, elgg_echo('ElggMan_:cMobile')) != '') {
          $value = "../graphics/mobil.png";
        } else {
          $value = "";
        }
        break;
      case elgg_echo('ElggMan:displayname'):
        if ($entity->getSubtype() == "PrivateContact") {
          $value = $entity->user_last_name ? $entity->user_last_name : '';
        } else {
          $value = $entity->display_name ? $entity->display_name : ($entity->name ? $entity->name : '');
        }
        break;
      case elgg_echo('ElggMan_:cUserName'):
        $value = $entity->username ? $entity->username : '';
        break;
      case elgg_echo('ElggMan_:cEmail'):
        if ($entity->getSubtype() == "PrivateContact") {
          $value = strtoupper($entity->user_email) != 'NULL' ? $entity->user_email : '';
        } else {
          $value = $entity->contactemail ? $entity->contactemail : ($entity->email ? $entity->email : '');
        }
        break;
      case elgg_echo('ElggMan_:cMobile'):
        if ($entity->getSubtype() == "PrivateContact") {
          $value = ($entity->mobil && strtoupper($entity->mobil) != 'NULL') ? $entity->mobil : '';
        } else {
          $value = get_metadata_byname ($entity->getGUID(),
                      'mobile'
                      )->value ? get_metadata_byname ($entity->getGUID(),
                      'mobile'
                      )->value : '';
        }
        break;
      case elgg_echo('ElggMan_:cSince'):
        $value = $entity->time_created ? substr (date("c", $entity->time_created), 0, 10) : '';
        break;
      case elgg_echo('ElggMan_:cLastAction'):
        $value = $entity->last_action ? substr (date("c", $entity->last_action), 0, 10) : '';
        break;
      case elgg_echo('ElggMan_:cLastLogin'):
        $value = $entity->last_login ? substr (date("c", $entity->last_login), 0, 10) : '';
        break;
      case elgg_echo('ElggMan_:cLocation'):
        $location = $entity->location;
        if (is_array($location)) {
          $location = implode(', ', $location);
        }
        $value = $location ? $location  : '';
        break;
      default:
        ;
    } // switch
    return($value);
  }

  function method_getPollingData($params, $error)
  {
    global $CONFIG;
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    if (! function_exists("count_unread_messages")) {
      $result = array("err" => "messaging plugin seems to be not installed.");
      return($result);
    }
    //    // neue E-Mails,
    $num_messages = westorElggMan_count_unread_messages();
    if($num_messages){
      $num = $num_messages;
    } else {
      $num = 0;
    }

    // TODO: neue Anfragen
    $result = array("msg" => $num);
    return($result);
  }

  function method_setMessageStatus($params, $error)
  {
    global $CONFIG;
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    $result = array();
    $message = westorElggMan_get_entity($params[0]);
    if ($params[1] == "read") {
      $message->readYet = 1;
      $message->save();
      $result["status"] = "read";
    }

    return($result);
  }


  function method_getAllColumns($params, $error)
  {
    $colfix = array('guid' => '', 'dummy' => '', 'image' => '', 'name' => elgg_echo("ElggMan:displayname"));
    if ($params[0] == "val") {
      if ($params[1] == elgg_echo('ElggMan:rb:view:user')) {
        $allColumns = array_merge(array_values($colfix),array_values($this->getVarColumnsUser()));
      } else {
        $allColumns = array_merge(array_values($colfix),array_values($this->getVarColumns()));
      }
    } else {
      if ($params[1] == elgg_echo('ElggMan:rb:view:user')) {
        $allColumns = array_merge($colfix, $this->getVarColumnsUser());
      } else {
        $allColumns = array_merge($colfix, $this->getVarColumns());
      }
    }
    return(array("data" => $allColumns, "method" => 'getAllColumns'));
  }

  function method_getObject($params, $error)
  {
    global $is_admin;
    $is_admin = true;
    if (function_exists("elgg_set_ignore_access")) {
      // new function for access overwrite
      elgg_set_ignore_access(true);
    }

    $guid = (int) $params[0];
    if ($guid) {
      $object = westorElggMan_get_entity($guid);
      if (is_object($object)) {
        $data = array();
//        $metaData = array();
//        $i=0;
        $owner = westorElggMan_get_entity($object->owner_guid);
        foreach ($object as $key => $val) {
//          $data[$i] = array('<b>' . $key . '</b>', $val);
          switch($key){
            case 'subtype':
              if ($val) {
                $metaData = $object->getSubtype();
              } else {
                $metaData = '';
              }
              break;
            case 'time_created':
              $metaData = substr (date("c", $object->time_created), 0, 10);
              break;
            case 'time_updated':
              $metaData = substr (date("c", $object->time_updated), 0, 10);
              break;
            case 'last_action':
              $metaData = substr (date("c", $object->last_action), 0, 10);
              break;
            case 'prev_last_action':
              $metaData = substr (date("c", $object->prev_last_action), 0, 10);
              break;
            case 'last_login':
              $metaData = substr (date("c", $object->last_login), 0, 10);
              break;
            case 'prev_last_login':
              $metaData = substr (date("c", $object->prev_last_login), 0, 10);
              break;
            case 'owner_guid':
              if (is_object($owner)) {
                $metaData = array("guid" => $owner->guid, "name" => $owner->name, "username" => $owner->username);
              } else {
                $metaData = '';
              }
              break;
            case 'description':
              if (is_object($owner)) {
                $metaData = $val;
              } else {
                $metaData = '';
              }
              break;
            default:
              $metaData = '';
          } // switch
          $data[] = array('<b>' . $key . '</b>', $val, $metaData);
          // $i++;
        } // foreach

        $metaObjects = westorElggMan_get_metadata($guid);
        foreach ($metaObjects as $metaObject) {
          if ($metaObject->value_type == "text" || $metaObject->value_type == "integer") {
            $val = $metaObject->value;
          } else {
            $val = "*****";
          }

          $data[] = array($metaObject->name, $val,
            array(
            "value" => $val,
            "type" => $metaObject->value_type,
            "owner_guid" => $metaObject->owner_guid,
            "access_id" => $metaObject->access_id,
            "allow_multiple" => $metaObject->allow_multiple ? true : false
            )
          );
//          $metaData[$i] = array(
//            "value" => $val,
//            "type" => $metaObject->value_type,
//            "owner_guid" => $metaObject->owner_guid,
//            "access_id" => $metaObject->access_id,
//            "allow_multiple" => $metaObject->allow_multiple ? true : false
//            );
//          $i++;
        }
        $ret["data"] = $data;
//        $ret["metaData"] = $metaData;

        $itsObjects = westorElggMan_get_entities("", "", $guid);
        $ownerships = array();
        foreach ($itsObjects as $itsObject) {
          $ownership = new stdClass();
          $ownership->guid = $itsObject->getGUID();
          $append = $itsObject->name . '';
          if ($append == '') {
            $append = $itsObject->title;
          }
          if ($append == '') {
            $append = $itsObject->subtitle;
          }
          if ($append == '') {
            $append = substr($itsObject->description,0,15);
          }
          $append .= ' [' . $itsObject->type;
          if ($subtype = $itsObject->getSubtype()) {
            $append .= ', ' .$subtype;
          }
          $append .= ']';
          $ownership->label = '[' . $itsObject->getGUID() . '] ' . $append;
          $ownerships[] = $ownership;
        }
        $ret["ownerships"] = $ownerships;
      } else {
        $ret["err"] = "No valid Elgg entity for that GUID";
      }
    } else {
      $ret["err"] = "Invalid GUID";
    }
    return($ret);
  }

  private function getAllColumnWidths($user = "admin")
  {
    if ($user == "user") {
      $w = unserialize(westorElggMan_get_plugin_setting("columnWidthsUser",'westorElggMan'));
    } else {
      $w = unserialize(westorElggMan_get_plugin_setting("columnWidthsAdmin",'westorElggMan'));
    }
    if (is_array($w)) {
      $w[0] = 0;
      $w[1] = 28;
      $w[2] = 34;
      return($w);
    }

    $w = array(0, 28, 34, 180);
    if ($user == "user") {
      $varColumns = $this->getVarColumnsUser();
    } else {
      $varColumns = $this->getVarColumns();
    }
    if (is_array($varColumns)) {
      foreach($varColumns as $varColumn){
        if ($varColumn == elgg_echo('ElggMan_:cSince') || $varColumn == elgg_echo('ElggMan_:cLastLogin') || $varColumn == elgg_echo('ElggMan_:cLastAction') ) {
          $w[] = 80;
        } else {
          $w[] = 170;
        }
      }
    }
    return($w);
  }

  function method_getElggData($params, $error)
  // alle zugeordeneten User liefern
  {
    global $CONFIG;
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));

    $owner = $_SESSION['user'];

    $request = $params[0];
    // erlaubte Abfragen: friends, contacs, user
    $filter = $params[1];
    $friendselect = $params[2];

//    $firstRow = $params[3];
//    $lastRow = $params[3];

    $entry["data"] = array();
    $entry["colnames"] = array();
    $entry["colwidths"] = array();

    if ($request == "demo" && $friendselect == elgg_echo('ElggMan:rb:view:user') && $this->is_admin()) {
      $varColumns = $this->getVarColumnsUser();
    } else {
      $varColumns = $this->getVarColumns();
    }

    $limit = MAXENTRIES;
    switch ($request) {
      case "friends":
        switch($friendselect){
          case elgg_echo('ElggMan:friends'):
            $users = $owner->getFriends("", MAXENTRIES, $offset = 0);;
            break;
          case elgg_echo('ElggMan:friends:incoming'):
            $in_count = get_entities_from_relationship('friendrequest', $owner->guid, true, "", "", 0, "", 0, 0, true);
            $users = get_entities_from_relationship("friendrequest", $owner->guid, true, "", "", 0, "", $in_count);
            break;
          case elgg_echo('ElggMan:friends:outgoing'):
            $sent_count = get_entities_from_relationship("friendrequest", $owner->guid, false, "user", "", 0, "", 0, 0, true);
            $users = get_entities_from_relationship("friendrequest", $owner->guid, false, "user", "", 0, "", $sent_count);
        } // switch
        break;
      case "contacts":
        $users = $owner->getObjects("PrivateContact", MAXENTRIES, $offset = 0);
        break;
      case "notActivatedUsers":
        access_show_hidden_entities(true);
        $prefix = $CONFIG->dbprefix;
        $sql = "SELECT guid  FROM {$prefix}entities WHERE type = 'user' and enabled <> 'yes'";
        $result = get_data($sql);
        if (is_array($result)) {
          foreach($result as $row) {
            $users[] = westorElggMan_get_entity($row->guid);
          }
        }
        break;

      case "usersOnline":
        $users = find_active_users(600, MAXENTRIES);
        break;
      case "demo" :
        $limit = 2;
        $entry["helpTxt"] = elgg_echo('ElggMan:helpTableColumns');

      default:
        // "users" || "blockedUsers":
        $users = westorElggMan_get_entities($type = "user", $subtype = "", $owner_guid = 0, $order_by = "", $limit);
        break;
    } // switch

    if (is_array($users)) {
      foreach($users as $user) {
        if ($request == "users" && $user->isBanned()) {
          continue;
        }
        if ($request == "blockedUsers" && ! $user->isBanned()) {
          continue;
        }
        $name = $this->getEntityProperty($user, elgg_echo('ElggMan:displayname'));
        if ($filter) { // Filter angegeben
          if (stripos($name, $filter) === false) {
            continue;
          }
        }
        $line = array($user->getGUID(),
        false,
        $this->getEntityProperty($user, 'userIcon'),
        $name,
        );
        if (is_array($varColumns)) {
          foreach($varColumns as $varColumn){
            if ($varColumn == elgg_echo('ElggMan_:cMobile')) {
              $line[] = $this->getEntityProperty($user, 'smsIcon');
            } else {
              $line[] = $this->getEntityProperty($user, $varColumn);
            }
          }
        }
        $entry["data"][] = $line;

        // f = friend
        // a = admin
        // s = self
        $entry["metadata"][$user->getGUID()] = array('f' => is_object($owner->isFriendsWith($user->guid)),'a' => westorElggMan_isAdmin($user), 's' => $owner->getGUID() == $user->getGUID(), 'u' =>$user->getURL());
      }
    }

    $entry["colnames"] = array('', '', '', elgg_echo("ElggMan:displayname"));
    $entry["colnames"] = array_merge($entry["colnames"],$varColumns);
    if ( ! $this->is_admin() || ($request == "demo" && $friendselect == elgg_echo('ElggMan:rb:view:user'))) {
      $entry["colwidths"] = $this->getAllColumnWidths("user");
    } else {
      $entry["colwidths"] = $this->getAllColumnWidths("admin");
    }

    // list the colrenderer to use, null for default
    $entry["colrenderer"] = array(null,'bool','img',null);
    $i = 4;
    if (is_array($varColumns)) {
      foreach($varColumns as $varColumn){
        if ($varColumn == elgg_echo('ElggMan_:cMobile')) {
          $entry["colrenderer"][] = 'img';
          $entry["colwidths"][$i] = 30;
        } else {
          $entry["colrenderer"][] = null;
        }
        $i++;
      }
    }

    //    $entry["colwidths"] = array(0, 22, 22, 180);
    //    foreach($varColumns as $varColumn){
    //      if ($varColumn == elgg_echo('ElggMan_:cSince') || $varColumn == elgg_echo('ElggMan_:cLastLogin') || $varColumn == elgg_echo('ElggMan_:cLastAction') ) {
      //        $entry["colwidths"][] = 80;
      //      } else {
      //        $entry["colwidths"][] = 170;
      //      }
      //    }
      return $entry;
  }

  // contact
  function method_getContact($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    $result = $params[1];
    $guid = $params[0];
    if ((int) $guid) {
      $v = westorElggMan_get_entity($guid);
      $result->data = array("guid" => $v->guid,
        "user_email" => strtoupper($v->user_email) != 'NULL' ? $v->user_email : '',
        "user_last_name" => $v->user_last_name,
        "mobil" => strtoupper($v->mobil) != 'NULL' ? $v->mobil : '');
      return($result);
    }
  }

  function method_saveContact($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    $result = $params[1];
    $guid = $params[0]->guid;
    $fields = get_object_vars($params[0]->data);
    if ((int) $guid) {
      $v = westorElggMan_get_entity($guid);
      if (! $v->owner_guid == $_SESSION['user']->getGUID()) {
        return(array("err" => "Die Daten können wegen einer Sicherheitsverletzung nicht geändert werden."));
      }
    } else {
      $v = new ElggObject();
      $v->subtype = "PrivateContact";
      $v->access_id = 0; // Private
    }
    if (is_array($fields)) {
      foreach($fields as $field => $val) {
        if ($field != 'guid') {
          if (!$val)
          $v->$field = 'NULL';
          else
          $v->$field = $val;
        }
      }
    }
    $v->save();
    $result->guid = $v->guid;
    return($result);
  }

  function method_deleteContact($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));

    $idsToDelete = $params[0];

    $success = true;
    if (is_array($idsToDelete)) {
      foreach($idsToDelete as $id) {
        $contact = westorElggMan_get_entity($id);
        // todo: ist die entity ein kontakt?
        if ($contact->owner_guid == $_SESSION['user']->getGUID()) {
          if (! $contact->delete()) {
            $success = false;
          }
        }
      }
    }
    if ($success) {
      return(array("txt" => "Kontaktdaten gelöscht."));
    } else {
      return(array("txt" => "Nicht alle Kontaktdaten konnten gelöscht werden."));
    }
  }


  // friends
  function method_handleFriendRequests($params, $error)
  {
    global $CONFIG;
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));

    $friendsToRiverOption = westorElggMan_get_plugin_setting('friendsToRiverOption', 'westorElggMan');
    if (!$friendsToRiverOption) {
      $friendsToRiverOption = "no";
    }

    //        action = "sendMyFriendRequest";
    //        action = "deleteMyFriendRequest";
    //        action = "acceptFriendRequest";
    //        action = "rejectFriendRequest";
    //        action = "removeFromFriends";



    $idsToHandle = $params[0];
    $action = $params[1];
    $user = $_SESSION['user'];

    if(isset($CONFIG->events['create']['friend'])) {
      $oldEventHander = $CONFIG->events['create']['friend'];
      $CONFIG->events['create']['friend'] = array();      //Removes any event handlers
    }

    $msg = array();
    if (is_array($idsToHandle)) {
      foreach($idsToHandle as $id) {
        $friend = westorElggMan_get_entity($id);
        switch($action){
          case "acceptFriendRequest":
            if(remove_entity_relationship($friend->guid, 'friendrequest', $user->guid)) {
              $user->addFriend($friend->guid);
              $friend->addFriend($user->guid);      //Friends means reciprical...
              if ($friendsToRiverOption == 'yes') {
                add_to_river('friends/river/create', 'friend', $_SESSION['user']->guid, $id);
              }
            }
            break;
          case "sendMyFriendRequest":
            if ($id == $_SESSION['user']->guid) {
              $msg["no"][] = $friend->name . ': ' . elgg_echo("ElggMan_:friends:FR:reason_self");
              break;
            }
            //          if ($user->isFriendsWith($friend->guid) || $friend->isFriendsWith($user->guid)) { // user is already a friend?
            if ($user->isFriendsWith($friend->guid)) { // user is already a friend?
              $msg["no"][] = $friend->name . ': ' . elgg_echo("ElggMan_:friends:FR:reason_friend");
              break;
            }
            $result = add_entity_relationship($user->guid, "friendrequest", $friend->guid);
            if($result == false) {
              $msg["no"][] = $friend->name . ': ' . elgg_echo("ElggMan_:friends:FR:reason_sent");
            } else {
              // save EventHandler
              if(isset($CONFIG->events['create']['friend'])) {
                $oldEventHander = $CONFIG->events['create']['friend'];
                $CONFIG->events['create']['friend'] = array();      //Removes any event handlers
              }
              // Notify target user
              $subject = sprintf(elgg_echo('ElggMan_:FR:newfriend:subject'), $user->name);
              $message = sprintf(elgg_echo("ElggMan_:FR:newfriend:body"), $user->name, $CONFIG->url . 'mod/westorElggMan/build/index.php');

              $p = array();
              $p[0] = "EMAILTXT";
              $p[1][] = array($friend->guid);
              $p[2] = $message;
              $p[3] = $subject;
              $this->method_pushMessage($p,null);

              //            notify_user($object->guid_two, $object->guid_one, $subject, $message);
              $msg["yes"][] = $friend->name;
              // restore EventHandler
              if($oldEventHander) {
                $CONFIG->events['create']['friend'] = $oldEventHander;
              }
            }
            break;
          case "deleteMyFriendRequest":
            $result = remove_entity_relationship( $user->guid, 'friendrequest', $friend->guid );
            if($result == false) {
              $msg["no"][] = $friend->name;
            } else {
              $msg["yes"][] = $friend->name;
            }
            break;
          case "rejectFriendRequest":
            $result = remove_entity_relationship( $friend->guid, 'friendrequest', $user->guid );
            if($result == false) {
              $msg["no"][] = $friend->name;
            } else {
              $msg["yes"][] = $friend->name;
            }
            break;
          case "removeFromFriends":
            try {
              $user->removeFriend($friend->guid);
              $friend->removeFriend($user->guid);
              $msg["yes"][] = $friend->name;
            } catch (Exception $e) {
              $msg["no"][] = $friend->name;
            }
            break;
          default:
            ;
        } // switch
      }
    }
    return(array("msg" => $this->createAnswerStr($msg) ));
  }

  // user
  function method_getUser($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    $result = $params[1];

    $guid = $params[0];
    if ((int) $guid) {
      $v = westorElggMan_get_entity($guid);
      $result->data = (array("guid" => $v->guid, "username" => $v->username, "name" => $v->name, "email" => $v->email, "password" => '', "password2" => ''));
    }
    return($result);
  }

  function method_saveUser($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));

    global $CONFIG;
    $result = $params[1];
    $guid = $params[0]->guid;
    $action = $params[0]->action;
    $data = $params[0]->data;
    $username = $data->username;
    $password = $data->password;
    $name = $data->name;
    $email = $data->email;
    $password2 = $data->password2;

    if (strcmp($password, $password2)) {
      $result->err = elgg_echo("ElggMan_:password:problem");
      return($result);
    }

    if ($action == "update" && (int) $guid) {
      $fields = get_object_vars($data);
      $v = westorElggMan_get_entity($guid);
      if (is_array($fields)) {
        foreach($fields as $field => $val) {
          if($field == 'password2') {
            continue;
          }
          if($field == 'password' && ! ($val > '')) {
            continue;
          }
          if ($field != 'guid') {
            if (!$val)
            $v->$field = 'NULL';
            else
            $v->$field = $val;
          }
        }
      }
      $v->save();
    } else {
      try {
        $guid = register_user($username, $password, $name, $email, true);

        if (((trim($password) != "") && (strcmp($password, $password2) == 0)) && ($guid)) {
          $new_user = westorElggMan_get_entity($guid);

          $new_user->admin_created = true;
          $new_user->created_by_guid = get_loggedin_userid();
          set_user_validation_status($new_user->getGUID(), true, 'admin_created');

          notify_user($new_user->guid, $CONFIG->site->guid, elgg_echo('useradd:subject'), sprintf(elgg_echo('useradd:body'), $name, $CONFIG->site->name, $CONFIG->site->url, $username, $password));
          // system_message(sprintf(elgg_echo("adduser:ok"),$CONFIG->sitename));
        } else {
          $result->err = elgg_echo("adduser:bad");
        }
      }
      catch (RegistrationException $r) {
        $result->err = $r->getMessage();
      }
    }
    return($result);
  }

  function method_deleteUser($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    if (! $this->is_admin()) return (array("err" => ADMIN_ERROR));
    access_show_hidden_entities(true);

    $msg = array();
    $idsToDelete = $params[0];
    if (is_array($idsToDelete)) {
      foreach($idsToDelete as $id) {
        $user = westorElggMan_get_entity($id);
        $name = $user->name;
        if (($user instanceof ElggUser) && ($user->canEdit())) {
          $result = $user->delete();
          if($result == false) {
            $msg["no"][] = $name;
          } else {
            $msg["yes"][] = $name;
          }
        }
      }
    }
    return(array("msg" => $this->createAnswerStr($msg) ));
  }

  function method_resetPassword($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    if (! $this->is_admin()) return (array("err" => ADMIN_ERROR));

    $ids = $params[0];
    $msg = array();
    if (is_array($ids)) {
      foreach($ids as $id) {
        $obj = westorElggMan_get_entity($id);

        if (($obj instanceof ElggUser)) {
          $password = generate_random_cleartext_password();
          $obj->salt = generate_random_cleartext_password(); // Reset the salt
          $obj->password = generate_user_password($obj, $password);

          if ($obj->save()) {
            system_message(elgg_echo('admin:user:resetpassword:yes'));
            $msg["yes"][] = $obj->username;

            notify_user($obj->guid,
            $CONFIG->site->guid,
            elgg_echo('email:resetpassword:subject'),
            sprintf(elgg_echo('email:resetpassword:body'), $obj->username, $password),
            null,
                          'email');
          } else {
            $msg["no"][] = $obj->username;
          }
        }
      }
    }
    return(array("msg" => $this->createAnswerStr($msg) ));
  }

  function method_toggleBlock($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    if (! $this->is_admin()) return (array("err" => ADMIN_ERROR));
    access_show_hidden_entities(true);

    $ids = $params[0];
    $ban = $params[1]; // true or false
    $done = 'banned ';
    $action = 'ban';
    if (!$ban) {
      $done = 'unbanned';
      $action = 'unban';
    }

    $msg = array();
    if (is_array($ids)) {
      foreach($ids as $id) {
        $obj = westorElggMan_get_entity($id);
        if (($obj instanceof ElggUser)) {
          if ($obj->$action()) {
            $msg["yes"][] = $obj->username;
          } else {
            $msg["no"][] = $obj->username;
          }
        }
      }
    }
    return(array("msg" => $this->createAnswerStr($msg) ));
  }

  function method_toggleAdmin($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    if (! $this->is_admin()) return (array("err" => ADMIN_ERROR));
    $idsToAdd = $params[0];
    $msg = array();
    if (is_array($idsToAdd)) {
      foreach($idsToAdd as $id) {
        $friend = westorElggMan_get_entity($id);
        try {
          if (!$_SESSION['user']->addFriend($id)) {
            $msg["no"][] = $friend->name;
          } else {
            $msg["yes"][] = $friend->name;
            add_to_river('friends/river/create', 'friend', $_SESSION['user']->guid, $friend_guid);
          }
        }
        catch (Exception $e) {
          register_error(sprintf(elgg_echo("friends:add:failure"), $friend->name));
        }
      }
    }
    return(array("msg" => $this->createAnswerStr($msg) ));
  }

  function method_toggleDisable($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
    if (! $this->is_admin()) return (array("err" => ADMIN_ERROR));
    access_show_hidden_entities(true);

    $ids = $params[0];
    $disable = $params[1]; // true or false
    $done = 'deactivated recursive';
    if (!$disable) {
      $done = 'enabled';
    }

    $msg = array();

    access_show_hidden_entities(true);

    if (is_array($ids)) {
      foreach($ids as $id) {
        $obj = westorElggMan_get_entity($id);
        if (($obj instanceof ElggUser)) {
          if ($disable) {
            if ($obj->disable("elggMan", true)) {
              $msg["yes"][] = $obj->username;
            } else {
              $msg["no"][] = $obj->username;
            }
          } else {
            if ($obj->enable()) {
              if (function_exists("set_user_validation_status")) {
                set_user_validation_status($obj->getGUID(), false, 'admin_modified');
                set_user_validation_status($obj->getGUID(), true, 'admin_modified');
              }
              $msg["yes"][] = $obj->username;
            } else {
              $msg["no"][] = $obj->username;
            }
          }
        }
      }
    }
    return(array("msg" => $this->createAnswerStr($msg) ));
  }

  // Message Functions
  private function createMessageObject($subtype, $mType, $from, $to, $subject, $body, $messageSchedule = null){
    $message = new WestorObject();
    $message->subtype = $subtype;
    $message->mType = $mType;
    $message->owner_guid = $from;
    $message->container_guid = $from;
    $message->title = $subject;
    $message->description = $body;
    $message->toId = $to; // the user receiving the message
    $message->fromId = $from; // the user sending the message
    $message->readYet = 0; // this is a toggle between 0 / 1 (1 = read)
    $message->hiddenFrom = 0; // this is used when a user deletes a message in their sentbox, it is a flag
    $message->hiddenTo = 0; // this is used when a user deletes a message in their inbox
    $message->msg = 1;
    $message->scheduled = $messageSchedule;

    // Plugin Einstellungen: Mail in Outbox speichern
    $copyOutboxOption = westorElggMan_get_plugin_setting('copyOutboxOption', 'westorElggMan');
    if ($copyOutboxOption == 'yes' || ! $copyOutboxOption) {
      // the following properties are introduced to make the alternative background send job compatible
      // with exiting outbox
      $message->waitForSend = 1; // if not present or not 1 no message will not be sended by cron job
      $message->state = 'wait';

      $message->access_id = ACCESS_PRIVATE;
      $message->save();
      // if the new message is a reply then create a relationship link between the new message
      // and the message it is in reply to
      // TODO ???
      //                    if ($reply_guid && $success) {
      //                        $create_relationship = add_entity_relationship($message->guid, "reply", $reply_guid);
        //                    }
      }
      return ($message);
  }

  public function sendMsgNow($message) {
    $mType = $message->mType;
    $recp = westorElggMan_get_entity($message->toId);
    $sender = westorElggMan_get_entity($message->fromId);

    $senderEmail = $this->getEntityProperty($sender, elgg_echo('ElggMan_:cEmail'));

    $subject = $message->title;
    $body = $message->description;

    if (($message->mType == "EMAIL") || ($message->mType == "EMAILTXT")) {
      $mail = new htmlMimeMail5();
    }
    switch($message->mType){
      case "EMAIL":
        $mail->setHTMLCharset("UTF-8");
        $mail->setHTML($body);
      case "EMAILTXT":
        $mail->setTextCharset("UTF-8");
        $mail->setHeadCharset("UTF-8");
        $mail->setFrom($senderEmail);
        $mail->setSubject($subject);
        $search = array("|<br[[:space:]]*/?[[:space:]]*>|i", "|</p>|i");
        $replace = array("\r\n", "\r\n");
        $mail->setText(strip_tags(html_entity_decode(preg_replace($search, $replace, $body))));
        if ($recp->getSubtype() == "PrivateContact") {
          $mail->send(array($recp->user_email));
        } else {
          $mail->send(array($recp->email));
        }
        $message->waitForSend = 0; // mark as done
        $message->state = 'OK';
        break;
      case "HQ-SMS":
        $senderNumber = $sender->westorElggManSMSSender;
        $recipientNumber = $this->getEntityProperty($recp, elgg_echo('ElggMan_:cMobile'));
        $result = $this->sendSMSToGateway($senderNumber, $recipientNumber, $body, $message->mType);
        if ($result["success"]) {
          $message->waitForSend = 0; // mark as done
          $message->state = 'OK';
        } else {
          if ($message->retryed >= 3) {
            $message->waitForSend = 0; // mark as done
            $message->state = 'FAILED';
          }
          $message->retryed++;
        }
        break;
      default:
        ;
    } // switch
    $message->save();
    return($result);
  }
  private function sendDirectNotification($user,$msg) {
    notify_user($user->guid, get_loggedin_user()->guid, elgg_echo('messages:email:subject'),
    sprintf(
    elgg_echo('ElggMan_:newMessageNotification'),
    get_loggedin_user()->name,
    strip_tags($msg),
    $CONFIG->wwwroot . "pg/messages/" . $user->username,
    get_loggedin_user()->name,
    $CONFIG->wwwroot . "mod/messages/send.php?send_to=" . get_loggedin_user()->guid
    )
    );
  }

  function method_pushMessage($params, $error = null)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));

    global $CONFIG;
    $context = westorElggMan_get_context();
    westorElggMan_set_context('westorElggMan');

    // Plugin Settings
    // 'FullMail', 'Notify', 'NoMessage', 'NoInbox'
    $messageSendOption = westorElggMan_get_plugin_setting('messageSendOption', 'westorElggMan');
    if (! $messageSendOption) {  $messageSendOption = 'FullMail'; }

    $useCronOption = westorElggMan_get_plugin_setting('useCronOption', 'westorElggMan');
    if (! $useCronOption) {  $useCronOption = 'no'; }

    $allowSendToAllOption = westorElggMan_get_plugin_setting('allowSendToAllOption', 'westorElggMan');

    $owner = $_SESSION['user'];
    $from = (int) $owner->guid;
    $options = unserialize($owner->smsOptions);

    // SMS-HQ, SMS-BASIC, EMAIL, EMAILTXT, MMS
    $mType = $params[0];

    $lastSMSAccountBalance = $options["lastSMSAccountBalance"];
    $recipients = $params[1];
    $body = $params[2];
    $subject = $params[3];
    if (($mType == "EMAIL" || $mType == "EMAILTXT") && ! ($subject > '')) {
      $subject = elgg_echo("ElggMan_:noSubject");
    }
    // message empty?
    if ($subject == elgg_echo("ElggMan_:noSubject") && (!strlen($body) || $body == '<br />')) {
      return(array("err" => elgg_echo("ElggMan_:noMessageTxt")));
    }
    // shedule
    if ($params[4]) { // in Form yyyy-mm-tt hh:mm:ss
      // $messageSchedule = "'" . mysql_real_escape_string($params[4]) . "'";
      $messageSchedule = mysql_real_escape_string($params[4]);
    } else {
      $messageSchedule = null;
    }

    $count = 0;
    if (is_array($recipients)) {
      foreach($recipients as $recipient) {
        // Ersetzungen
        $link = $CONFIG->wwwroot . 'account/register.php?friend_guid=' . $_SESSION['guid'] . '&invitecode=' . generate_invite_code($_SESSION['user']->username);

        //            $search = array("/%%name%%/", "/%%registration_link%%/", "/%%sender_name%%/");
        //            $replace = array($recipient[1], $link, $owner->name);
        //            $body = preg_replace($search, $replace, $body);
        if (is_array($recipient)) {
          $to = $recipient[0];
        } else {
          $to = $recipient;
        }
        $recp = westorElggMan_get_entity($to);
        if ($recp instanceof ElggUser || $recp->getSubtype() == "PrivateContact") {
          // if the user shall only send messages to friends and private contacs, continue
          // normally this only occures when someone hacks the client code.
          if ($allowSendToAllOption == 'no' && ! ( $this->is_admin() || $recp->isFriendsWith($_SESSION['guid']) || $recp->getSubtype() == "PrivateContact"  )) {
            continue;
          }
          if ($recp == $_SESSION['user']) continue; // no message to current user
          $msgObjToSend = $this->createMessageObject("messages", $mType, $from, $to, $subject, $body, $messageSchedule);
          if ($recp->getSubtype() != "PrivateContact" && ($mType == "EMAIL" || $mType == "EMAILTXT")) {
            $message_copy_for_recipient = clone $msgObjToSend;
            $message_copy_for_recipient->waitForSend = 0;
            $message_copy_for_recipient->state = 'copy';
            $message_copy_for_recipient->save();
            $message_copy_for_recipient->access_id = ACCESS_PRIVATE;
            $message_copy_for_recipient->changeOwner($to);
          }
          if ($recp->getSubtype() != "PrivateContact" && $messageSendOption == 'Notify') {
            $this->sendDirectNotification($recp, $body);
          }
          ///////////////////////////////
          // for DEMO Account send direct
          ///////////////////////////////

          if ($useCronOption == 'yes' && $mType == "HQ-SMS") {
            $smsRes[] = $this->sendMsgNow($msgObjToSend);
          }

          if ($useCronOption == 'no' &&
          ($messageSendOption == 'FullMail' || $recp->getSubtype() == "PrivateContact" || $mType == "HQ-SMS")) {
            $smsRes[] = $this->sendMsgNow($msgObjToSend);
          }
          $count++;
        }
      }
    }
    westorElggMan_set_context($context);
    $result["txt"] = sprintf(elgg_echo('ElggMan_:messagesSaved'), $count);
    if (is_array($smsRes)) {
      foreach ($smsRes as $smsR) {
        if ($smsR["txt"]) {
          $result["txt"] .= "\n" . $smsR["txt"];
        }
      }
    }
    return($result);
  }

  function method_getMessages($params, $error)
  {
    if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));

    $request = $params[0];
    // erlaubte Abfragen: outbox, inbox, sms
    $filter = $params[1];

    $entry["data"] = array();
    $entry["colnames"] = array();
    $entry["colwidths"] = array();

    switch ($request) {
      case elgg_echo("ElggMan:messages:outbox"):
        $messages = westorElggMan_get_entities_from_metadata('fromId', $_SESSION['user']->guid, 'object', 'messages', $_SESSION['user']->guid, MAXENTRIES);
        break;
      case elgg_echo("ElggMan:messages:inbox"):
        $messages = westorElggMan_get_entities_from_metadata('toId', $_SESSION['user']->guid, 'object', 'messages', $_SESSION['user']->guid, MAXENTRIES);
        break;
      case elgg_echo("ElggMan:sms"):
        $messages = westorElggMan_get_entities_from_metadata('mType', 'HQ-SMS', 'object', 'messages', $_SESSION['user']->guid, MAXENTRIES);
        break;
    }
    // $messageType = array("SMS", "SMS", "eMail", "MMS");
    // tableModel.setColumns([ "ID", "", "Empfänger", "Datum", "Text", "Anzahl", "Cent", "Status"]);
    $result = array();
    if (is_array($messages)) {
      $i = 0;
      foreach($messages as $message) {
        if ($message->hiddenTo != 1) { // not deleted
          $guid = $message->getGUID();
          if ($request == elgg_echo("ElggMan:messages:inbox")) {
            // inbox
            $user = westorElggMan_get_entity($message->fromId);
            if ($message->readYet != 1) {
              $message_state = '<img src="../graphics/star.gif">';
              // $message_state .= elgg_echo("ElggMan_:messages:new");
              $contactId = $message->fromId;
            } else {
              $message_state = '';
            }
          } else {
            // outbox or SMS
            if ($request == elgg_echo("ElggMan:messages:outbox") && $message->mType == "HQ-SMS")
            continue;
            $message_state = $message->state ? $message->state : '';
            $user = westorElggMan_get_entity($message->toId);
            $contactId = $message->toId;
          }
          if (is_object($user)) {
            $user_icon = westorElggMan_getIcon($user,'topbar');
            if($user->getSubtype() == "PrivateContact") {
              $user_email = $user->user_email;
              $user_name = $user->user_last_name;
            } else {
              $user_email = $user->email;
              $user_name = $user->name;
            }
          }
          if ($request == elgg_echo("ElggMan:sms")) {
            $len = 30;
            $title = substr($message->description, 0, $len);
            $message_title = $title . (strlen($message->description) > $len ? '...' : '');
            $user_icon = 'resource/westorelggman/sms-mobil.png';
          } else {
            $message_title = $message->title;
          }
          $message_txt = $message->description;

          $message_time = $message->scheduled ? $message->scheduled : substr(date("c", $message->time_created), 0, 10) . ' ' . substr(date("c", $message->time_created), 11, 8);
          // $message_time_updated = substr (date("c", $message->time_updated), 0, 10);
          // $result[] = array($guid, false, $to_user_icon, $to_user_name, $message_time, $message_title, 1, 0, 0);
          if ($filter) { // Filter angegeben
            if (stripos($message_txt, $filter) === false && stripos($message_title, $filter) === false) {
              continue;
            }
          }
          $result[] = array($guid, false, $user_icon, $user_name, $message_time, $message_title, $message_state, $user_email,$contactId);
          if (count($result) >= MAXENTRIES)
          break;
        }
      }
    }
    // while ($row = mysql_fetch_array($result)) {
    // if ($row["date_submit"]) {
    // $date = $row["date_submit"];
      // } else if ($row["submitted"] == 2) {
      // $date = '<font color="red">' . $row["scheduled"] . '</font>';
        // } else if ($row["scheduled"]) {
        // $date = '<font color="green">' . $row["scheduled"] . '</font>';
          // }
          // $txt = $row["txt"];
          // if (strlen($txt) > 40) {
          // $txt = substr($txt, 0, 40) . '...';
            // }
            // $entry[] = array("id" => $row["id"],
            // "recipient" => utf8_encode($row["recipient"]),
            // "date" => $date,
            // "txt" => utf8_encode($txt),
            // "c" => $row["msgcount"],
            // "v" => $row["value"] / 10,
            // "t" => $messageType[$row["type"]],
            // "s" => ($row["send_status"]?$row["send_status"]:'')
            // );
            // }
            return($result);
}

function method_deleteMessages($params, $error)
{
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  $owner = $_SESSION['user'];
  if (!is_object($owner)) {
    $result["err"] = SESSION_ERROR;
    return($result);
  }

  $idsToDelete = $params[0]; // Array
  $success = true;
  if (is_array($idsToDelete)) {
    foreach ($idsToDelete as $message_id) {
      $message = westorElggMan_get_entity($message_id);
      if ($message && ($message->getSubtype() == "messages" || $message->getSubtype() == "elggManSMS")) {
        if (! $message->delete()) {
          $success = false;
        }
      }
    }
  }
  if (! $success) {
    return(array("err" => elgg_echo('ElggMan_:massagesDeletedFailed')));
  } else {
    return(array());
  }
}

function method_getFullMessage($params, $error)
{
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  $message = westorElggMan_get_entity($params[0]);
  return($message->description);
}

function method_getGroups($params, $error)
{
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  $user = $_SESSION['user'];

  $entry = array();
  if($params[0] == 'my') {
    // $groupobjects = get_entities("group", '', $user->guid);
    $groupobjects = get_users_membership($user->guid);
    if (is_array($groupobjects)) {
      foreach($groupobjects as $group){
        $groupname = $group->name;
        $groupowner = false;
        if (!$group->isPublicMembership()) {
          $groupname = '<font color="silver">[' . $groupname . ']</font>';
        }
        if ($group->owner_guid == $user->guid ) {
          $groupname = '<strong>* ' . $groupname . '</strong>';
          $groupowner = true;
        }
        $entry[] = array($group->getGUID(), westorElggMan_getIcon($group,'tiny'), $groupname, $groupowner, $group->isPublicMembership());
      }
    }
  } else {
    if (function_exists("elgg_get_entities")) {
      $groupobjects = elgg_get_entities(array("types" => "group", "limit" => MAXENTRIES));
    } else {
      $groupobjects = get_entities("group", "", 0, "", MAXENTRIES);
    }
    if (is_array($groupobjects)) {
      foreach($groupobjects as $group){
        if ($group->isMember($user->guid))
        continue;

        $groupname = $group->name;
        $groupowner = false;
        if (!$group->isPublicMembership()) {
          $groupname = '<font color="grey">[' . $groupname . ']</font>';
        }
        if ($group->owner_guid == $user->guid ) {
          $groupname = '<strong>* ' . $groupname . '</strong>';
          $groupowner = true;
        }
        $entry[] = array($group->getGUID(), westorElggMan_getIcon($group,'tiny'), $groupname, $groupowner, $group->isPublicMembership());
      }
    }
  }

  return($entry);
}

function method_getGroupMembers($params, $error)
{
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));

  $group = westorElggMan_get_entity($params[0]);
  $members = $group->getMembers(MAXENTRIES);

  $entry = array();
  foreach($members as $member){
    $entry[] = array($member->getGUID(), westorElggMan_getIcon($member,'topbar'), $member->name );
  }

  return($entry);
}

function method_removeMeFromGroup($params, $error){
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  $user = $_SESSION['user'];
  $group = westorElggMan_get_entity($params[0]);

  $group->leave($user);
}

function method_addMeToGroup($params, $error){
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  $user = $_SESSION['user'];
  $group = westorElggMan_get_entity($params[0]);

  $group->join($user);
}

function method_saveAndGetDrafts($params, $error)
{
  return (array());
}

function method_deleteMyUsers($params, $error)
{
}

function method_checkMobileNumbers($params, $error)
{
  global $CONFIG;
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  $owner = $_SESSION['user'];

  $result = $params[1];
  $req = $params[0];
  $recipients = $req->recipients;
  $name_no = array();
  $rcp_name = '';
  if (is_array($recipients)) {
    foreach($recipients as $recipient) {
      $user = westorElggMan_get_entity($recipient);
      $number = $this->getEntityProperty($user, elgg_echo('ElggMan_:cMobile'));
      $name = $this->getEntityProperty($user, elgg_echo('ElggMan:displayname'));
      if ($number == "") {
        $name_no[] = $name;
        $rcp_name .= '<span style="text-decoration:line-through">'.$name.'</span>, ';
      } else {
        $rcp_name .= $name.', ';
        $rcp_guids[] = $recipient;
      }
    }
  }
  $result->rcp_name = substr ( $rcp_name , 0 , strlen($rcp_name)-2 );
  $result->rcp_guids = $rcp_guids;
  if (count($name_no)) {
    $result->txt = elgg_echo('ElggMan_:sms:noNumber') . "\n" . implode(", ",$name_no);
  }

  $req = new stdClass();
  $req->action = "getData";
  // number verified?
  $verified = $this->method_verifySenderNumber(array($req), 1);
  $result->verified = $verified->NumberVeryfied;
  $result->info = $verified->info;
  return($result);
}

// SMS functions
// number verified?
function method_verifySenderNumber($params, $error)
{
  global $CONFIG;
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  $owner = $_SESSION['user'];

  //    $result = new stdClass();
  $result = $params[1];
  $req = $params[0];

  switch($params[0]->action){
    case "getData":
      $number = ($owner->westorElggManSMSSender ? $owner->westorElggManSMSSender : elgg_echo('ElggMan_:sms:senderNotKnown'));
      $result->info = elgg_echo('ElggMan_:sms:senderNumber') . $number;
      $result->CodeSubmitted = ($owner->SMSVerifyCodeSubmitted ? true : false);
      $result->NumberVeryfied = ($owner->SMSNumberVeryfied ? true : false);
      break;
    case "sendNumber":
      $number = $req->number;
      if ($number) {
        $owner->westorElggManSMSVerifyRetrys = 0;
        $owner->SMSVerifyCodeSubmitted = false;
        $owner->SMSNumberVeryfied = false;
        $verifyCode = (string) rand(10000, 99999);
        $smsType = "t"; // Text-SMS
        $msg = elgg_echo('ElggMan_:sms:verify:sendCode') . $verifyCode;
        $senderdomain = elgg_echo("ElggMan_SMS_Sitename");
        $send = $this->sendSMSToGateway($senderdomain, $number, $msg, $smsType, $sanitizeSender=false);
        if ($send["success"]) {
          $owner->westorElggManVerifyCode = $verifyCode;
          $owner->westorElggManSMSSender = $number;
          $owner->SMSVerifyCodeSubmitted = true;
          $number = ($owner->westorElggManSMSSender ? $owner->westorElggManSMSSender : elgg_echo('ElggMan_:sms:senderNotKnown'));
          $result->info = elgg_echo('ElggMan_:sms:senderNumber') . $number;
          $result->txt = sprintf(elgg_echo("ElggMan_:sms:verify:sendCodeResult"),$number);
          if($send["txt"]) {
            $result->txt .= "\n" . $send["txt"];
          }
          $result->success = true;
          //// !!!!!!!!!!!!!!!!
          // $result->verifycode = $verifyCode;
        } else {
          // $result->err = elgg_echo("ElggMan_:sms:problems") . "\n" . $send["txt"];
          $result->err = $send["error"];
          $result->success = false;
          $result->txt = $send["txt"];
          $result->number = $number;
        }
      } else {
        $result->err = elgg_echo('ElggMan_:sms:noNumber2');
        $result->success = false;
      }
      break;
    case  "sendCode":
      $maxTrys = 3;
      $actualTrys = $owner->westorElggManSMSVerifyRetrys;
      if ($actualTrys < $maxTrys) {
        if ($owner->westorElggManVerifyCode == $req->code) {
          $result->success = true;
          $result->info = elgg_echo("ElggMan_:sms:verify:sendCodeVerified");
          $owner->SMSNumberVeryfied = true;
          $owner->westorElggManSMSVerifyRetrys = 0;
        } else {
          $result->success = false;
          $owner->westorElggManSMSVerifyRetrys = ++$actualTrys;
          $remainingTrys =  ( ($remainingTrys = $maxTrys - $actualTrys) > 0 ? $remainingTrys : 0) ;
          $result->info = sprintf(elgg_echo("ElggMan_:sms:verify:codeMismatch"), $remainingTrys) . " $actualTrys";
        }
      } else {
        $result->err = elgg_echo('ElggMan_:sms:noMoreVerifyRetry');
        $result->success = false;
      }
      break;
    default:
      ;
  } // switch
  $owner->save();
  return($result);
}

function method_sendSMS($params, $error)
{
  global $CONFIG;
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  $owner = $_SESSION['user'];

  $result = $params[1];
  $req = $params[0];
  $myRes = $this->method_pushMessage(array("HQ-SMS",$req->recipients,$req->message,$subject=null,$req->shedule));
  $result->txt = $myRes["txt"];
  return($result);
}

public function sanitizeNumber($number) {
  $land = '49';
  // only numbers
  $number = preg_replace("/[^0-9]/", "", $number);
  // 0172 to 49172
  $number = preg_replace("/^0([^0])/", "$land$1", $number);
  if (substr($number,0,2)!="00") {
    $number = "00" . $number;
  }
  return($number);
}

public function sendSMSToGateway($sender, $receiver, $msg, $smsType, $sanitizeSender = true) {
  if (! ($sender && $receiver && $smsType)) {
    $result["txt"]="not all params are given to send sms";
    $result["success"] = false;
    return($result);
  }

  $parm = "receiver=" . $this->sanitizeNumber($receiver)
  . "&sender=" . ($sanitizeSender ? ($this->sanitizeNumber($sender)) : $sender)
  . "&msg=" . urlencode(utf8_decode($msg))
  . "&msgtype=$smsType";

  if (DEVELOPMENT) {
    $gatewayServer = "www.community-software-24.com.local";
  } else {
    $gatewayServer = "www.community-software-24.com";
  }
  $gateways[] = array(
      "protocol" => "http",
      "srv" => $gatewayServer,
      "port" => 80,
      "path" => "/services/sendSMSGateway.php"
      );
      foreach( $gateways as $gateway ){
        try {
          $answer = $this->doHTTPRequest($gateway["srv"], $gateway["port"], $gateway["path"] . '?' . $parm, "GET");
          $result = unserialize($answer);
          if (substr($result["responsebody"],0,2) == "OK") {
            $result["success"]= true;
            $result["txt"]=sprintf(elgg_echo('ElggMan_:sms:test:left'), $result["remaining"]);
            break;
          } else if (! $answer) {
            $result["txt"]="Connection Problem";
            $result["success"]= false;
          } else {
            $result["txt"]=$result["responsebody"];
            $result["success"]= false;
          }
        } catch (Exception $e) {
          $result["txt"]="Unknown Problem";
          $result["success"] = false;
        }
      }
      return($result);
}

public function doHTTPRequest($srv, $port=80, $path, $method="GET", $data="", $referer="", $cookies="") {
  switch(strtoupper($method)) {
    case "POST":
      $req = "POST ".$path." HTTP/1.1\r\n";
      break;

    case "GET":
      $req = "GET ".$path." HTTP/1.1\r\n";
      break;

    default:
      return false;
  }
  if($srv=="") return false;
  $req .= "Host: ".$srv."\r\n";
  if($referer!="") $req .= "Referer: ".$referer."\r\n";
  if($cookies!="") $req .= "Cookie: ".$cookies."\r\n";
  if($method=="POST") {
    $req .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $req .= "Content-Length: ".strlen($data)."\r\n";
  }
  $req .= "Connection: Close\r\n\r\n";
  if($method=="POST") $req .= "".$data;

  $connection = @fsockopen($srv, $port);
  if($connection!==false) {
    fputs($connection, $req);
    $result = "";
    $line = 0;
    while(!feof($connection)) {
      $result .= fgets($connection);
      if(!$line++ && ! preg_match("|HTTP/1.1 200 OK|", $result) ) {
        // if not 200 return
        return false;
      }
    }
    $result = preg_replace("/^.*#####SERVERANSWERCS24#####/s",'',$result);
    // only last line is needed
    return $result;
  } else {
    return false;
  }
}

private function getHTML($argHTMLFile, $aReplaces)
{
  // $this->povMessageHtml = '';
  $handle = fopen ($argHTMLFile, "r");
  $MessageHtml = '';
  if (!$handle) {
    echo "Text $argHTMLFile nicht gefunden";
    exit;
  } while (!feof($handle)) {
    $MessageHtml .= fgets($handle);
  }
  fclose ($handle);
  if (is_array($aReplaces)) {
    foreach($aReplaces as $key => $val) {
      $MessageHtml = preg_replace("/$key/", nl2br($val), $MessageHtml);
    }
  }
  return(nl2br($MessageHtml));
}

function method_checkSMSOptions($params, $error)
{
}

// inserts CSV Data into private contacts
function method_insertCsvData($params, $error) {

}

// settings
function method_saveSettings($params, $error)
{
  global $CONFIG;
  if (! $this->is_loggedin()) return (array("err" => SESSION_ERROR));
  if (! $this->is_admin()) return (array("err" => ADMIN_ERROR));

  $result = $params[1];
  $req = $params[0];

  switch($params[0]->action){
    case "saveTheme":
      westorElggMan_set_plugin_setting("theme", $req->theme, 'westorElggMan');
      $result->success = true;
      break;
    case "saveColumnWidth":
      if ($params[0]->type == elgg_echo('ElggMan:rb:view:user')) {
        westorElggMan_set_plugin_setting("columnWidthsUser", serialize($req->colWidths), 'westorElggMan');
      } else {
        westorElggMan_set_plugin_setting("columnWidthsAdmin", serialize($req->colWidths), 'westorElggMan');
      }
      $result->txt = "OK, Columns saved";
      $result->success = true;
      break;
    case "saveGeneralSettings":
      westorElggMan_set_plugin_setting("pluginWidth", $req->pluginWidth, 'westorElggMan');
      westorElggMan_set_plugin_setting("pollingInterval", $req->pollingInterval, 'westorElggMan');

      $result->txt = "OK, values saved. Please reload the page.";
      $result->success = true;
      break;
    case "resetColumnWidth":
      if ($params[0]->type == elgg_echo('ElggMan:rb:view:user')) {
        clear_plugin_setting("columnWidthsUser", 'westorElggMan');
      } else {
        clear_plugin_setting("columnWidthsAdmin", 'westorElggMan');
      }
      $result->txt = "OK, default settings restored.";
      $result->success = true;
      break;
    case "resetGeneralSettings":
      clear_plugin_setting("pluginWidth", 'westorElggMan');
      $result->txt = "OK, default settings restored. Please reload the page.";
      $result->success = true;

    default:
      ;
  } // switch
  return($result);
}


}

?>