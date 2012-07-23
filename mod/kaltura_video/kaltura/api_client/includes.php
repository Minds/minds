<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");

global $user_ID, $CONFIG;

include_once ( dirname(__FILE__) . "/definitions.php");
include_once ( dirname(__FILE__) . "/KalturaClient.php");
include_once ( dirname(__FILE__) . "/KalturaClientBase.php");
include_once ( dirname(__FILE__) . "/elgg_helpers.php");
include_once ( dirname(__FILE__) . "/kaltura_helpers.php");

$user_ID = KALTURA_ELGG_USER_PREFIX . $_SESSION['user']->username;
if($page_owner = elgg_get_page_owner_entity()) {
	if($page_owner instanceof ElggGroup) {
		$user_ID .= ':'.$page_owner->getGUID();
	}
}
$user_Name = ( empty($_SESSION['user']->name) ? $_SESSION['user']->username : $_SESSION['user']->name );

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

?>
