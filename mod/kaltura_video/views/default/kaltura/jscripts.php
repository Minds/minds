<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/kaltura_video/kaltura/js/kaltura.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $vars['url']; ?>mod/kaltura_video/kaltura/css/kaltura.css" />
<?php

if(elgg_get_context()=='kaltura_video' || elgg_get_context()=='studio') {
	$js = file_get_contents($CONFIG->path.'mod/kaltura_video/kaltura/js/admin.js');

	$js = str_replace(
	array('{URL}','{SHOWADV}','{HIDEADV}','{EDITTITLE}','{ERRORUPDATETITLE}'),
	array(
		$CONFIG->wwwroot.'mod/kaltura_video/',
		str_replace("'","\'",elgg_echo("kalturavideo:show:advoptions")),
		str_replace("'","\'",elgg_echo("kalturavideo:hide:advoptions")),
		str_replace("'","\'",elgg_echo("kalturavideo:label:edittitle")),
		str_replace("'","\'",elgg_echo("kalturavideo:error:edittitle"))),
	$js);

	echo '<script type="text/javascript">
/* <![CDATA[ */';
if (elgg_get_page_owner_entity() instanceof ElggGroup) {
        echo "\nCURRENT_GROUP = '" . elgg_get_page_owner_entity()->username . "';\n";
} else {
        echo "\nCURRENT_GROUP = '';\n";
}

echo $js.'
/* ]]> */
</script>
';
}
//shows the configuration option if this param is active
if(elgg_get_context()=='admin') {
	$partner_id = str_replace("'","\'",elgg_get_plugin_setting('partner_id','kaltura_video'));
	$change_alert = str_replace("'","\'",str_replace("\n","\\n",elgg_echo('kalturavideo:server:alertchange')));
	$all_processed = str_replace("'","\'",str_replace("\n","\\n",elgg_echo('kalturavideo:recreate:done')));
	$all_processed_errors = str_replace("'","\'",str_replace("\n","\\n",elgg_echo('kalturavideo:recreate:donewitherrors')));

	echo <<<EOF
	<script type="text/javascript">
/* <![CDATA[ */
//changes the visualitzation of admin server part
function KalturaVideoAdminChangeServer() {
	var type = $("#kaltura_server_type").val();
	if(type=="ce") {
		$("#kaltura_video_layer_server_corp").hide();
		$("#kaltura_video_layer_server_ce").show();
		$("#partner_id").val(1);
	}
	else {
		$("#kaltura_video_layer_server_corp").show();
		$("#kaltura_video_layer_server_ce").hide();
		$("#partner_id").val('$partner_id');
	}
}

//loads the ajax object recreator
KALTURA_OLD_PAGE=0;
function KalturaVideoAdminLoadAdvanced(cont) {
	var msg = '<p><b>$all_processed</b></p>';

	$.get('{$CONFIG->wwwroot}mod/kaltura_video/ajax-recreateobjects.php',{page:cont},function(data){
		$('#kaltura_video_advanced_layer').append(data);

		if($('#kaltura_video_advanced_layer .loaded').is('div')) {
			var next_page = $('#kaltura_video_advanced_layer .loaded:last').attr('rel');
			if(KALTURA_OLD_PAGE == next_page) {
				next_page = 'end';
				msg = '<p><b>$all_processed_errors</b></p>';
			}
			KALTURA_OLD_PAGE = next_page;
		}
		else var next_page = 'end';

		if(next_page!='end') {
			$('#kaltura_video_advanced_layer .loaded:not(:last)').remove();
			KalturaVideoAdminLoadAdvanced(next_page);
		}
		else {
			//remove all loadings
			$('#kaltura_video_advanced_layer .loaded').remove();
			$('#kaltura_video_advanced_layer').append(msg);
		}
	});
}
$(document).ready(function(){
	//admin server options part
	$("#kaltura_server_type").change(KalturaVideoAdminChangeServer);
	$("#kaltura_server_type").click(KalturaVideoAdminChangeServer);

	//change data
	$("#kaltura_video_change_admin_data").click(function(){
		if(confirm('$change_alert')) {
			$("#kaltura_server_type").attr('disabled',false);
			$("#kaltura_server_url").attr('disabled',false);
			$("#partner_id").attr('disabled',false);
			$("#email").attr('disabled',false);
			$("#password").attr('disabled',false);
			$("#kaltura_video_change_password").show();
			$(this).hide();
		}
		return false;
	});
	//admin player/editor part
	$("#defaultplayer,#defaulteditor,#defaultkcw").click(function() {
		if($(this).val()=='custom') $('#kaltura_video_layer_'+$(this).attr('id')).show();
		else  $('#kaltura_video_layer_'+$(this).attr('id')).hide();
	});
	//
	$("#kaltura_video_getlist_custom_kdp,#kaltura_video_getlist_custom_kcw,#kaltura_video_getlist_custom_kse").click(function(){
		var id = $(this).attr('id').substr(22);
		var t = $(this).parent().parent().attr('rel');
		$("#"+id).replaceWith('<img id="'+id+'" src="{$CONFIG->wwwroot}mod/kaltura_video/kaltura/editor/images/loadingAnimation.gif" style="vertical-align:middle;" />');
		$(this).remove();

		$.get('{$CONFIG->wwwroot}mod/kaltura_video/ajax-listuiconf.php',{type:t},function(data){
			$("#"+id).replaceWith(data);
		});

		return false;
	})
	//
	$("#enableindexwidget").click(function(){
		if($(this).val()=='no') $('#numindexvideos').attr('disabled',true);
		else $('#numindexvideos').attr('disabled',false);
	});
	//admin advanced part
	$('#kaltura_video_recreate_objects').click(function(){
		$('#kaltura_video_advanced_layer').html('');
		KalturaVideoAdminLoadAdvanced(0);
		return false;
	});

});
/* ]]> */
</script>
EOF;

}

?>
