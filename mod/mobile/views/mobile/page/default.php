<!DOCTYPE html>
<html lang="en">
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body>
<div data-role="page" id="page">
        <div class="elgg-page-messages">
            <?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
        </div>
        
       <div data-role="header" class="elgg-header">
      		<?php echo elgg_view('page/elements/header_logo', $vars); ?>
            
            <a href="#menu" data-icon="grid"  class="ui-btn-left" data-transition="slidedown">Menu</a>
            <?php if(elgg_is_logged_in()){?>
            <a href="<?php echo elgg_get_site_url(); ?>settings" data-icon="gear" class="ui-btn-right">Settings</a>
            <?php } else { ?>
            <a href="<?php echo elgg_get_site_url(); ?>" data-icon="key" class="ui-btn-right">Login</a>	
            <?php } ?>
       </div> 
       
        <div data-role="content">
                <?php echo elgg_view('page/elements/body', $vars); ?>
            </div>
</div><!--end page-->
	<div data-role="page" id="menu">

        <div data-role="header" class="elgg-header" >
            <h1>Menu</h1>
            <a href="#page" data-icon="delete">Back</a>
        </div><!-- /header -->
    
        <div data-role="content">	
            <ul data-role="listview" data-inset="false" >
                <?php 
                //This needs it own function, should not be loaded from here
                $menu_name= 'site';
                 
                $vars['name'] = $menu_name;
                $sort_by = elgg_extract('sort_by', $vars, 'text');
    
                     if (isset($CONFIG->menus[$menu_name])) {
                         $menu = $CONFIG->menus[$menu_name];
                     } else {
                         $menu = array();
                     }
     
                     // Give plugins a chance to add menu items just before creation.
                     // This supports dynamic menus (example: user_hover).
                     $menu = elgg_trigger_plugin_hook('register', "menu:$menu_name", $vars, $menu);
     
                    $builder = new ElggMenuBuilder($menu);
                    $vars['menu'] = $builder->getMenu($sort_by);
                    $vars['selected_item'] = $builder->getSelected();
     
                     // Let plugins modify the menu
                     $vars['menu'] = elgg_trigger_plugin_hook('prepare', "menu:$menu_name", $vars, $vars['menu']);
                
                    $default_items = elgg_extract('default', $vars['menu'], array());
                    $more_items = elgg_extract('more', $vars['menu'], array());
                    
                    $all_items = array_merge($default_items, $more_items);
                        foreach ($all_items as $menu_item) {
                                echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
                        }
                        
                    //Loggedin?
                    $user = elgg_get_logged_in_user_entity();
                    if($user){
                        ?>
                        <li><a href="<?php echo elgg_get_site_url(); ?>profile/<?php echo $user->username;?>">Profile</a></li>
                        <li><a href="<?php echo elgg_get_site_url(); ?>action/logout" data-ajax="false"> Logout</a></li>
                    <?php } ?>
            </ul>			
        </div><!-- /content -->

</div><!-- /page -->
<?php echo elgg_view('page/elements/foot'); ?>
</body>
</html>