<?php 
    /**
    * Elgg Membership plugin
    * Membership upgrade page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    include_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php");
    admin_gatekeeper();
    elgg_set_context('membership');
    $entity=get_entity($vars['guid']);
    $action="action/user/upgrade";
    $hidden_groups = $vars['entity']->payment_type;
    if (!$hidden_groups) $hidden_groups = 'paypal';
    global $CONFIG;
    $order_by = array('name' => "amount",'direction' => 'ASC', 'as' => integer);
    $options = array(
            'types' => 'object',
            'subtypes' => 'premium_membership',
            'limit'=>9999,
            'offset'=>0,
            'order_by_metadata' => $order_by);
    $membership = elgg_get_entities_from_metadata($options);
    $pulldown['Free']="Free";
    $i=1;
    foreach($membership as $val){
        $membership_options[$i]=$val->category;
        $membership_values[$i]=$val->guid;
        $pulldown[$membership_options[$i]] =$membership_options[$i];
        $i++;
    }


?>
<div class="contentWrapper">
    <form action="<?php echo $vars['url'].$action; ?>" method="post">
        <table>
            <tr>
                <td><?php echo elgg_echo('Name: ');?></td>
                <td>&nbsp;</td>
                <td><?php echo  ucfirst($entity->name);  ?>&nbsp;</td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td><?php echo elgg_echo('Membership: ');?></td>
                <td>&nbsp;</td>
                <td><?php echo ucfirst($entity->user_type);  ?>&nbsp;</td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td><?php echo elgg_echo('Upgrade to: ');  ?></td>
                <td>&nbsp;</td>
                <td>
                    <?php echo elgg_view('input/dropdown' , array('name' => 'usertype',"options_values"=>$pulldown,'value' => $entity->user_type, 'class' => "general-textarea"))?>&nbsp;
                </td>
            </tr>
            <?php
            if (isset($vars['guid'])){
            $entity_hidden = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
            } else {
            $entity_hidden = '';
            }
            echo $entity_hidden;
            echo elgg_view('input/securitytoken');
            ?>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    <?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save'))); ?>
                    &nbsp;
                </td>
            </tr>
         </table>
    </form>
</div>
