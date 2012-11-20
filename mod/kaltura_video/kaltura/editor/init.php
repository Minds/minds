<script type='text/javascript' src="../js/kaltura.js"></script>

<script type='text/javascript'>
/* <![CDATA[ */
var entryId = '';
var usetinymce = window.parent.KALTURA_TINYMCE_REGISTERED;
//???
var ks = '';
var thumb = '';
var widget_html = '';
var widget_html_l = '';
var widget_html_m = '';
var obj = {};
var obj_l = {};
var obj_m = {};

//loads the gallery modal
function loadGallery(page) {

	var url = 'gallery.php?<?php echo (empty($_REQUEST['type'])?'':'type='.$_REQUEST['type'].'&'); ?>page=';
	if(page) url += page;

	$('#loading').addClass('loading');
	$('#loading').html('<div id="flash-container"></div>');
	$('#flash-container').load(url,function(){
		$('#loading').removeClass('loading');
		cwWidth = 660;
		cwHeight = 580;
		var topWindow = Kaltura.getTopWindow();

		// fix for IE6, scroll the page up so modal would animate in the center of the window
		if (jQuery.browser.msie && jQuery.browser.version < 7)
			topWindow.scrollTo(0,0);

		topWindow.Kaltura.animateModalSize(cwWidth, cwHeight);

		$('a.insert').click(function(){
			entryId = $(this).attr('rel');
			thumb = $(this).parent().prev().attr('src');
			addentry();
			return false;
		});
		$('a.edit').click(function(){
			entryId = $(this).attr('rel');
			thumb = $(this).parent().prev().attr('src');
			editVideo();
			return false;
		});
		$('a.next,a.prev').click(function(){
			loadGallery($(this).attr('rel'));
		});
		$('a.new').click(function(){
			loadNew();
		});
		$('a.newsimple').click(function(){
			loadNewSimple();
		});
		$('a.cancel').click(function(){
			finished(0);
		});
	});
}

//loads the KCW modal page
function loadNew() {
	$('#loading').addClass('loading');
	$('#loading').html('<div id="flash-container"></div>');
	$('#flash-container').load('contributionwizard.php',{username:'<?php echo get_input("new",''); ?>'},function(){
		$('#loading').removeClass('loading');
		cwWidth = 680;
		cwHeight = 360;
		var topWindow = Kaltura.getTopWindow();

		// fix for IE6, scroll the page up so modal would animate in the center of the window
		if (jQuery.browser.msie && jQuery.browser.version < 7)
			topWindow.scrollTo(0,0);

		topWindow.Kaltura.animateModalSize(cwWidth, cwHeight);
	});
}

//loads the simple video uploader in a modal window
function loadNewSimple() {
	$('#loading').addClass('loading');
	$('#loading').html('<div id="flash-container"></div>');
	$('#flash-container').load('../../newsimplevideo.php?modal_view=1',{username:'<?php echo get_input("new",''); ?>'},function(){
		$('#loading').removeClass('loading');
		$('a.close').click(function(){
			cancelAction();
			loadGallery(1);
		});
		cwWidth = 680;
		cwHeight = 585;
		var topWindow = Kaltura.getTopWindow();

		// fix for IE6, scroll the page up so modal would animate in the center of the window
		if (jQuery.browser.msie && jQuery.browser.version < 7)
			topWindow.scrollTo(0,0);

		topWindow.Kaltura.animateModalSize(cwWidth, cwHeight);
	});
}

function renameMixVideo(entries) {
    if(entries != undefined) {
        var uploaded_entry_id = entries[0].entryId;
		
		//define global 
		uploaded_video_id = entries[0].entryId;
	// we need a synchronous call here
        $.ajax({url:'<?php echo elgg_get_site_url(); ?>mod/kaltura_video/ajax-update.php?update_entry_id='+entryId+'&uploaded_entry_id='+uploaded_entry_id, async:false});
    }
}

function renameAndEditMixVideo(entries) {
    renameMixVideo(entries);
    editVideo();
}

//loads the KSE modal page
function editVideo() {
	var url = 'editor.php?';
	if(entryId) url += 'entryId='+entryId;
	//if(thumb) url += '&thumbnail='+escape(thumb);

	//hide the flash movie
	$('#loading').addClass('loading');
	$('#flash-container').hide();

	//open the editor window
	$('#flash-container').load(url,function(){
		$('#flash-container').show();
		cwWidth = 890;
		cwHeight = 546;
		var topWindow = Kaltura.getTopWindow();

		// fix for IE6, scroll the page up so modal would animate in the center of the window
		if (jQuery.browser.msie && jQuery.browser.version < 7)
			topWindow.scrollTo(0,0);

		topWindow.Kaltura.animateModalSize(cwWidth, cwHeight);
	});
}

//deletes a video
function deleteVideo() {
	if(entryId) {
		var url = '<?php echo elgg_get_site_url(); ?>mod/kaltura_video/ajax-update.php?delete_entry_id='+entryId;
		$('#loading').addClass('loading');
		$('#loading').html('<div id="flash-container"></div>');
		$('#flash-container').load(url,function(data){
			$('#loading').removeClass('loading');
			var topWindow = Kaltura.getTopWindow();
			topWindow.KalturaModal.closeModal();
		});
	}
	else {
		var topWindow = Kaltura.getTopWindow();
		topWindow.KalturaModal.closeModal();
	}
}

