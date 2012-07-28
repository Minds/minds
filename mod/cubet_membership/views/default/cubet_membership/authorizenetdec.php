<?php
	/**
        * Elgg Membership plugin
        * Authorize.net payment success page
        * @package Elgg Membership plugin
        * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
        * @author Cubet Technologies
        * @copyright Cubet 2010
        * @link http://elgghub.com/
        */

	$guid=get_input("guid");
        $cat_guid=get_input("cat_guid");

	access_show_hidden_entities(true);

        $entity=get_entity($cat_guid);

 	$new_user = get_entity($guid);

 	access_show_hidden_entities(false);

        if(!$_SESSION['user'])
        {
        system_message(elgg_echo('uservalidationbyemail:premiumfailed'));
        system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
        request_reconfirm($guid);

        if (!$new_user->isAdmin)
                $new_user->disable('new_user', false);
		elgg_set_user_validation_status($guid, FALSE);
        }

        $action = $CONFIG->wwwroot;

        $body = "<br>".elgg_echo('cancel:authorizenet');
        $body .= "<br>".$_SESSION['reason']."<br>".$_SESSION['full_error_message'];
        $body .= "<br>".$_SESSION['result'];

?>

        <div class="contentWrapper">
            <form action="<?php echo $action ?>" method="post">
                <p>
                    <?php echo $body; ?>
                </p>
                <input type="submit" class="elgg-button elgg-button-submit" name="btn_submit" value="<?php echo elgg_echo('back:text') ?>">
            </form>
        </div>
<?php
        unset($_SESSION['reason']);
        unset($_SESSION['full_error_message']);
        unset($_SESSION['result']);
        if(isset ($_SESSION['coupon_code']))
        {
            unset($_SESSION['coupon_code']);
        }
        if(isset ($_SESSION['register']))
        {
            unset($_SESSION['register']);
        }
?>
