<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

$configured = $vars['configured'];

?>
<!--- SERVER SETUP --->
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:serverpart'); ?></h3>
        
	</div>
	<div class="elgg-body">
	    <div id="kaltura_video_layer_server_ce">
	        <p>
	            <?php echo elgg_echo('kalturavideo:server:ceurl'); ?>: <br />
	            <?php
	                echo elgg_view('input/text', array('name' => 'params[kaltura_server_url]','id' => 'kaltura_server_url', 'value' => elgg_get_plugin_setting('kaltura_server_url', 'archive')));
	            ?>
	        </p>
	        <p>
	            <?php echo elgg_echo('kalturavideo:server:ceurl:api'); ?>: <br />
	            <?php
	                echo elgg_view('input/text', array('name' => 'params[kaltura_server_url_api]','id' => 'kaltura_server_url_api', 'value' => elgg_get_plugin_setting('kaltura_server_url_api', 'archive')));
	            ?>
	        </p>
	    </div>
	</div>
</div>
<!----- PARTNER SETUP ----->
<div class="elgg-module elgg-module-info">
    <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:partnerpart'); ?></h3>
        
	</div>
	<div class="elgg-body">
    	<p>
			<?php echo elgg_echo('kalturavideo:enterkmcdata'); ?>:
        </p>
        <p>
            <?php echo elgg_echo('kalturavideo:label:cms_user_email'); ?>:<br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[user]','id' => 'user', 'value' => elgg_get_plugin_setting('user', 'archive')));
            ?>
        </p>
        <p>
            <?php echo elgg_echo('kalturavideo:label:partner_id'); ?>:<br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[partner_id]','id' => 'partner_id', 'value' => elgg_get_plugin_setting('partner_id', 'archive')));
            ?>
        </p>
         <p>
            <?php echo elgg_echo('kalturavideo:label:sub_partner_id'); ?>:<br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[subp_id]','id' => 'sub_partner_id', 'value' => elgg_get_plugin_setting('subp_id', 'archive')));
            ?>
        </p>
          <p>
            <?php echo elgg_echo('kalturavideo:label:secret'); ?>:<br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[secret]','id' => 'secret', 'value' => elgg_get_plugin_setting('secret', 'archive')));
            ?>
        </p>
        <p>
            <?php echo elgg_echo('kalturavideo:label:admin_secret'); ?>:<br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[admin_secret]','id' => 'admin_secret', 'value' => elgg_get_plugin_setting('admin_secret', 'archive')));
            ?>
        </p>
     
    </div>
</div>
<!--- Temporary Ad Options -->
<div class="elgg-module elgg-module-info">
    <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:player'); ?></h3>
	</div>
	<div class="elgg-body">
		 <?php echo elgg_echo('Ad plugin ID'); 
               echo elgg_view('input/url', array('name' => 'params[adPluginID]','id' => 'adPluginID', 'value' => elgg_get_plugin_setting('adPluginID', 'kaltura_video') ));
         ?>
	</div>
</div>
<!--- PLAYER SETTINGS ---->
<div class="elgg-module elgg-module-info">
    <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:player'); ?></h3>
        
	</div>
	<div class="elgg-body">
    	<p><?php echo elgg_echo('kalturavideo:changeplayer'); ?></p>

        
        <div id="kaltura_video_layer_defaultplayer">
        <p>
            <?php echo elgg_echo('kalturavideo:uiconf1'); ?>:
            <?php
                echo elgg_view('input/url', array('name' => 'params[custom_kdp]','id' => 'custom_kdp', 'value' => elgg_get_plugin_setting('custom_kdp', 'archive'), 'class' => 'input-short' ));
            ?>
        </p>        
        </div>
    </div>
</div>

<!--- EDITOR SETTINGS ---->
<div class="elgg-module elgg-module-info">
    <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:editor'); ?></h3>
        
	</div>
	<div class="elgg-body">

        
        <div id="kaltura_video_layer_defaultkcw">
        <p>
            <?php echo elgg_echo('kalturavideo:uiconf2'); ?>:
            <?php
                echo elgg_view('input/url', array('name' => 'params[custom_kcw]','id' => 'custom_kcw', 'value' => elgg_get_plugin_setting('custom_kcw', 'archive'), 'class' => 'input-short' ));
            ?>
        </p>
        </div>
        
        <div id="kaltura_video_layer_defaulteditor">
        <p>
            <?php echo elgg_echo('kalturavideo:uiconf3'); ?>:
            <?php
                echo elgg_view('input/url', array('name' => 'params[custom_kse]','id' => 'custom_kse', 'value' => elgg_get_plugin_setting('custom_kse', 'archive'), 'class' => 'input-short' ));
            ?>
        </p>
        </div>
    </div>
</div>