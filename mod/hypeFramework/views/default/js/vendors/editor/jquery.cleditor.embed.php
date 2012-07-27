<script type="text/javascript">

    (function($) {

        // Define the table button
        $.cleditor.buttons.embed = {
            name: "embed",
            image: "embed.gif",
            title: "Embed",
            popupName: "embed",
            popupClass: "cleditorPrompt",
            buttonClick: function(e, cldata) {
                $(cldata.popup).width(400).height(300);
                var button = $('input.hj-embed-options-submit');
                $('input.hj-embed-options-submit').die().live('click', function(event) {
                    event.preventDefault();
                       
                    var form = $(this).parents('form:first');
                    var data = new Object();
                        
                    data.type = form.find('input[name="type"]:checked').val();
                    data.title = form.find('input[name="title"]').val();
                    data.image = form.find('input[name="image"]').val();
                    data.align = form.find('input[name="align"]:checked').val();
                    data.url = form.find('input[name="url"]').val();
                        
                    var content, image;
                    
                    if (data.type == 'link') {
                        image = data.title;
                    } else {
                        image = '<img src="' + data.image + '&size=' + data.type + '" />';
                    }
                    
                    content = '<div class="hj-embedded-content" style="float:' + data.align + ';padding:5px;">' +                   
                        '<a href="' + data.url + '" title="' + data.title + '">' +
                        image +
                        '</a>' +
                        '</div>';
                    
                    if (data.align == 'right') {
                        var content = '<div class="clearfix"><div style="float:left;padding:5px">Your text goes here</div>' + content + '</div>';
                    } else if (data.align == 'left') {
                        var content = '<div class="clearfix">' + content + '<div style="float:right;padding:5px;">Your text goes here</div></div>';
                    }
                    cldata.editor.hidePopups();
                    cldata.editor.execCommand('inserthtml', content, null, cldata.button);
                    cldata.editor.focus();
                });
            }
        };

		$.ajax({url:'<?php echo $vars['url'] . 'embed'; ?>',
			async: false,
			success: function(result) {
				$.cleditor.buttons.embed.popupContent = '<div class="popup"><div class="content">' + result + '</div></div>';
			}
		});
        // Add the button to the default controls
        $.cleditor.defaultOptions.controls = $.cleditor.defaultOptions.controls
        .replace("rule ", "rule embed ");
	
		// Table button click event handler
		window.current_editor = $.cleditor;

	})(jQuery);
</script>