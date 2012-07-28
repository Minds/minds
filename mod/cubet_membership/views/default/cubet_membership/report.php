<?php
	/**
	 * Elgg report page
	 * 
	 * @package Elgg Membership
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgg.in/
	 */ 
	
	global $CONFIG;
        access_show_hidden_entities(true);
        
        $limit = 10;
	$offset = get_input('offset', 0);
	$usertype=get_input('type');

        $guid =  get_input('guid');
        if($guid > 0){
            $entity=get_entity($guid);
            $type=$entity->title;
        }else{
            $type='Free';
        }

        $options = array('types'=>'user', 'limit' => $limit, 'offset' => $offset);
        if($usertype){
            $options['metadata_case_sensitive']  = false;
            $options['metadata_name_value_pairs'] = array('user_type'=>$usertype);
            $users=elgg_get_entities_from_metadata($options);
            $options['count'] = true;
            $count=elgg_get_entities_from_metadata($options);
        }else{
            $users=elgg_get_entities($options);
            $options['count'] = true;
            $count=elgg_get_entities($options);
        }
	
	$count_admin=0;
	foreach($users as $user){
		 if($user->isAdmin())
		$count_admin++;
	}
	$count = $count - $count_admin;
	
	$confirm="Are you sure you want to delete this item?";

	$edit=$CONFIG->wwwroot."mod/cubet_membership/graphics/Btn_edit.gif";
	
	$joins = array("JOIN {$CONFIG->dbprefix}metadata md on e.guid = md.entity_guid","JOIN {$CONFIG->dbprefix}metastrings ms_n on md.name_id = ms_n.id","JOIN {$CONFIG->dbprefix}metastrings ms_v on md.value_id = ms_v.id");
	$order_by = array('name' => "amount",'direction' => 'ASC','as' => integer);
	$options = array(
		'types' => 'object',
		'subtypes' => 'premium_membership',
		'limit'=>9999, 
		'offset'=>0,
		'joins' => $joins,
		'wheres' => $wheres,
		'order_by_metadata' => $order_by);
	$memberships = elgg_get_entities_from_metadata($options);
?>
<form name="frm" action="">
	<div class="contentWrapper" >
            <div>
                <select name='type' onchange="submit();">
                    <option value="">Select category</option>
                    <option value="Free" <?php if($usertype == 'Free') {?> selected <?php }?>>Free</option>
                    <?php
                        if($memberships){
                            foreach($memberships as $membership) {
                    ?>
                                <option value="<?php echo  $membership->category;?>" <?php if($membership->category==$usertype) {?> selected <?php }?>><?php echo $membership->title;?></option>
                    <?php
                            }
                        }?>
                </select>
            </div>
            <table class="mem_report" width="100%">
<?php 
                if($users){
?>
                    <tr class="margin">
                        <th><?php echo elgg_echo('log:name');?></th>
                        <th><?php echo elgg_echo('log:email');?></th>
                        <th><?php echo elgg_echo('log:category');?></th>
                        <th><?php echo elgg_echo('log:upgrade');?></th>
                    </tr>
<?php 
                   foreach($users as $user){
                        $user_guid=$user->guid;
                        if(!$user->isAdmin()){
                            if(!$user->user_type) {
                                $user->user_type = 'Free';
                            }
                            if($user->isEnabled()){
                                $class="mem_enabled";
                            }else{
                                $class="mem_disabled";
                            }
?>
                            <tr class="<?php echo $class; ?>">
                                <td>
                                    <div style="float:left;padding:2px 5px 2px 2px;">
                                        <?php echo  $icon = elgg_view_entity_icon($user, 'tiny');
                                        ?>
                                    </div>
                                    <span class="mem_content"><a href="<?php echo $user->getUrl();?> "><?php echo $user->name;?></a></span>
                                </td>
                                <td><span class="mem_content"><?php echo $user->email; ?></span></td>
                                <td><span class="mem_content"><?php echo $user->user_type;?></span></td>
                                <td><a href="<?php echo $CONFIG->wwwroot."membership/upgrade/".$user_guid;?>"><img src="<?php echo $edit;?>" alt=""/></a></td>
                            </tr>
<?php 
                        }
                    }
                } else {
?>
                    <tr><td><?php echo elgg_echo('no:users:membership');?></td></tr>
<?php 
                }
?>
	</table>
</div>
<?php 
	 echo elgg_view('navigation/pagination',array(			
												'base_url' => $_SERVER['REQUEST_URI'],
												'offset' => $offset,
												'count' => $count,
												'limit' => $limit,
														));
?>
</form>

