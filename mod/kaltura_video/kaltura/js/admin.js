/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

OLD_HTML = '';
KALTURA_TINYMCE_REGISTERED = false;
//CURRENT_GROUP = ''; //now is setup in jscripts.php
function Kaltura_button_init() {
	//edit videos
	$('.kalturavideoitem a.edit,.kalturaviewer a.edit').click(function(){
		var id = $(this).attr('rel');
		var thumb = $(this).attr('href');
		KalturaModal.openModal("TB_window", "{URL}kaltura/editor/init.php?entryId="+id+"&thumbnail="+thumb, { width: 240, height: 60 } );
		return false;
	});
	//update status
	$('select.ajaxprivacity').change(function(){
		var id = $(this).attr('id').substr(2);
		var access = $(this).val();

		$.get("{URL}ajax-update.php",{
				id : id,
				access_id : access
			},
			function(data){
				window.location.reload();
			}
		);
	});
	//check/uncheck collaborative editing
	$('input.collaborative').click(function() {
		var id = $(this).val();
		var collaborative = $(this).attr('checked')?'yes':'no';
		$.get("{URL}ajax-update.php",{
				id : id,
				collaborative : collaborative
			},
			function(data){
				window.location.reload();
			}
		);
	});

	//show details of a video
	$('.kalturaviewer a.showdetails').click(function(){
		if($('.kalturaviewer .kaltura_video_details').is(':visible')) {
			$(this).html('{SHOWADV}');
			$('.kalturaviewer .kaltura_video_details').slideUp();
		}
		else {
			$(this).html('{HIDEADV}');
			$('.kalturaviewer .kaltura_video_details input').focus(function(){
				$(this).select();
				return false;
			});
			$('.kalturaviewer .kaltura_video_details input').keypress(function(e){
				$(this).select();
				if(e.keyCode == 9) return true;
				return false;
			});
			$('.kalturaviewer .kaltura_video_details input').mousedown(function(){
				$(this).select();
				return false;
			});
			$('.kalturaviewer .kaltura_video_details').slideDown();
		}
		return false;
	});


}

function Kaltura_ajax_button_init() {
	$('.kalturaviewer a.returnindex').click(function(){
		$('#kaltura_container').html(OLD_HTML);
		Kaltura_button_init();
		return false;
	});
	Kaltura_button_init();
}

$(document).ready(function () {
	//list video button actions
	Kaltura_button_init();

	//init the new video menu
	$('a[href="#kaltura_create"]').click(function(){

		var url = "{URL}kaltura/editor/init.php?new";

		if(CURRENT_GROUP) {
			url += "=" + escape(CURRENT_GROUP);
		}
		//alert(url)
		KalturaModal.openModal("TB_window", url, { width: 240, height: 60 } );
		return false;
	});

	//try to update the plays by ajax (to speed up the contact process with kaltura)
	$('.ajax_play').each(function(){
		var old = $(this).html();
		$(this).load("{URL}ajax-update.php?update_plays="+$(this).attr('rel'),function(data){
			if(!(data)) $(this).html(old)
		});

	});
});
