<?php 
/**
 * Elgg riverdashboard mini-profile and notification links sidebar box
 *
 * @package iShouvik Elgg River
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author iShouvik <contact@ishouvik.com>
 * @link http://ishouvik.com/
 *
 */
?>
<div id="dashboard1">
<!-- displayes user's avatar -->
    <?php 
	$user = elgg_get_page_owner_entity();
	$icon = elgg_view_entity_icon($user, 'large', array('use_hover' => false));
	echo "<div id=\"river_avatar\">" . $icon . "</div>"; 
    ?>
<!-- /river_avatar -->
<div id="lastloggedin">
	<?php 
		$name = '';
		if (elgg_is_logged_in()) {
			$name = elgg_get_logged_in_user_entity()->name;
			echo sprintf(elgg_echo('welcome:user'), $name) . "!<br />";
		}
       ?>
</div> <!-- /lastloggedin -->
<div id="dashboard_navigation">
	<ul>
        <li><a href="<?php echo $vars['url']; ?>profile/<?php echo $_SESSION['user']->username; ?>/edit/">Edit details</a></li>
        <li><a href="<?php echo $vars['url']; ?>avatar/edit/<?php echo $_SESSION['user']->username; ?>">Change image</a></li>
        <li><a href="<?php echo $vars['url']; ?>settings/user/<?php echo $_SESSION['user']->username; ?>">Account settings</a></li>
    </ul>
    <ul>
   
     <?php if(elgg_is_active_plugin('friend_request')){ 
    //need to be logged in to see friend requests
    gatekeeper();
    $user = elgg_get_logged_in_user_entity();

		if($user = elgg_get_logged_in_user_entity()){
			$options = array(
				"type" => "user",
				"count" => true,
				"relationship" => "friendrequest",
				"relationship_guid" => $user->getGUID(),
				"inverse_relationship" => true
			);

    if($count = elgg_get_entities_from_relationship($options)){
    ?>  
        <li><a href="<?php echo $vars['url']; ?>friend_request/<?php echo $_SESSION['user']->username; ?>"><?php echo elgg_echo('friends') ?> <span style="color:#FF0000"> [<?php echo $count ?> New]</span></a></li>
    <?php }else { ?>
        <li><a href="<?php echo $vars['url']; ?>friends/<?php echo $_SESSION['user']->username; ?>"><?php echo elgg_echo('friends') ?> <span style="color:#FF0000">[0 new]</span></a></li>
    <?php }
	} else { ?>
    <li><a href="<?php echo $vars['url']; ?>friends/<?php echo $_SESSION['user']->username; ?>"><?php echo elgg_echo('friends') ?></a></li>
	<?php } 
	} ?>
    </ul>
</div> <!-- /dashboard_navigation -->
</div> <!-- /dasboard1 -->
