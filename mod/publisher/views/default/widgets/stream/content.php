<?php
/**
 * Stream Widget
 *
 */

$owner = elgg_get_page_owner_entity();

?>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js'></script>

<div id='container'>The player will be placed here</div>

<script type="text/javascript">
  var flashvars = {
	'streamer':'rtmp://www.minds.tv/oflaDemo',
    'file':'<?php echo $owner->username;?>',
	'type':'rtmp',
	'controlbar':'bottom',
    'stretching':'none',
	'id': 'jwplayer',
    'autostart':  'true'


  };
  var params =
      {
        'allowfullscreen':              'true',
        'allowscriptaccess':            'always',
        'bgcolor':                      '#000'
      };

   var attributes =
      {
        'id':                           'jwplayer',
        'name':                         'jwplayer'
      };

  swfobject.embedSWF('<?php echo elgg_get_site_url();?>mod/publisher/jwplayer/player.swf','container','290','270','9.0.115','false', flashvars, params,
  attributes

  );
</script>