<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>
    
	elgg.provide('hj.framework.editor');
       
	hj.framework.editor.init = function() {
		$('.elgg-input-longtext')
		.live('focus', function(event) {
			event.stopPropagation();
			$(this)
			.cleditor({width:'100%'});
		});
	};
    
	elgg.register_hook_handler('init', 'system', hj.framework.editor.init);
	elgg.register_hook_handler('success', 'hj:framework:ajax', hj.framework.editor.init);
    
	elgg.provide('elgg.embed');

	elgg.embed.init = function() {

		// caches the current textarea id
		$(".embed-control").live('click', function() {
			var classes = $(this).attr('class');
			var embedClass = classes.split(/[, ]+/).pop();
			var textAreaId = embedClass.substr(embedClass.indexOf('embed-control-') + "embed-control-".length);
			elgg.embed.textAreaId = textAreaId;
		});

		// special pagination helper for lightbox
		$('.embed-wrapper .elgg-pagination a').live('click', elgg.embed.forward);

		$('.embed-section').live('click', elgg.embed.forward);

		$('.elgg-form-embed').live('submit', elgg.embed.submit);
	};


	/**
	 * Submit an upload form through Ajax
	 *
	 * Requires the jQuery Form Plugin. Because files cannot be uploaded with
	 * XMLHttpRequest, the plugin uses an invisible iframe. This results in the
	 * the X-Requested-With header not being set. To work around this, we are
	 * sending the header as a POST variable and Elgg's code checks for it in
	 * elgg_is_xhr().
	 *
	 * @param {Object} event
	 * @return bool
	 */
	elgg.embed.submit = function(event) {
	
		$(this).ajaxSubmit({
			dataType : 'json',
			data     : { 'X-Requested-With' : 'XMLHttpRequest'},
			success  : function(response) {
				if (response) {
					if (response.system_messages) {
						elgg.register_error(response.system_messages.error);
						elgg.system_message(response.system_messages.success);
					}
					if (response.status >= 0) {
						var forward = $('input[name=embed_forward]').val();
						var url = elgg.normalize_url('embed/tab/' + forward);
						$('.embed-wrapper').parent().load(url);
					}
				}
			}
		});

		// this was bubbling up the DOM causing a submission
		event.preventDefault();
		event.stopPropagation();
	};

	/**
	 * Loads content within the lightbox
	 *
	 * @param {Object} event
	 * @return void
	 */
	elgg.embed.forward = function(event) {
		$('.embed-wrapper').parent().load($(this).attr('href'));
		event.preventDefault();
	};

	elgg.register_hook_handler('init', 'system', elgg.embed.init);

<?php if (FALSE) : ?></script><?php endif; ?>