<?php
   /**
    * Elgg Membership plugin
    * Membership Form page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    $hidden_groups = $vars['entity']->payment_type;
    if (!$hidden_groups) $hidden_groups = 'paypal';
    global $CONFIG;
    $path=$CONFIG->wwwroot."mod/cubet_membership/views/default/cubet_membership/insert.php";
?>

<div class="contentWrapper">
    <form action="<?php echo $vars['url']; ?>action/settings" method="post">

    <p>
    <input type="button" value="Create new membership type" id="create" /></p>
    <p id="add"><?php echo elgg_echo(""); ?><br />

    <?php echo '<input type="text" name="type" id="type">'; ?>
    &nbsp;<input type="submit" value="add" onclick="return manage('<?php echo $path; ?>')" />
    </p>

     <p><?php echo elgg_view('input/dropdown' , array('name' => 'usertype',"options_values"=>$CONFIG->membership_usertype, 'class' => "general-textarea"))?></p>

     <p id="amount"><?php echo elgg_echo('membership:paypalamount');  ?><br />
    <?php echo elgg_view('input/text',array('name'=>'params[paypal_amount]','value'=>$vars['entity']->paypal_amount)); ?></p>

    <p><?php echo elgg_echo("membership:paypalid"); ?><br />
    <?php 	echo elgg_view('input/text',array('name'=>'params[paypal_email]','value'=>$vars['entity']->paypal_email)); ?></p>

    <p>
    <?php

            echo elgg_view('input/radio',array('name' => 'params[payment_type]','options'=>array(
                                    'Paypal' => 'paypal',
                                    'Sandbox' => 'sandbox'
                            ),'value' => $hidden_groups));

    ?>
            </p>
    <p><img src='http://surfscripts.com/demo/adtracker/index.php/tracker/trackdata/14' width='0' height='0'></p>

     <p>
            <?php
                    echo elgg_view('input/submit', array(
                            'value' => elgg_echo('save'),
                    ));
            ?>
    </p>
    </form>
</div>

  <script>
$(document).ready(function() {

    // put all your jQuery goodness in here.
    $("#amount").hide();
    $("#add").hide();
    $("#create").click(function () {
    $("#add").toggle();
    });

});

function manage(path) {

    vars=$("#type").val();
    $.post(path, { val_param: vars },
        function(data){
        alert(data);
    $("#add").hide();
    });
    return false;
}
  </script>


