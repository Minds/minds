<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

global $SKIP_KALTURA_REWRITE,$KALTURA_CURRENT_TINYMCE_FILE,$KALTURA_TINYMCE_PATHS;
?>
<!-- css button -->
<link rel="stylesheet" type="text/css" href="<?php echo $vars['url']; ?>mod/kaltura_video/kaltura/css/buttons.css" />
<?php
$file = $CONFIG->pluginspath.$KALTURA_CURRENT_TINYMCE_FILE;

if(is_file($file)) {
	if($SKIP_KALTURA_REWRITE) {
		require($file);
	}
	else {
		$mce_lang = $vars['user']->language;
		$plugdir = array_flip($KALTURA_TINYMCE_PATHS);
		$plugdir = $lang_file[$KALTURA_CURRENT_TINYMCE_FILE];

		if(!is_file($CONFIG->pluginspath."$plugdir/tinymce/jscripts/tiny_mce/langs/$mce_lang.js")) {
			$mce_lang = 'en';
		}

		$manifest = load_plugin_manifest('kaltura_video');
		$script = file_get_contents($CONFIG->pluginspath.'kaltura_video/kaltura/js/tinymce.js');

		$script = preg_replace(array('/{URL}/','/{MCE_LANG}/','/{MCE_DESC}/','/{VERSION}/'),array($CONFIG->wwwroot,$mce_lang,str_replace("'","\'",elgg_echo('kalturavideo:label:addvideo')),$manifest['version']),$script);

		ob_start();
		include($file);
		$ret = ob_get_clean();
		$pos = strpos($ret,"tinyMCE.init");
		$ret = substr($ret,0,$pos) ."\n$script\n". substr($ret,$pos);

		$pos = strpos($ret,"tinyMCE.init");
		//add the plugins to init
		$substr = substr($ret,$pos);
		$limited_substr = substr($substr,0,strpos($substr,"}"));
		if(strpos($limited_substr,'plugins')!==false) {
			$pos1 = $pos + strpos($substr,"plugins");
			$substr = substr($ret,$pos1);
			$pos1 += strpos($substr,'"') +1;
			$substr = substr($ret,$pos1);
			$pos1 += strpos($substr,'"');
			$ret = substr($ret,0,$pos1) .",kaltura". substr($ret,$pos1);
		}
		else {
			$pos1 = $pos + strpos($substr,"{") +1;
			$substr = substr($ret,$pos1);
			$ret = substr($ret,0,$pos1) ."\nplugins: 'kaltura',\n". substr($ret,$pos1);
		}
		//add the button to init
		$substr = substr($ret,$pos);
		if(strpos($substr,'theme_advanced_buttons1')!==false) {
			$pos1 = $pos + strpos($substr,"theme_advanced_buttons1");
			$substr = substr($ret,$pos1);
			$pos1 += strpos($substr,'"') +1;
			$substr = substr($ret,$pos1);
			$pos1 += strpos($substr,'"');
			$ret = substr($ret,0,$pos1) .",|,kaltura". substr($ret,$pos1);
		}
		else {
			//error message? this is not expected...
		}
		echo $ret;
	}
}
else {
	//echo "<p>Fatal Error while readding longtext.php from tinymce plugin!</p>";
	require($CONFIG->viewpath."default/input/longtext.php");
}

?>
