elgg.provide('elgg.tinymce');

/**
 * Toggles the tinymce editor
 *
 * @param {Object} event
 * @return void
 */
elgg.tinymce.toggleEditor = function(event) {
	event.preventDefault();
	
	var target = $(this).attr('href');
	var id = $(target).attr('id');
	if (!tinyMCE.get(id)) {
		tinyMCE.execCommand('mceAddControl', false, id);
		$(this).html(elgg.echo('tinymce:remove'));
	} else {
		tinyMCE.execCommand('mceRemoveControl', false, id);
		$(this).html(elgg.echo('tinymce:add'));
	}
}

/**
 * TinyMCE initialization script
 *
 * You can find configuration information here:
 * http://tinymce.moxiecode.com/wiki.php/Configuration
 */
elgg.tinymce.init = function() {

	$('.tinymce-toggle-editor').on('click', elgg.tinymce.toggleEditor);

	$('.elgg-input-longtext').parents('form').submit(function() {
		tinyMCE.triggerSave();
	});

	tinyMCE.init({
		selector: "textarea",
		relative_urls: false,
	    theme: "modern",
	    fontsize_formats: "8pt 9pt 10pt 11pt 12pt 26pt 36pt 42pt 54pt 64pt 86pt 72pt",
	    width: "100%",
	    height: 400,
	    plugins: [
	         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
	         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
	         "save table contextmenu directionality emoticons template paste textcolor"
	   ],
	   content_css: elgg.get_site_url()+"css/elgg.0.css",
	   toolbar: "fontselect | fontsizeselect | styleselect | forecolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media", 
	   file_browser_callback : mindsBrowser,
	   setup: function (editor) {
	        editor.on('keyup', function (e) {  
	        	//this is a hack...
	         	$(document).trigger('updated-tinymce', editor.id);
	        });
	    }
	   /*style_formats: [
	        {title: 'Bold text', inline: 'b'},
	        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
	        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
	        {title: 'Example 1', inline: 'span', classes: 'example1'},
	        {title: 'Example 2', inline: 'span', classes: 'example2'},
	        {title: 'Table styles'},
	        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
	    ]*/
	});

	function mindsBrowser (field_name, url, type, win) {

	    // alert("Field_Name: " + field_name + "nURL: " + url + "nType: " + type + "nWin: " + win); // debug/testing
	
	    var cmsURL = elgg.get_site_url() + 'archive/embed/' + type;
	
	    tinyMCE.activeEditor.windowManager.open({
	        file : cmsURL,
	        title : 'Browse ' + type,
	        width : 800,  // Your dimensions may differ - toy around with them!
	        height : 400,
	        resizable : "yes",
	        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
	        close_previous : "no"
	    }, {
	        window : win,
	        input : field_name,
	        onInsert: function(src){
	        	win.document.getElementById(field_name).value = src; 
	        }
	    });
	    return false;
	  }
	
}

elgg.register_hook_handler('init', 'system', elgg.tinymce.init);
