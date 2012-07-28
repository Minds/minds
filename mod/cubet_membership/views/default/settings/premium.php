<?php
   /**
    * Elgg Membership plugin
    * Membership Premium page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    $confirm="Are you sure you want to delete this item?";
    $edit=$CONFIG->wwwroot."mod/cubet_membership/graphics/Btn_edit.gif";
    $add=$CONFIG->wwwroot."membership/add";
    $order_by = array('name' => "amount",'direction' => 'ASC','as' => integer);
    $options = array(
            'types' => 'object',
            'subtypes' => 'premium_membership',
            'limit'=>9999,
            'offset'=>0,
            'wheres' => $wheres,
            'order_by_metadata' => $order_by);
    $membership = elgg_get_entities_from_metadata($options);

?>
<div style="padding:5px;margin-left:10px;">
    <?php
        echo elgg_view('input/submit', array( 'id'=>'create','name' => "submit_button",'value' => elgg_echo('Add new Category'), 'js'=>'onclick="add_category();"'));
    ?>
</div>

<?php 
if($membership){
?>
    <div class="contentWrapper">
        <table width="100%"  >

            <tr class="margin" ><td>Display Name</td><td>Category</td><td>Description</td><td>Amount</td><td>Edit</td><td>Delete</td></tr>
            <?php
            foreach($membership as $val){
                $guid=$val->guid;
                ?>
                <tr class="premium_membership_row">
                    <td><?php echo $val->title;?></td>
                    <td><?php echo $val->category;?></td>
                    <td class="premium_membership_desc"><?php echo $val->description ;?></td>
                    <td><?php echo $val->amount;?></td>
                    <td>
                        <a href="<?php echo $CONFIG->wwwroot."membership/edit/".$guid;?>"><img src="<?php echo $edit;?>"/></a>
                    </td>
                    <td>
                        <a href="<?php echo $CONFIG->wwwroot."membership/delete/".$guid;?>" onclick="return confirm('<?php echo addslashes($confirm); ?>');">
                        <img src="<?php echo $CONFIG->wwwroot.'mod/cubet_membership/graphics/delete_icon.gif'?>"/></a>
                    </td>
                </tr>
                <?php
            }
?>

        </table>
    </div>
<?php } ?>
<script>
    function add_category() {
        window.location='<?php echo $add;?>';
    }
</script>
