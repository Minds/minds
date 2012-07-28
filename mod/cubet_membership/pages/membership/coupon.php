<?php 
     /**
    * Elgg Membership plugin
    * Membership coupon page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    global $CONFIG;
    admin_gatekeeper();

    $page_owner = page_owner_entity();
    if ($page_owner === false || is_null($page_owner)) {
        $page_owner = $_SESSION['user'];
        set_page_owner($_SESSION['guid']);
    }
    
    // Make sure only valid admin users can see this
    elgg_set_context('membership');
    
    $title = elgg_echo('account:settings');
    elgg_push_breadcrumb($title);
    
    // Add the form to this section
    $manage_coupon = $CONFIG->wwwroot."membership/manage_coupon";
    $delete_confirm_msg = elgg_echo('mem:coupon:delete:confirm');
    $area2 .= <<<EOF
    <script type="text/javascript">
        function mem_add_coupon(){
                $.post('{$manage_coupon}', { manage_action: "add_coupon"},
                  function(data){
                    $("#mem_coupcode_container").html(data);
                });
        }

        function mem_coupon_cancel(){
                $.post('{$manage_coupon}', { manage_action: "cancel"},
                  function(data){
                    $("#mem_coupcode_container").html(data);
                });
        }

        function mem_edit_coupon(guid){
                $.post('{$manage_coupon}', { manage_action: "add_coupon",coupon_guid:guid},
                  function(data){
                    $("#mem_coupcode_container").html(data);
                });

        }

        function mem_delete_coupon(guid){
                if(confirm("{$delete_confirm_msg}")){
                        $.post('{$manage_coupon}', { manage_action: "delete",coupon_guid:guid},
                          function(data){
                                if(data == 1)
                                        mem_coupon_cancel();
                                else
                                        alert(data);
                        });
                }
        }
    </script>
EOF;
    $area2 .= elgg_view("cubet_membership/coupon");
    
    // Create a layout
    $body = elgg_view_layout('content', array(
            'filter' => '',
            'content' => $area2,
            'title' => $title,
            'sidebar' => $area1,
    ));

    // Finally draw the page
    echo elgg_view_page($title, $body);
