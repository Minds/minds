<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/includes.php");

$configured = $vars['configured'];

?>
<!--- SERVER SETUP --->
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:serverpart'); ?></h3>
        
	</div>
	<div class="elgg-body">
		<p>
	<?php echo elgg_echo('kalturavideo:server:info'); ?>
</p>
<p>
	<?php echo elgg_echo('kalturavideo:server:type'); ?>:
	<?php
		echo elgg_view('input/dropdown', array(
			'name' => 'params[kaltura_server_type]',
			'id' => 'kaltura_server_type',
			'disabled'=>$configured,
			'options_values' => array(
				"corp" => elgg_echo('kalturavideo:server:kalturacorp'),
				"ce" => elgg_echo('kalturavideo:server:kalturace')
				),
			'value' => (elgg_get_plugin_setting('kaltura_server_type', 'kaltura_video') ? elgg_get_plugin_setting('kaltura_server_type', 'kaltura_video') : 'corp')
			)
		);
	?>
</p>
    <div id="kaltura_video_layer_server_corp"<?php echo (elgg_get_plugin_setting('kaltura_server_type', 'kaltura_video')=='ce' ? 'style="display:none;"' : ''); ?>>
        <p>
            <?php echo elgg_echo('kalturavideo:server:corpinfo'); ?>
       	 <br />
            <?php echo elgg_echo('kalturavideo:server:moreinfo'); ?> <a href="<?php echo KALTURA_SERVER_URL; ?>" onclick="window.open(this.href);return false;"><?php echo elgg_echo('kalturavideo:server:kalturacorp'); ?></a>
        </p>
    
        <p>
            <?php echo elgg_echo('kalturavideo:notpartner'); ?> <a href="?type=partner_wizard"><?php echo elgg_echo('kalturavideo:clickifnewpartner'); ?></a>
        </p>
    </div>
    <div id="kaltura_video_layer_server_ce"<?php echo (elgg_get_plugin_setting('kaltura_server_type', 'kaltura_video')=='ce' ? '' : 'style="display:none;"'); ?>>
        <p>
            <?php echo elgg_echo('kalturavideo:server:ceurl'); ?>: <br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[kaltura_server_url]','id' => 'kaltura_server_url','disabled'=>$configured, 'value' => (elgg_get_plugin_setting('kaltura_server_url',  'kaltura_video') ? elgg_get_plugin_setting('kaltura_server_url',  'kaltura_video') : $CONFIG->wwwroot.'kalturaCE/') ));
            ?>
        </p>
        <p>
            <?php echo elgg_echo('kalturavideo:server:ceurl:api'); ?>: <br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[kaltura_server_url_api]','id' => 'kaltura_server_url_api','disabled'=>$configured, 'value' => (elgg_get_plugin_setting('kaltura_server_url_api',  'kaltura_video') ? elgg_get_plugin_setting('kaltura_server_url_api',  'kaltura_video') : $CONFIG->wwwroot.'kalturaCE/') ));
            ?>
        </p>
    
        <p>
            <?php echo elgg_echo('kalturavideo:server:ceinfo'); ?>
        <br />
            <?php echo elgg_echo('kalturavideo:server:moreinfo'); ?> <a href="http://www.kaltura.org/project/community_edition_video_platform" onclick="window.open(this.href);return false;"><?php echo elgg_echo('kalturavideo:server:kalturace'); ?></a>
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
            <?php echo elgg_echo('kalturavideo:label:partner_id'); ?>:<br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[partner_id]','id' => 'partner_id','disabled'=>$configured, 'value' => elgg_get_plugin_setting('partner_id',  'kaltura_video')));
            ?>
        </p>
        <p>
            <?php echo elgg_echo('email'); ?>: <br />
            <?php
                echo elgg_view('input/text', array('name' => 'params[email]','id' => 'email','disabled'=>$configured, 'value' => elgg_get_plugin_setting('email',  'kaltura_video') ));
            ?>
        </p>
        <p>
            <?php echo elgg_echo('password'); ?>:
            <?php
                echo elgg_view('input/password', array('name' => 'params[password]','id' => 'password','disabled'=>$configured, 'value' => elgg_get_plugin_setting('password', 'kaltura_video') ));
        
            if($configured) {
            ?>
            <a href="#" id="kaltura_video_change_admin_data">&larr;<?php echo elgg_echo('kalturavideo:editpassword'); ?></a>
            <?php
            }
            ?>
            <a href="<?php echo KalturaHelpers::getServerUrl(); ?>/index.php/kmc" id="kaltura_video_change_password" onclick="window.open(this.href);return false;"<?php echo ($configured ? ' style="display:none;"' : '') ?>><?php echo elgg_echo('kalturavideo:forgotpassword'); ?></a>
        </p>
        <p>
        	<?php echo sprintf(elgg_echo('kalturavideo:logintokaltura'),'<a href="'.KalturaHelpers::getServerUrl().'/index.php/kmc" onclick="window.open(this.href);return false;">'.elgg_echo('kalturavideo:login').'</a>'); ?>
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
        <p>
            <?php echo elgg_echo('kalturavideo:label:defaultplayer'); ?>:
            <?php
                $t = elgg_get_plugin_setting('kaltura_server_type', 'kaltura_video');
                if(empty($t)) $t = 'corp';
                $widgets = $KALTURA_GLOBAL_UICONF['kdp'][$t];
        
                $default = elgg_get_plugin_setting('defaultplayer', 'kaltura_video');
                $vals = array();
                foreach($widgets as $k => $v) {
                    $vals[$k] = $v['name'].' ('.elgg_echo("kalturavideo:generic").' - ' .$v['width'].'x'.$v['height'].'px)';
                }
        
                $vals['custom'] = elgg_echo("kalturavideo:customplayer");
        
                reset($widgets);
                if(empty($default)) $default = key($widgets);
            ?>
                <?php
                echo elgg_view('input/dropdown', array(
                    'name' => 'params[defaultplayer]',
                    'id' => 'defaultplayer',
                    'options_values' => $vals,
                    'value' => $default
                ));
            ?>
        </p>
        
        <div id="kaltura_video_layer_defaultplayer"<?php echo (elgg_get_plugin_setting('defaultplayer', 'kaltura_video')!='custom' ? 'style="display:none;"' : ''); ?> rel="1">
        <p>
            <?php echo elgg_echo('kalturavideo:uiconf1'); ?>:
            <?php
                echo elgg_view('input/url', array('name' => 'params[custom_kdp]','id' => 'custom_kdp', 'value' => elgg_get_plugin_setting('custom_kdp', 'kaltura_video'), 'class' => 'input-short' ));
                echo '<a href="#" id="kaltura_video_getlist_custom_kdp">&larr;'.elgg_echo("kalturavideo:uiconf:getlist").'</a>'
            ?>
        </p>
        <p><?php echo sprintf(elgg_echo('kalturavideo:text:uiconf1'),'<a href="'.KalturaHelpers::getServerUrl().'/index.php/kmc" onclick="window.open(this.href);return false;">'.elgg_echo('kalturavideo:login').'</a>'); ?></p>
        
        </div>
    </div>
