<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once(dirname(dirname(__FILE__))."/api_client/includes.php");

?><html>
<head>
<script type="text/javascript" src="../../../../vendors/jquery/<?php echo $JQUERY_LIB; ?>"></script>
<link rel="stylesheet" type="text/css" href="kaltura.css"/>
<link rel="stylesheet" href="http://elgg.localhost/_css/css.css?lastcache=1296604134&amp;viewtype=default" type="text/css" />
<script type='text/javascript' src="../js/kaltura.js"></script>

<script type='text/javascript'>
/* <![CDATA[ */
//loads the gallery modal
function closeSimpleVideoCreatorModal() {

	var topWindow = Kaltura.getTopWindow();
	topWindow.KalturaModal.closeModal();
	
	topWindow.KalturaVideoStartModal();
}

closeSimpleVideoCreatorModal();
/* ]]> */
</script>
</head>
<body>
</body>
</html>
