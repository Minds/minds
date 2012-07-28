<?php 
    /**
    * Elgg Membership plugin
    * Membership add category page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    
    admin_gatekeeper();
    global $CONFIG;
    $entity			=get_entity($vars['guid']);
    $hidden_groups 	= $vars['entity']->payment_type;
    $plugin_settings= $CONFIG->plugin_settings;
	
    $allow_payment 	= $plugin_settings->allow_regpayment;
    if (!$hidden_groups) $hidden_groups = 'paypal';
    
    $cat_disable = false;
    
    if($entity) {
            $action="action/category/edit";
            $category = $entity->category;
            if($category != ''){
                    $cat_disable = true;
            }
            $title = $entity->title;
            $amount = $entity->amount;
            $description = $entity->description;
            $m_permissions = $entity->permissions;
            if(!is_array($m_permissions)){
                if(empty($m_permissions)){
                    $m_permissions = array();
                }else{
                    $m_permissions = array($m_permissions);
            }
        }
    } else {// For insertion
        $action="action/category/add";
        $category = isset($_SESSION['category']) ? $_SESSION['category'] : '';
        $title = isset($_SESSION['title']) ? $_SESSION['title'] : '';
        $amount = isset($_SESSION['amount']) ? $_SESSION['amount'] : '';
        $description = isset($_SESSION['description']) ? $_SESSION['description'] : '';
        $m_permissions = isset($_SESSION['m_permissions']) ? $_SESSION['m_permissions'] : '';
    }
?>
<script>
function permissionManage(pThis){
    var groupval =$(pThis).val();
    if ($(pThis).is(':checked')) {
        $(pThis).parents("#"+groupval).find("#perm_"+groupval).show();
        $(pThis).parents("#"+groupval).find("#perm_"+groupval).filter('input[name="m_permission"]').attr('checked', true);
    }else{
        $(pThis).parents("#"+groupval).find("#perm_"+groupval).hide();
        $(pThis).parents("#"+groupval).find("#perm_"+groupval).filter('input[name="m_permission"]').attr('checked', false);
    }
}
</script>
<div class="contentWrapper">
<form action="<?php echo $vars['url'].$action; ?>" method="post">

<p>
    <?php echo elgg_echo('membership:category');  ?><br />
    <?php echo elgg_view('input/text',array('name'=>'category','value'=>$category, 'disabled'=>$cat_disable)); ?>
</p>

<p>
    <?php echo elgg_echo('membership:display:name');  ?><br />
    <?php echo elgg_view('input/text',array('name'=>'title','value'=>$title)); ?>
</p>

<?php 
 // for free user type on 29-12-2011
if($allow_payment == '1') {?>
    <p>
        <?php echo elgg_echo('membership:paypalamount');  ?><br />
        <?php echo elgg_view('input/text',array('name'=>'amount','value'=>$amount)); ?>
    </p>
<?php }?>

<p>
    <?php echo elgg_echo('membership:description');  ?><br />
    <?php echo elgg_view('input/plaintext',array('name'=>'description','value'=>$description)); ?>
</p>

<?php 
 if (isset($vars['guid'])) {
    $entity_hidden = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
} else {
    $entity_hidden = '';
}
echo $entity_hidden;

	if(elgg_is_active_plugin('blog') || elgg_is_active_plugin('file') || elgg_is_active_plugin('groups') || elgg_is_active_plugin('pages') || elgg_is_active_plugin('thewire') || elgg_is_active_plugin('bookmarks') || elgg_is_active_plugin('messages')){
	    echo "<p>".elgg_echo('membership:permissions').'</p>';
	}
	if(isset($CONFIG->membershipPermissions) && !empty($CONFIG->membershipPermissions)){
	    echo "<ul class='permission''>";
	    foreach($CONFIG->membershipPermissions as $group=>$permissions){
	        $checked = "";
	        $sub_class = "perm_hide";
	        if(in_array($group, $m_permissions)) {
	             $checked = "checked='checked'";
	             $sub_class = "perm_show";
	        }
	?>
	        <li id="<?php echo $group; ?>">
	            <div><input onclick="return permissionManage(this);" type="checkbox" <?php echo $checked; ?> name="m_permission[]" value="<?php echo $group; ?>" /><?php echo elgg_echo('membership:group:'.$group); ?></div>
	            <div class="sub_permission <?php echo $sub_class ?>" id="perm_<?php echo $group;?>">
	            <?php foreach($permissions as $permission) { ?>
	                <div>
	                    <input type="checkbox" <?php if(in_array($permission['permission'], $m_permissions)) { echo "checked='checked'"; } ?> name="m_permission[]" value="<?php echo $permission['permission']; ?>" /> <?php echo elgg_echo('membership:permission:'.$permission['permission']); ?>
	                </div>
	            <?php } ?>
	            </div>
	        </li>
	<?php
	    }
	    echo "</ul>";
	}
	?>
<p>
    <?php
            echo elgg_view('input/submit', array(
                    'value' => elgg_echo('save'),
            ));
    ?>
</p>
<?php echo elgg_view('input/securitytoken'); ?>
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