</div>

<!--- EDITOR SETTINGS ---->
<div class="elgg-module elgg-module-info">
    <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:editor'); ?></h3>
        
	</div>
	<div class="elgg-body">
    	<p>
			<?php echo elgg_echo('kalturavideo:label:defaultkcw'); ?>:
            <?php
                $t = elgg_get_plugin_setting('kaltura_server_type', 'kaltura_video');
                if(empty($t)) $t = 'corp';
                $widgets = $KALTURA_GLOBAL_UICONF['kcw'][$t];
        
                $default = elgg_get_plugin_setting('defaultkcw', 'kaltura_video');
                $vals = array();
                foreach($widgets as $k => $v) {
                    $vals[$k] = $v['name'].' ('.elgg_echo("kalturavideo:generic").')';
                }
        
                $vals['custom'] = elgg_echo("kalturavideo:customkcw");
        
                reset($widgets);
                if(empty($default)) $default = key($widgets);
            ?>
                <?php
                echo elgg_view('input/dropdown', array(
                    'name' => 'params[defaultkcw]',
                    'id' => 'defaultkcw',
                    'options_values' => $vals,
                    'value' => $default
                ));
            ?>
        </p>
        
        <div id="kaltura_video_layer_defaultkcw"<?php echo (elgg_get_plugin_setting('defaultkcw', 'kaltura_video')!='custom' ? 'style="display:none;"' : ''); ?> rel="2">
        <p>
            <?php echo elgg_echo('kalturavideo:uiconf2'); ?>:
            <?php
                echo elgg_view('input/url', array('name' => 'params[custom_kcw]','id' => 'custom_kcw', 'value' => elgg_get_plugin_setting('custom_kcw', 'kaltura_video'), 'class' => 'input-short' ));
                echo '<a href="#" id="kaltura_video_getlist_custom_kcw">&larr;'.elgg_echo("kalturavideo:uiconf:getlist").'</a>'
            ?>
        </p>
        </div>
        
        <p>
            <?php echo elgg_echo('kalturavideo:label:defaulteditor'); ?>:
            <?php
                $t = elgg_get_plugin_setting('kaltura_server_type', 'kaltura_video');
                if(empty($t)) $t = 'corp';
                $widgets = $KALTURA_GLOBAL_UICONF['kse'][$t];
        
                $default = elgg_get_plugin_setting('defaulteditor', 'kaltura_video');
                $vals = array();
                foreach($widgets as $k => $v) {
                    $vals[$k] = $v['name'].' ('.elgg_echo("kalturavideo:generic").')';
                }
        
                $vals['custom'] = elgg_echo("kalturavideo:customeditor");
        
                reset($widgets);
                if(empty($default)) $default = key($widgets);
            ?>
                <?php
                echo elgg_view('input/dropdown', array(
                    'name' => 'params[defaulteditor]',
                    'id' => 'defaulteditor',
                    'options_values' => $vals,
                    'value' => $default
                ));
            ?>
        </p>
        
        <div id="kaltura_video_layer_defaulteditor"<?php echo (elgg_get_plugin_setting('defaulteditor', 'kaltura_video')!='custom' ? 'style="display:none;"' : ''); ?> rel="3">
        <p>
            <?php echo elgg_echo('kalturavideo:uiconf3'); ?>:
            <?php
                echo elgg_view('input/url', array('name' => 'params[custom_kse]','id' => 'custom_kse', 'value' => elgg_get_plugin_setting('custom_kse', 'kaltura_video'), 'class' => 'input-short' ));
                echo '<a href="#" id="kaltura_video_getlist_custom_kse">&larr;'.elgg_echo("kalturavideo:uiconf:getlist").'</a>';
            ?>
        </p>
        </div>
    </div>
