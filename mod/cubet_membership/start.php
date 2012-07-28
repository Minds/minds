<?php
   /**
    * Elgg Membership plugin
    * Membership Start Page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    /**
    * Set membership settings to a global Variable
    */
    global $CONFIG;
    $options = array(
            'types'=>'object',
            'subtypes'=>'membership_settings'
            );
    $plugin_settings = elgg_get_entities($options);
    if($plugin_settings) {
        $CONFIG->plugin_settings = $plugin_settings[0];
    }
    
    /**
     * Membership init function
     */
    function membership_init(){
        global $CONFIG;
        // Set up the menu for logged in admin users
        if (elgg_is_admin_logged_in()) {
            /* add a site navigation item */
            elgg_register_menu_item('site', array(
                    'name' => 'memebership',
                    'text' => elgg_echo('membership'),
                    'href' => 'membership/settings'
            ));
        }
        
        /* routing of urls */
	elgg_register_page_handler('membership','membership_page_handler');

        // add to the main css
        elgg_extend_view('css/elgg','cubet_membership/css');
        
        elgg_register_event_handler('pagesetup','system','membership_submenus');

        // add a file link to owner blocks
        if(elgg_is_logged_in ()) {
            elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'membership_owner_block_menu');
        }
        elgg_register_action('register', dirname(__FILE__)."/actions/membership/register.php", 'public');
        elgg_register_action('admin/user/delete', dirname(__FILE__)."/actions/membership/delete_user.php", 'admin');
        
    }
    
    /**
     * Add a menu item to upgrade mebership to the user ownerblock
     */
    function membership_owner_block_menu($hook, $type, $return, $params) {
        global $CONFIG;
        if (elgg_instanceof($params['entity'], 'user')) {
            $membership=elgg_get_entities(
                        array('types'=>"object",
                                'subtypes'=>"premium_membership",
                                'limit'=>999));
            $i=0;
            foreach($membership as $val){
                $amount[$i++]=$val->amount;
            }
            $max=max($amount);
            $user=elgg_get_logged_in_user_entity();

            $query=mysql_query("select guid from {$CONFIG->dbprefix}objects_entity where title='$user->user_type'");
            $arr=mysql_fetch_array($query);
            $entity_guid=$arr['guid'];
            $entity=get_entity($entity_guid);
            $entity_amount=$entity->amount;
            if(!elgg_is_admin_logged_in() && $entity_amount != $max) {
                $url = $CONFIG->wwwroot."membership/confirm";;
                $item = new ElggMenuItem('membership', elgg_echo('membership:upgrade'), $url);
                $return[] = $item;
            }
        }
        return $return;
    }
    
    /**
     * When users deleted, so remove subscriptions.
     */
    function membership_delete_subscription($guid) {
	global $CONFIG;
        $obj = get_entity($guid);

        $plugin_settings = $CONFIG->plugin_settings;
        $apiloginid = $plugin_settings->authorizenet_apiloginid;
        $transactionkey = $plugin_settings->authorizenet_transactionkey;
        $accounttype = $plugin_settings->authorizenet_environment;
        if($accounttype == "yes") {
            $accounttype = "true";
            $arb_server = "1";
        }
        else {
            $accounttype = "";
            $arb_server = "0";
        }

        if($obj->subscription_id) {
            $user_subscription_id = $obj->subscription_id;
        }

        if($user_subscription_id) {
            require(dirname(__FILE__) . '/pages/membership/AuthnetARB.class.php');

            // Set up the subscription. Use the developer account for testing..
            $subscription = new AuthnetARB($apiloginid, $transactionkey, $arb_server);

             // Set subscription id
            $subscription->setParameter('subscrId', $user_subscription_id);

            // Delete the subscription
            $subscription->deleteAccount();

            // Check the results of our API call
            if ($subscription->isSuccessful()) {
                $flag = 1;
                // Get the subscription ID
                $subscription->getResponse();
            } else {
                 $flag = 0;
                // The subscription was not created!
                $subscription->getResponseCode();
                $subscription->getResponse();
            }
        }
        return $flag;
    }
    
    function plugin_settings_array_manage($hook_name, $action, $return_value, $parameters) {
        if(isset($parameters['name']) && $parameters['name'] == 'permission') {
            if(is_array($parameters['value'])){
                return implode(',', $parameters['value']);
            }
        }
        return $return_value;
    }

    function checkMembershipActionPermission($hook_name, $action, $return_value, $parameters){
        global $CONFIG;
        $permissions = $CONFIG->user_permissions;
        $action = '/'.$action;
        if($permissions != 'all'){
            if($action != ''){
                $return = true;

                //Blog
                if(!in_array('blog', $permissions) && strstr($action,  '/blog')){
                    register_error(elgg_echo('access:not:permitted'));
                    $return = false;
                }
                if( $return !== false){
                    switch ($action){
                        case "blog/add":
                            if((is_array($permissions) && !in_array('blog_add', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:blog:add'));
                                $return = false;
                            }
                            break;
                         case "blog/edit":
                            if((is_array($permissions) && !in_array('blog_edit', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:blog:edit'));
                                $return = false;
                            }
                            break;
                        case "blog/delete":
                            if((is_array($permissions) && !in_array('blog_delete', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:blog:delete'));
                                $return = false;
                            }
                            break;
                    }
                }

                //File
                if(!in_array('file', $permissions) && strstr($action,  '/file')){
                    register_error(elgg_echo('access:not:permitted'));
                    $return = false;
                }
                if( $return !== false){
                    switch ($action){
                        case "file/upload":
                            if((is_array($permissions) && !in_array('file_add', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:file:add'));
                                $return = false;
                            }
                            break;
                         case "file/upload":
                            if((is_array($permissions) && !in_array('file_edit', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:file:edit'));
                                $return = false;
                            }
                            break;
                        case "file/delete":
                            if((is_array($permissions) && !in_array('file_delete', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:file:delete'));
                                $return = false;
                            }
                            break;
                    }
                }

                //Groups
                if(!in_array('group', $permissions) && strstr($action,  '/group')){
                    register_error(elgg_echo('access:not:permitted'));
                    $return = false;
                }
                if( $return !== false){
                    switch ($action){
                        case "groups/edit":
                            $group_guid = get_input('group_guid', 0);
                            if($group_guid == 0 && ((is_array($permissions) && !in_array('groups_add', $permissions)) || $permissions == 'none')){
                                register_error(elgg_echo('access:groups:add'));
                                $return = false;
                            }
                             if($group_guid > 0 && ((is_array($permissions) && !in_array('groups_edit', $permissions)) || $permissions == 'none')){
                                register_error(elgg_echo('access:groups:edit'));
                                $return = false;
                            }
                            break;
                        case "groups/delete":
                            if((is_array($permissions) && !in_array('groups_delete', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:groups:delete'));
                                $return = false;
                            }
                            break;
                    }
                }

                //Pages
                if(!in_array('page', $permissions) && strstr($action,  '/pages')){
                    register_error(elgg_echo('access:not:permitted'));
                    $return = false;
                }
                if( $return !== false){
                    switch ($action){
                        case "pages/edit":
                            $pages_guid = get_input('pages_guid', 0);
                             if($pages_guid > 0 && ((is_array($permissions) && !in_array('pages_edit', $permissions)) || $permissions == 'none')){
                                    register_error(elgg_echo('access:pages:edit'));
                                    $return = false;
                            }
                            if($pages_guid == 0 && ((is_array($permissions) && !in_array('pages_add', $permissions)) || $permissions == 'none')){
                                register_error(elgg_echo('access:pages:add'));
                                $return = false;
                            }
                            break;
                        case "pages/delete":
                            if((is_array($permissions) && !in_array('pages_delete', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:pages:delete'));
                                $return = false;
                            }
                            break;
                    }
                }

                //The wire
                if(!in_array('thewire', $permissions) && strstr($action,  '/thewire')){
                    register_error(elgg_echo('access:not:permitted'));
                    $return = false;
                }
                if( $return !== false){
                    switch ($action){
                        case "thewire/add":
                            if(strstr($_SERVER[HTTP_REFERER],  'reply') && ((is_array($permissions) && !in_array('thewire_reply', $permissions)) || $permissions == 'none')){
                                register_error(elgg_echo('access:thewire:reply'));
                                $return = false;
                            }
                             if(!strstr($_SERVER[HTTP_REFERER],  'reply') && ((is_array($permissions) && !in_array('thewire_add', $permissions)) || $permissions == 'none')){
                                 register_error(elgg_echo('access:thewire:add'));
                                $return = false;
                            }
                            break;
                        case "thewire/delete":
                            if((is_array($permissions) && !in_array('thewire_delete', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:thewire:delete'));
                                $return = false;
                            }
                            break;
                    }
                }

                //Bookmarks
                if(!in_array('bookmarks', $permissions) && strstr($action,  '/bookmarks')){
                    register_error(elgg_echo('access:not:permitted'));
                    $return = false;
                }
                if( $return !== false){
                    switch ($action){
                        case "bookmarks/add":
                            $bookmark_guid = get_input('bookmark_guid', 0);
                             if($bookmark_guid > 0 && ((is_array($permissions) && !in_array('bookmarks_edit', $permissions)) || $permissions == 'none')){
                                    register_error(elgg_echo('access:bookmarks:edit'));
                                    $return = false;
                            }
                            if($bookmark_guid == 0 && ((is_array($permissions) && !in_array('bookmarks_add', $permissions)) || $permissions == 'none')){
                                register_error(elgg_echo('access:bookmarks:add'));
                                $return = false;
                            }
                            break;
                        case "bookmarks/delete":
                            if((is_array($permissions) && !in_array('bookmarks_delete', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:bookmarks:delete'));
                                $return = false;
                            }
                            break;
                    }
                }

                //Messages
                if(!in_array('message', $permissions) && strstr($action,  '/messages')){
                    register_error(elgg_echo('access:not:permitted'));
                    $return = false;
                }
                if( $return !== false){
                    switch ($action){
                        case "messages/delete":
                            if((is_array($permissions) && !in_array('messages_delete', $permissions)) || $permissions == 'none'){
                                register_error(elgg_echo('access:messages:delete'));
                                $return = false;
                            }
                            break;
                    }
                }


                if($return === false){
                    if(isset($_SERVER['HTTP_REFERER'])){
                        forward($_SERVER['HTTP_REFERER']);
                    }else{
                        return false;
                    }
                }
            }
        }
        return $return_value;
    }

    function checkMembershipPermission(){
        global $CONFIG;
        $submenu_delete = array();
        $context =  elgg_get_context();
        $permissions = $CONFIG->user_permissions;
        //print_r($permissions);
        $handler = $_REQUEST['handler'];
        if($permissions != 'all'){

            // Blog
            if(!in_array('blog', $permissions)){
                unset($CONFIG->registers['menu']['Blogs']);
                if(($context == 'blog' || $handler == 'blog') && !in_array('blog', $permissions)){
                    register_error(elgg_echo('access:not:permitted'));
                    forward();
                }
            }
            if($handler == 'blog'){
                if((is_array($permissions) && (!in_array('blog_add', $permissions) || !in_array('blog', $permissions))) || $permissions == 'none'){
                    $submenu_delete[] = 'Write a blog post';
                    if(strstr($_REQUEST['page'],  'new')){
                        register_error(elgg_echo('access:blog:add'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('blog_read', $permissions) || !in_array('blog', $permissions))) || $permissions == 'none'){
                    if(strstr($_REQUEST['page'],  'read')){
                        register_error(elgg_echo('access:blog:read'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('blog_edit', $permissions) || !in_array('blog', $permissions))) || $permissions == 'none'){
                    if(strstr($_REQUEST['page'],  'edit')){
                        register_error(elgg_echo('access:blog:edit'));
                        forward();
                    }
                }
            }

            //File
            if(!in_array('file', $permissions)){
                unset($CONFIG->registers['menu']['Files']);
                if(($context == 'file' || $handler == 'file') && !in_array('file', $permissions)){
                    register_error(elgg_echo('access:not:permitted'));
                    forward();
                }
            }
            if($handler == 'file'){
                if((is_array($permissions) && (!in_array('file_add', $permissions) || !in_array('file', $permissions))) || $permissions == 'none'){
                    $submenu_delete[] = 'Upload a file';
                    if(strstr($_REQUEST['page'],  'new')){
                        register_error(elgg_echo('access:file:add'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('file_read', $permissions) || !in_array('file', $permissions))) || $permissions == 'none'){
                    if(strstr($_REQUEST['page'],  'read')){
                        register_error(elgg_echo('access:file:read'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('file_edit', $permissions) || !in_array('file', $permissions))) || $permissions == 'none'){
                    if(strstr($_REQUEST['page'],  'edit')){
                        register_error(elgg_echo('access:file:edit'));
                         forward();
                    }
                }
            }

            //Groups
            if(!in_array('group', $permissions)){
                unset($CONFIG->registers['menu']['Groups']);
                if(($context == 'groups' || $handler == 'groups') && !in_array('groups', $permissions)){
                    register_error(elgg_echo('access:not:permitted'));
                    forward();
                }
            }
            if($handler == 'groups'){
                if((is_array($permissions) && (!in_array('groups_add', $permissions) || !in_array('group', $permissions))) || $permissions == 'none'){
                    $submenu_delete[] = 'Create a new group';
                    if(strstr($_REQUEST['page'],  'new')){
                        register_error(elgg_echo('access:groups:add'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('groups_read', $permissions) || !in_array('group', $permissions))) || $permissions == 'none'){
                    $page = explode('/',$_REQUEST['page']);
                    if($page[0]>0 || strstr($_REQUEST['page'],  'forum')){
                        register_error(elgg_echo('access:groups:read'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('groups_edit', $permissions) || !in_array('group', $permissions))) || $permissions == 'none'){
                   $submenu_delete[] = 'Edit group';
                    if(strstr($_REQUEST['page'],  'edit')){
                        register_error(elgg_echo('access:groups:edit'));
                         forward();
                    }
                }
            }

            //Pages
            if(!in_array('page', $permissions)){
                unset($CONFIG->registers['menu']['Pages']);
                if(($context == 'pages' || $handler == 'pages') && !in_array('page', $permissions)){
                    register_error(elgg_echo('access:not:permitted'));
                    forward();
                }
            }
            if($handler == 'pages'){
                if((is_array($permissions) && (!in_array('pages_add', $permissions) || !in_array('page', $permissions))) || $permissions == 'none'){
                    $submenu_delete[] = 'New page';
                    if(strstr($_REQUEST['page'],  'new')){
                        register_error(elgg_echo('access:pages:add'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('pages_read', $permissions) || !in_array('page', $permissions))) || $permissions == 'none'){
                    if( strstr($_REQUEST['page'],  'view')){
                        register_error(elgg_echo('access:pages:read'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('pages_edit', $permissions) || !in_array('page', $permissions))) || $permissions == 'none'){
                   $submenu_delete[] = 'Edit page';
                   $submenu_delete[] = 'Create a sub-page';
                    if(strstr($_REQUEST['page'],  'edit')){
                        register_error(elgg_echo('access:pages:edit'));
                         forward();
                    }
                }
                if((is_array($permissions) && (!in_array('pages_delete', $permissions) || !in_array('page', $permissions))) || $permissions == 'none'){
                    $submenu_delete[] = 'Delete this page';
                }
            }


            //The wire
            if(!in_array('thewire', $permissions)){
                unset($CONFIG->registers['menu']['The wire']);
                if(($context == 'thewire' || $handler == 'thewire') && !in_array('thewire', $permissions)){
                    register_error(elgg_echo('access:not:permitted'));
                    forward();
                }
            }
            if((is_array($permissions) && (!in_array('thewire_reply', $permissions) || !in_array('thewire', $permissions))) || $permissions == 'none'){
                if( strstr($_REQUEST['page'],  'reply')){
                    register_error(elgg_echo('access:thewire:reply'));
                    forward();
                }
            }

            //Bookmarks
            if(!in_array('bookmarks', $permissions)){
                unset($CONFIG->registers['menu']['Bookmarks']);
                if(($context == 'bookmarks' || $handler == 'bookmarks') && !in_array('bookmarks', $permissions)){
                    register_error(elgg_echo('access:not:permitted'));
                    forward();
                }
            }
            if($handler == 'bookmarks'){
                if((is_array($permissions) && (!in_array('bookmarks_add', $permissions) || !in_array('bookmarks', $permissions))) || $permissions == 'none'){
                    $submenu_delete[] = 'Add bookmark';
                    if(strstr($_REQUEST['page'],  'add')){
                        register_error(elgg_echo('access:bookmarks:add'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('bookmarks_read', $permissions) || !in_array('bookmarks', $permissions))) || $permissions == 'none'){
                    if(strstr($_REQUEST['page'],  'read')){
                        register_error(elgg_echo('access:bookmarks:read'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('bookmarks_edit', $permissions) || !in_array('bookmarks', $permissions))) || $permissions == 'none'){
                    if(strstr($_REQUEST['page'],  'edit')){
                        register_error(elgg_echo('access:bookmarks:edit'));
                         forward();
                    }
                }
            }

            //Messages
            if(!in_array('message', $permissions)){
                // Extend the elgg topbar

                if(($context == 'messages' || $handler == 'messages') && !in_array('message', $permissions)){
                    register_error(elgg_echo('access:not:permitted'));
                    forward();
                }
            }
            if($handler == 'messages'){
                if((is_array($permissions) && (!in_array('messages_compose', $permissions) || !in_array('message', $permissions))) || $permissions == 'none'){
                    $submenu_delete[] = 'Compose a message';
                    if(strstr($_REQUEST['page'],  'compose')){
                        register_error(elgg_echo('access:messages:compose'));
                        forward();
                    }
                }
                if((is_array($permissions) && (!in_array('messages_read', $permissions) || !in_array('message', $permissions))) || $permissions == 'none'){
                    if(strstr($_REQUEST['page'],  'read')){
                        register_error(elgg_echo('access:messages:read'));
                        forward();
                    }
                }
            }

            foreach($CONFIG->submenu as $group=>$submenus){
                foreach($submenus as $key=>$submenu){
                    if(in_array($submenu->name, $submenu_delete)){
                            unset($CONFIG->submenu[$group][$key]);
                    }
                }
            }
        }
    }
    
    function registerMembershipPermissions( $permission = '', $type = 'object', $subtype = '',  $group = 'default'){
        global $CONFIG;
        if(empty($type)  || empty($permission)){
            return false;
        }
        if(empty($group)){
            $group = 'default';
        }
        if(!isset($CONFIG->membershipPermissions[$group])){
            $CONFIG->membershipPermissions[$group] =array();
        }
        $CONFIG->membershipPermissions[$group][]  = array('type'=>$type, 'subtype'=>$subtype, 'permission'=>$permission);
    }

    function assignMembershipPermissions(){
        global $CONFIG;
        $CONFIG->user_permissions = 'none';
        if(elgg_is_logged_in ()){
            $user = get_loggedin_user();
            if($user instanceof ElggUser){
                if($user->isAdmin()) {
                   $CONFIG->user_permissions = 'all';
                } else if(strtolower ($user->user_type) == 'free') {
                   $plugin_settings = $CONFIG->plugin_settings;
                   $CONFIG->user_permissions = explode(',', $plugin_settings->permission);
               } else {
                    $options = array('types' => 'object',
                                     'subtypes' => 'premium_membership',
                                     'selects' => array("oe.title"),
                                     'joins' => array("JOIN {$CONFIG->dbprefix}objects_entity oe on e.guid = oe.guid "),
                                     'wheres' => array("oe.title='".$user->user_type."'"));
                    $user_type = elgg_get_entities($options);
                    if($user_type && is_array($user_type)){
                        $user_type = $user_type[0];
                        if(is_object($user_type)){
                            $m_permissions = $user_type->permissions;
                            if(!is_array($m_permissions)){
                                if(empty($m_permissions)){
                                    $m_permissions = 'none';
                                }else{
                                    $m_permissions = array($m_permissions);
                                }
                            }
                            $CONFIG->user_permissions = $m_permissions;
                        }
                    }
               }
            }
        }
    }

    function membership_submenus(){
        global $CONFIG;
        if (elgg_is_admin_logged_in() && elgg_get_context() == 'membership') {
            elgg_register_menu_item('page', array(
                    'name' => elgg_echo('membership:settings'),
                    'text' => elgg_echo('membership:settings'),
                    'href' => 'membership/settings'
            ));
        }
        checkMembershipPermission();
    }

    function manage_usertype_setup(){
        $plugin_settings = $CONFIG->plugin_settings;
        if($plugin_settings->show_membership == '1') {
            global $CONFIG;
            $options = array(
                    'types' => 'object',
                    'subtypes' => 'premium_membership',
                    'limit'=>9999,
                    );
            $membership = elgg_get_entities($options);
            $profile_defaults[0]="Free";
            $i=1;
            if($membership) {
                foreach($membership as $val) {
                    $membership_options[$i]=$val->title." ($".$val->amount.")";
                    $membership_values[$i]=$val->guid;
                    $profile_defaults[$val->guid] =$membership_options[$i];
                    $i++;
                }
            }
            $CONFIG->membership_usertype = elgg_trigger_plugin_hook('profile:fields', 'profile', NULL, $profile_defaults);
        }
    }
    
    function membership_page_handler($page) {
        global $CONFIG;
        elgg_push_breadcrumb(elgg_echo('membership'), 'membership/settings');
        if (!isset($page[0])) {
            $page[0] = 'all';
	}
	$pages = dirname(__FILE__) . '/pages/membership';

	if(!empty($page[0])){
            switch($page[0]){
                case "settings":
                        $filter = "general";
                        if(isset($page[1]) && !empty($page[1])){
                            $filter = $page[1];
                        }
                        set_input('filter', $filter);
                        include("$pages/index.php");
                        return true;
                        break;
                case "upgrade":
                        set_input("guid",$page[1]);
                        include("$pages/upgrade.php");
                        return true;
                        break;
                case "confirm":
                        set_input("guid",$page[1]);
                        include("$pages/confirm.php");
                        return true;
                        break;
                case "payment":
                        if(isset($page[1])) {
                            set_input("guid",$page[1]);
                        }
                        if(isset($page[2])) {
                            set_input("cat_guid",$page[2]);
                        }
                        include("$pages/payment.php");
                        return true;
                        break;
                case "add":
                        include("$pages/add.php");
                        return true;
                        break;
                case "edit":
                        set_input("guid",$page[1]);
                        include("$pages/add.php");
                        return true;
                        break;
                case "delete":
                        set_input("guid",$page[1]);
                        include(dirname(__FILE__)."/actions/membership/delete.php");
                        return true;
                        break;
                case "success_payment":
                        set_input("manage_action",$page[1]);
                        set_input("guid",$page[2]);
                        set_input("cat_guid",$page[3]);
                        include(dirname(__FILE__)."/actions/membership/success_payment.php");
                        break;
                case "makepayment": 
                        set_input("manage_action",$page[0]);
                        set_input("guid",$page[1]);
                        set_input("cat_guid",$page[2]);
                        set_input("status",$page[3]);
                        if(isset($page[4])){
                            set_input("coupon",$page[4]);
                        }
                        include(dirname(__FILE__)."/actions/membership/manage_payment.php");
                        break;
                case "manage_coupon":
                        include("$pages/manage_coupon.php");
                        return true;
                        break;
                case "authorizenet" :
                        if(isset($page[1])) {
                            set_input("guid",$page[1]);
                        }
                        if(isset($page[2])) {
                            set_input("cat_guid",$page[2]);
                        }
                        include("$pages/authorizenet.php");
                        return true;
                        break;
                case "authorizenet_success":
                        if(isset($page[1])) {
                            set_input("guid",$page[1]);
                        }
                        if(isset($page[2])) {
                            set_input("cat_guid",$page[2]);
                        }
                        include("$pages/authorizenet_success.php");
                        return true;
                        break;
                case "authorizenet_decline":
                        if(isset($page[1])) {
                            set_input("guid",$page[1]);
                        }
                        if(isset($page[2])) {
                            set_input("cat_guid",$page[2]);
                        }
                        include("$pages/authorizenet_decline.php");
                        return true;
                        break;
                case "all" :
                        @include("$pages/index.php");
                        return true;
                        break;
                case "silent_form":
                        @include("$pages/silent_form.php");
                        return true;
                        break;
                default :
                        @include("$pages/index.php");
                        return true;
                        break;
            }
        }

        elgg_pop_context();
	return true;
        //Modification by Chithra @ Cubet Technologies on 29/09/11
    }
    
    function GenerateMemCouponCode() {
        $len = rand(8, 12);
        $retval = chr(rand(65, 90));
        for ($i = 0; $i < $len; $i++) {
            if (rand(1, 2) == 1) {
                $retval .= chr(rand(65, 90));
            } else {
                $retval .= chr(rand(48, 57));
            }
        }
        return $retval;
    }

    function request_reconfirm($guid) {
        access_show_hidden_entities(true);
        $new_user = get_entity($guid);
        access_show_hidden_entities(false);
        global $CONFIG;
        // Work out validate link
        $link = $CONFIG->wwwroot . "membership/confirm/".$guid;

        $link1 = $CONFIG->site->url . "pg/uservalidationbyemail/confirm?u=$guid&c=" . uservalidationbyemail_generate_code($guid, $new_user->email);

        // Send validation email
        $result = notify_user($new_user->guid, $CONFIG->site->guid, sprintf(elgg_echo('reconfirm:validate:subject'), $new_user->username), sprintf(elgg_echo('reconfirm:validate:body'), $new_user->name, $link, $link1), NULL, 'email');

    }
    
    /*
    Function to get Administrator GUID
    */
    function get_administrator_guid() {
        global $CONFIG;
        //Get admin's guid
        $joins = array("JOIN {$CONFIG->dbprefix}users_entity ue on e.guid = ue.guid");
        $wheres = array("(ue.admin = 'yes')");
        $options = array('types' => 'user','limit'=>1, 'offset'=>0,'joins' => $joins,'wheres' => $wheres,'order_by' => 'ue.guid asc',);
        $admins = elgg_get_entities($options);
        foreach ($admins as $admin) {
            $admin_guid = $admin->guid;
        }
        return $admin_guid;
    }
    
     /*
     Override the membership_to_upgrade function to return true for upgrading membership
     */
    function membership_to_upgrade($hook_name, $entity_type, $return_value, $parameters) {
        $context = elgg_get_context();
        if ($context == 'upgrade_membership') {
            return true;
        }
        return null;
    }
    
    /*
    Function to calculate expiry for memberships
    */
    function calculate_membership_expiry($guid = '', $subscription_date = '', $trial= false) {
        global $CONFIG;
        if(!$guid) {
            $guid = elgg_get_logged_in_user_guid();
        }
        $user = get_entity($guid);
        if(!$subscription_date) {
            $subscription_date = strtotime(date("F j Y"));
        }
        if($trial) {
           $subscr_period_units = $CONFIG->plugin_settings->trial_period_units ? $CONFIG->plugin_settings->trial_period_units : 'D';
           $subscr_period_duration = $CONFIG->plugin_settings->trial_period_duration ?$CONFIG->plugin_settings->trial_period_duration : 1;
        } else {
            $subscr_period_duration = $user->subscr_period_duration ? $user->subscr_period_duration : $CONFIG->plugin_settings->subscr_period_duration;
            $subscr_period_units = $user->interval_unit ? $user->interval_unit : $CONFIG->plugin_settings->subscr_period_units;
        }
        //Add subscription duration to current date
        if($subscr_period_units == 'D') {
            $durationAdded = strtotime(date("Y-m-d", $subscription_date) . " + {$subscr_period_duration} days");
        } else if($subscr_period_units == 'W') {
            $durationAdded = strtotime(date("Y-m-d", $subscription_date) . " + {$subscr_period_duration} week");
        } else if($subscr_period_units == 'M') {
            $durationAdded = strtotime(date("Y-m-d", $subscription_date) . " + {$subscr_period_duration} month");
        } else if($subscr_period_units == 'Y') {
            $durationAdded = strtotime(date("Y-m-d", $update_date) . " + {$subscr_period_duration} year");
        }
        $durationAdded = strtotime(date("Y-m-d", $durationAdded) . " + 1 days");
        return $durationAdded;
    }
   
    /*
    Function to set expiry for memberships
    */
    function check_membership_expiry() {
        global $CONFIG;
        //Get all premium users going to expire today
        $today = strtotime(date("F j Y"));
        $joins = array("JOIN {$CONFIG->dbprefix}users_entity ue on e.guid = ue.guid","JOIN {$CONFIG->dbprefix}metadata md on e.guid = md.entity_guid","JOIN {$CONFIG->dbprefix}metastrings ms_n on md.name_id = ms_n.id","JOIN {$CONFIG->dbprefix}metastrings ms_v on md.value_id = ms_v.id",
                "JOIN {$CONFIG->dbprefix}metadata md1 on e.guid = md1.entity_guid","JOIN {$CONFIG->dbprefix}metastrings ms_n1 on md1.name_id = ms_n1.id","JOIN {$CONFIG->dbprefix}metastrings ms_v1 on md1.value_id = ms_v1.id");
        $wheres = array("(ue.admin = 'no' AND ms_n.string = 'expiry_date' AND ms_v.string <= {$today} AND ms_n1.string = 'user_type' AND ms_v1.string != 'free' )");
        $options = array('types' => 'user','limit'=>9999, 'offset'=>0,'joins' => $joins,'wheres' => $wheres,'metadata_case_sensitive'=>false);
        $users = elgg_get_entities($options);
        if($users) {
            foreach ($users as $user) {
                // Send Notifications to user
                $result = notify_user($user->guid, $CONFIG->site->guid, elgg_echo('expired:membership:subject'), sprintf(elgg_echo('expired:membership:body'), $user->name, $user->user_type,$CONFIG->wwwroot.'membership/confirm/'), NULL, 'email');
                $admin_guid = get_administrator_guid();
                // Send Notifications to administrator
                $result = notify_user($admin_guid, $CONFIG->site->guid, elgg_echo('expired:membership:subject'), sprintf(elgg_echo('expired:membership:admin:body'),get_entity($admin_guid)->name, $user->name, $user->user_type,$CONFIG->wwwroot.'membership/upgrade/'.$user->guid), NULL, 'email');
                $user->user_type = 'Free';
                $user->expired_reason = 'Membership expiry reached';
                $user->amount = 0;
                $user->duration = 0;
                $user->update_date = strtotime(date("F j Y"));
                $user->expiry_date = '';
                $user->notify_date = '';
                $user->save();
            }
        }
        // To send notifications before 15 days
        $wheres = array("(ue.admin = 'no' AND ms_n.string = 'notify_date' AND ms_v.string = {$today} AND ms_n1.string = 'user_type' AND ms_v1.string != 'free')");
        $options = array('types' => 'user','limit'=>9999, 'offset'=>0,'joins' => $joins,'wheres' => $wheres,'metadata_case_sensitive'=>false);
        $users = elgg_get_entities_from_metadata($options);
        if($users) {
            foreach ($users as $user) {
                // Send Notifications
                $result = notify_user($user->guid, $CONFIG->site->guid, elgg_echo('expire:membership:subject'), sprintf(elgg_echo('expire:membership:body'), $user->name, $user->user_type,date ('F j Y',$user->notify_user),$CONFIG->wwwroot.'membership/confirm/'), NULL, 'email');
            }
        }
    }
    
    //Function to get checkout methods from settings
    function get_payment_methods_from_settings() {
        global $CONFIG;
        $show_checkout = $CONFIG->plugin_settings->show_checkout;
        $payment_method = array();
        if($show_checkout) {
            if (is_array($show_checkout)) {
                foreach($show_checkout as $val) {
                    if($val == 'paypal') {
                        $payment_method['Paypal'] = $val;
                    }
                    if($val == 'authorizenet') {
                        $payment_method['Authorize.net'] = $val;
                    }
                }
            } else {
                if($show_checkout == 'paypal') {
                    $payment_method['Paypal'] = $show_checkout;
                } else if($show_checkout == 'authorizenet') {
                    $payment_method['Authorize.net'] = $show_checkout;
                }
            }
        }
        return $payment_method;
    }
    
    //Register a plugin hook for cron
    if(elgg_is_active_plugin('crontrigger')) {
        // cal function 'check_membership_expiry' only if subscription is non-recurring
        elgg_register_plugin_hook_handler('cron', 'daily', 'check_membership_expiry');
    }

    // Register membership permissions
     if(elgg_is_active_plugin('blog')){
        registerMembershipPermissions('blog_read', 'object', 'blog', 'blog');
        registerMembershipPermissions('blog_add', 'object', 'blog', 'blog');
        registerMembershipPermissions('blog_edit', 'object', 'blog', 'blog');
        registerMembershipPermissions('blog_delete', 'object', 'blog', 'blog');
    }
    if(elgg_is_active_plugin('file')){
        registerMembershipPermissions('file_read', 'object', 'file', 'file');
        registerMembershipPermissions('file_add', 'object', 'file', 'file');
        registerMembershipPermissions('file_edit', 'object', 'file', 'file');
        registerMembershipPermissions('file_delete', 'object', 'file', 'file');
    }
    if(elgg_is_active_plugin('groups')){
        registerMembershipPermissions('groups_read', 'group', '', 'group');
        registerMembershipPermissions('groups_add', 'group', '', 'group');
        registerMembershipPermissions('groups_edit', 'group', '', 'group');
        registerMembershipPermissions('groups_delete', 'group', '', 'group');
    }
    if(elgg_is_active_plugin('pages')){
        registerMembershipPermissions('pages_read', 'object', 'page', 'page');
        registerMembershipPermissions('pages_add', 'object', 'page', 'page');
        registerMembershipPermissions('pages_edit', 'object', 'page', 'page');
        registerMembershipPermissions('pages_delete', 'object', 'page', 'page');
    }
    if(elgg_is_active_plugin('thewire')){
        //registerMembershipPermissions('thewire_read', 'object', 'thewire', 'thewire');
        registerMembershipPermissions('thewire_add', 'object', 'thewire', 'thewire');
        registerMembershipPermissions('thewire_reply', 'object', 'thewire', 'thewire');
        registerMembershipPermissions('thewire_delete', 'object', 'thewire', 'thewire');
    }
    if(is_plugin_enabled('bookmarks')){
        registerMembershipPermissions('bookmarks_read', 'object', 'bookmarks', 'bookmarks');
        registerMembershipPermissions('bookmarks_add', 'object', 'bookmarks', 'bookmarks');
        registerMembershipPermissions('bookmarks_edit', 'object', 'bookmarks', 'bookmarks');
        registerMembershipPermissions('bookmarks_delete', 'object', 'bookmarks', 'bookmarks');
    }
    if(elgg_is_active_plugin('messages')){
        registerMembershipPermissions('messages_read', 'object', 'messages', 'message');
        registerMembershipPermissions('messages_compose', 'object', 'messages', 'message');
        registerMembershipPermissions('messages_delete', 'object', 'messages', 'message');
    }

    if(elgg_is_logged_in ()){
        assignMembershipPermissions();
    }
    
    // Override permissions
    elgg_register_plugin_hook_handler('permissions_check','all','membership_to_upgrade');
    elgg_register_plugin_hook_handler('action','all','checkMembershipActionPermission');
    elgg_register_plugin_hook_handler('plugin:setting', 'plugin','plugin_settings_array_manage');

    elgg_register_event_handler('init','system','membership_init');
    elgg_register_event_handler('init','system','manage_usertype_setup', 10000); // Ensure this runs after other plugins

    // Register actions
    //elgg_register_action('register', '', 'public');
    //elgg_register_action("settings", dirname(__FILE__)."/actions/membership/settings.php");
    elgg_register_action("category/add", dirname(__FILE__)."/actions/membership/add-category.php");
    elgg_register_action("category/edit", dirname(__FILE__)."/actions/membership/edit-category.php");
    elgg_register_action("user/upgrade", dirname(__FILE__)."/actions/membership/upgrade.php");
    elgg_register_action("authorizenet", dirname(__FILE__)."/actions/membership/authorizenet.php", "public");
    elgg_register_action("cubet_membership/add_coupon", dirname(__FILE__)."/actions/membership/edit_coupon.php");
    elgg_register_action("cubet_membership/edit_coupon", dirname(__FILE__)."/actions/membership/edit_coupon.php");
    elgg_register_action("cubet_membership/delete_coupon", dirname(__FILE__)."/actions/membership/delete_coupon.php");
    elgg_register_action("authorizenet/cancel", dirname(__FILE__)."/actions/membership/cancel_membership.php");
    elgg_register_action("membership/settings", dirname(__FILE__)."/actions/membership/save_settings.php");
    ?>