//after press the save button on editor
function onEditorSave (entries) {

	if(entries != undefined) {
        var uploaded_entry_id = entries[0].entryId;
	
		// we need a synchronous call here
        $.ajax({	
			url:'<?php echo elgg_get_site_url(); ?>mod/kaltura_video/ajax-update.php?uploaded_entry_id='+uploaded_entry_id, 
			async:false
		});
		
		<?php
			//go to edit details window
			$refresh_loc = $CONFIG->wwwroot."archive/edit/";
		?>
		window.location =  '<?php echo $refresh_loc; ?>' + uploaded_entry_id;
    }

}

//after press the back button on editor
function onEditorBack () {
<?php
if(empty($_REQUEST['entryId']) && !isset($_REQUEST['new'])) {
?>
	//addentry();
	loadGallery(1);
<?php
}
else {
?>
	finished(3);
<?php
}
?>
}

function addentry ()
{
	var url = 'video_options.php?';
	if(entryId) url += 'entryId='+entryId;
	if(thumb) url += '&thumbnail='+escape(thumb);


<?php
if(isset($_REQUEST['new'])) {
?>
	editVideo();
	return;
<?php
}
?>

	$('#loading').addClass('loading');
	$('#loading').html('<div id="flash-container"></div>');
	$('#flash-container').load(url,function(){
		$('#loading').removeClass('loading');
		cwWidth = 600;
		cwHeight = 300;
		var topWindow = Kaltura.getTopWindow();

		// fix for IE6, scroll the page up so modal would animate in the center of the window
		if (jQuery.browser.msie && jQuery.browser.version < 7)
			topWindow.scrollTo(0,0);

		topWindow.Kaltura.animateModalSize(cwWidth, cwHeight);

		$('#finishVideo').click(function(){
			//put the correct widget and exit
			if($('#sizem').get(0).checked) {
				widget_html = widget_html_m;
				obj = obj_m;
			}
			else {
				widget_html = widget_html_l;
				obj = obj_l;
			}
			finished( '1' );
		});
		$('#editVideo').click(function(){
			editVideo();
		});
		$('#cancel').click(function(){
			finished('0');
		});
		$('#gallery').click(function(){
			loadGallery(1);
		});
	});
}

function finished ( modified )
{
	var topWindow = Kaltura.getTopWindow();

	if ( modified == 0 ) {
		topWindow.KalturaModal.closeModal();
	}
	else if ( modified > 1 ) {
		//in this case we try to update the image from opener and close the modal

        //hide the flash movie
        $('#flash-container').hide();

        cwWidth = 240;
        cwHeight = 60;
        topWindow.Kaltura.animateModalSize(cwWidth, cwHeight);

        $.get("../../ajax-update.php",{update_plays:entryId},function(){

	    //topWindow.KalturaModal.closeModal();
    	// close the modal crashes safari and Firefox 2,
<?php
	// check if it's a new video
	if (isset($_REQUEST['new'])) {
		//go to edit details window
		$refresh_loc = $CONFIG->wwwroot."mod/kaltura_video/edit.php?entryid=";
		$refresh_vars = "&uploaded_video_id=";

		echo "\ntopWindow.location =  '$refresh_loc' + entryId + '$refresh_vars' + window.uploaded_video_id;\n";
	}
	//remains in the same page if whe are editing the video
	else echo "\ntopWindow.location.reload();\n";
?>
        });
	}
	else {

	    try {
			if(usetinymce) {
				//update the tinymce!!!
				var cont = '<img style="width: ' + obj.width + 'px; height: ' + obj.height + 'px;" title="src:\'' + obj.swf + '\',width:\'' + obj.width + '\',height:\'' + obj.height + '\',thumbnail:\'' + obj.thumbnail + '\',allowscriptaccess:\'always\',allownetworking:\'all\',allowsfullscreen:\'true\'" class="mceItemKaltura" src="' + obj.thumbnail + '" align="" width="' + obj.width + '" height="' + obj.height + '">';

				topWindow.tinyMCE.activeEditor.execCommand('mceReplaceContent', false, cont);
			}
			else {
				topWindow.tinyMCE.activeEditor.execCommand('mceReplaceContent', false, widget_html);
			}
	    }
	    catch(e) {
			//if tinymce is not active we will put the content as html
			var current_value = topWindow.KalturaTextArea.attr('value');
			topWindow.KalturaTextArea.attr('value',current_value+"\n"+widget_html);
	    }
	    topWindow.KalturaModal.closeModal();
	}
	return;
}
function gotoadmin() {
	var url = '<?php echo $CONFIG->wwwroot.'pg/kaltura_video_admin/'; ?>';
	var topWindow = Kaltura.getTopWindow();
	topWindow.KalturaModal.closeModal();
	topWindow.location = url;
}
/* ]]> */
</script>