</div>
<!--- Editor Behaviour --->
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:videoeditor'); ?></h3>
        
	</div>
	<div class="elgg-body">
		<p>
			<?php echo elgg_echo('kalturavideo:behavior:alloweditor'); ?>: <br />
            <?php
                $alloweditor = elgg_get_plugin_setting('alloweditor', 'kaltura_video');
                if (!$alloweditor) $alloweditor = 'full';
        
                echo elgg_view('input/dropdown', array(
                    'name' => 'params[alloweditor]',
                    'options_values' => array(
                        'full' => elgg_echo('kalturavideo:alloweditor:full'),
                        'simple' => elgg_echo('kalturavideo:alloweditor:simple'),
                        'no' => elgg_echo('kalturavideo:alloweditor:no')
                    ),
                    'value' => $alloweditor
                ));
            ?>
        </p>
	</div>
</div>
<!--- Rating Behaviour --->
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:rating'); ?></h3>
        
	</div>
	<div class="elgg-body">
		<p>
			<?php echo elgg_echo('kalturavideo:behavior:enablerating'); ?>:
            <?php
                $enablerating = elgg_get_plugin_setting('enablerating', 'kaltura_video');
                if (!$enablerating) $enablerating = 'yes';
        
                echo elgg_view('input/dropdown', array(
                    'name' => 'params[enablerating]',
                    'options_values' => array(
                        'yes' => elgg_echo('option:yes'),
                        'no' => elgg_echo('option:no')
                    ),
                    'value' => $enablerating
                ));
            ?>
        
        </p>
	</div>
