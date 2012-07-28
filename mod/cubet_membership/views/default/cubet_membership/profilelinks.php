<?php
   /**
    * Elgg Membership plugin
    * Membership Profile Links page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    $user_id = page_owner();
    $user = get_entity($user_id);
    $subscription_id = $user->subscription_id;
    $current_membership = $user->user_type;
    echo elgg_echo('Membership: ')."<b>".$current_membership."</b>";
    if($subscription_id && ($user->canEdit())) {
    ?>
        <div class="mem_cancel">
            <div class="mem_cancel_inner">
                <form action="<?php echo $vars['url']; ?>action/authorizenet/cancel" method="post" name="cancel">
                    <?php echo elgg_view('input/submit', array('name' => 'cancel_btn', 'value' => elgg_echo('cancel:membership:desc'))); ?>
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <?php echo elgg_view('input/securitytoken'); ?>
                </form>
            </div>
        </div>
    <?php } ?>