</div>    
<!--- Textareas Behaviour --->
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:textareas'); ?></h3>
        
	</div>
	<div class="elgg-body">
		<p>
			<?php echo sprintf(elgg_echo('kalturavideo:label:addbuttonlongtext'),'"<img src="'.$vars['url'] .'mod/kaltura_video/kaltura/images/interactive_video_button.gif" style="vertical-align:middle;" />'.elgg_echo('kalturavideo:label:addvideo').'"'); ?><strong>*</strong>:
            <?php
                $addbutton = elgg_get_plugin_setting('addbutton', 'kaltura_video');
                if (!$addbutton) $addbutton = 'simple';
        
                echo elgg_view('input/dropdown', array(
                    'name' => 'params[addbutton]',
                    'options_values' => array(
                        'no' => elgg_echo('option:no'),
                        'simple' => elgg_echo('kalturavideo:option:simple'),
                        'tinymce' => elgg_echo('kalturavideo:option:tinymce')
                    ),
                    'value' => $addbutton
                ));
            ?>
        </p>
        <p style="font-style:italic;"><strong>*</strong> <?php echo elgg_echo('kalturavideo:note:addbuttonlongtext'); ?></p>
	</div>
</div>  
<!--- Other Behaviours --->
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:admin:others'); ?></h3>
        
	</div>
	<div class="elgg-body">
		<p>
			<?php echo elgg_echo('kalturavideo:behavior:widget'); ?>:
            <?php
                $enableindexwidget = elgg_get_plugin_setting('enableindexwidget', 'kaltura_video');
                if (!$enableindexwidget) $enableindexwidget = 'yes';
        
                echo elgg_view('input/dropdown', array(
                    'name' => 'params[enableindexwidget]',
                    'id' => 'enableindexwidget',
                    'options_values' => array(
                        'single' => elgg_echo('kalturavideo:option:single'),
                        'multi' => elgg_echo('kalturavideo:option:multi'),
                        'no' => elgg_echo('option:no')
                        ),
                    'value' => $enableindexwidget
                ));
            ?>
        </p>
        <p>
            <?php echo elgg_echo('kalturavideo:behavior:numvideos'); ?>:
            <?php
                $total = (int) elgg_get_plugin_setting('numindexvideos', 'kaltura_video');
                if(!$total) $total = 4;
                echo elgg_view('input/url', array('name' => 'params[numindexvideos]','id' => 'numindexvideos', 'value' => $total, 'class' => 'input-short', 'disabled'=>($enableindexwidget=='no') ));
            ?>
        </p>

	</div>
</div>  
<!--- Advanced --->
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
		<h3><?php echo elgg_echo('kalturavideo:label:recreateobjects'); ?></h3>
        
	</div>
	<div class="elgg-body">
		
        
        <div id="kaltura_video_advanced_layer">
        
        <p><?php echo nl2br(elgg_echo('kalturavideo:text:recreateobjects')); ?></p>
        <p><?php echo str_replace("%TAG%",KALTURA_ADMIN_TAGS,str_replace("%URLCMS%",'<a href="'.KalturaHelpers::getServerUrl().'/index.php/kmc" onclick="window.open(this.href);return false;">Login</a>',elgg_echo('kalturavideo:howtoimportkaltura'))); ?></p>
        
        <p><?php
        
        //this works in ajax
        echo elgg_view('input/submit', array('name' => 'recreateobjects','id' => 'kaltura_video_recreate_objects', 'value' => elgg_echo('kalturavideo:advanced:recreateobjects')));
        
        ?></p>
        
        </div>

	</div>
</div>  