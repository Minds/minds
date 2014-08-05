<?php
/* bookmarklet */

if (!elgg_is_logged_in()) {

$header = '';
$body = elgg_view('core/account/login_box');
$nolog = true;
} else {

	$url = get_input('url', 'false');
	$title = get_input('title', 'false');

	if (filter_var($url, FILTER_VALIDATE_URL)) {
		$url_tiny = goo_gl_short_url($url);
	} else {
		$url_tiny = 'badurl';
	}

	$content = $title . ' ' . $url_tiny;
	$body = '<div id="thewire">' . elgg_view_form('deck_river/wire_input', '', array('bookmarklet' => $content)) . '</div>';
	$body .= elgg_view('deck_river/bookmarklet/body', array(
		'url' => $url,
		'url_tiny' => $url_tiny,
		'title' => $title
	));

	$header = elgg_view('deck_river/bookmarklet/header');

	$user = elgg_get_logged_in_user_entity();

	$header .= '<div class="float-alt">';
	$header .= $user->username;
	$header .= elgg_view('output/img', array(
		'src' => elgg_format_url($user->getIconURL('tiny')),
		'title' => $user->username
	));
	$header .= '</div><span id="pin-thewire" class="elgg-icon elgg-icon-push-pin link float-alt pam prl tooltip n" title="';
	$header .= htmlspecialchars(elgg_echo('deck_river:thewire:bookmarklet:pinned')) . '"></span>';

}

// clear loaded external javascript and css
global $CONFIG;
//var_dump($CONFIG->externals_map['js']);
foreach($CONFIG->externals_map['js'] as $js) {
	$js->loaded = false;
}
//var_dump(elgg_get_loaded_js('head'));
//var_dump($CONFIG->views->extensions['js/elgg']);


$css = elgg_view('css/elements/reset');
$css .= elgg_view('css/elements/core');
$css .= elgg_view('css/elements/typography', $vars);
$css .= elgg_view('css/elements/forms', $vars);
$css .= elgg_view('css/elements/buttons', $vars);
$css .= elgg_view('css/elements/icons', $vars);
$css .= elgg_view('css/elements/grid', $vars);
//$css .= elgg_view('css/elements/navigation', $vars);
//$css .= elgg_view('css/elements/modules', $vars);
$css .= elgg_view('css/elements/components', $vars);
$css .= elgg_view('css/elements/layout', $vars);
//$css .= elgg_view('css/elements/misc', $vars);
$css .= elgg_view('deck_river/css');
$css .= elgg_view('ggouv_template/css');
$css .= elgg_view('markdown_ace_editor/css');
$css .= elgg_view('markdown_wiki/css');
$css .= elgg_view('markdown_wiki/markdown_css');
$css .= elgg_view('markdown_wiki/highlight_css');
$css .= elgg_view('bookmarks/css');
$css .= elgg_view('css/elements/helpers');


// bookmarklet_js doesn't exist. It's a empty view to be extended
elgg_extend_view('bookmarklet_js', 'js/elgg');
elgg_extend_view('bookmarklet_js', 'js/initialize_elgg');
elgg_extend_view('bookmarklet_js', 'markdown_wiki/js/js');
elgg_extend_view('bookmarklet_js', 'markdown_wiki/js/editor_js');
elgg_extend_view('bookmarklet_js', 'markdown_ace_editor/ace_ext', 1000);

elgg_load_js('jquery');
elgg_load_js('jquery-ui');
elgg_load_js('jquery.caretposition');
elgg_register_js('jquery.tipsy', "/mod/elgg-ggouv_template/vendors/jquery.tipsy.min.js");
elgg_load_js('jquery.tipsy');
elgg_register_js('ace', "/mod/elgg-markdown_ace_editor/vendors/ace-builds/src-min-noconflict/ace.js");
elgg_load_js('ace');
elgg_register_js('showdown', "/mod/elgg-markdown_wiki/vendors/showdown/compressed/showdown.js");
elgg_load_js('showdown');
elgg_register_js('showdownggouv', "/mod/elgg-markdown_wiki/vendors/showdown/compressed/extensions/showdownggouv.js");
elgg_load_js('showdownggouv');
elgg_register_js('highlight', "/mod/elgg-markdown_wiki/vendors/highlight/highlight.pack.js", 'head', 100);
elgg_load_js('highlight');
elgg_register_js('xoxco.tags',"/mod/elgg-ggouv_template/vendors/xoxco_tags/jquery.tagsinput.min.js");
elgg_load_js('xoxco.tags');
//elgg_register_js('elgg.autocomplete', "/mod/elgg-ggouv_template/views/default/js/lib/ui.autocomplete.js");
//elgg_load_js('elgg.autocomplete');
elgg_load_js('jquery.ui.autocomplete.html');


$body .= elgg_view('markdown_ace_editor/snippets');
$body .= elgg_view('markdown_wiki/syntax/language_selector');



header("Content-type: text/html; charset=UTF-8");
$lang = get_current_language();
$window_title = elgg_get_config('sitename');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>" class="bookmarklet">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

	<title><?php echo $window_title; ?></title>

	<style type="text/css">
		<?php echo $css; ?>
		body {
			position: fixed;
			overflow: hidden;
		}
		.elgg-bookmarklet-header {
			background: #1F2E3D;
			height: 36px;
			width: 795px;
			z-index: 9;
		}
		.elgg-menu-item-logo {
			margin: -5px 4px -5px 7px;
		}
		.elgg-menu-item-logo a {
			font-size: 50px;
		}
		#elgg-page-header-container {
			margin-left: -1px;
		}
		.elgg-page-header {
			height: 1000px;
		}
		#elgg-page-header-container .elgg-inner {
			width: 781px;
		}
		#thewire {
			width: 580px;
		}
		.elgg-inner-bookmarklet div.float-alt {
			color: white;
			font-weight: bold;
		}
		.elgg-inner-bookmarklet img {
			padding: 6px;
			vertical-align: middle;
		}
		.elgg-subtype {
			color: white;
			font-size: 4em;
			height: 36px;
		}
		.elgg-subtype > li {
			height: 36px;
			line-height: 36px;
			width: 52px;
			text-align: center;
			cursor: pointer;
		}
		.elgg-subtype > li:hover {
			color: #62ABE4;
			font-size: 60px;
		}
		.elgg-subtype .elgg-state-active {
			background-color: #62ABE4;
		}
		.elgg-subtype > li.elgg-state-active {
			color: white;
			font-size: 1em;
		}
		#thewire-header {
			height: 163px;
		}
		#thewire-network .selected-profile {
			border-radius: 6px;
		}
		#thewire-network .non-pinned {
			background: none;
			-webkit-box-shadow: none;
			box-shadow: none;
		}
		#thewire-network .net-profiles-wrapper {
			border-radius: 0 0 6px 6px;
		}
		#thewire-network .net-profiles {
			height: 135px;
		}
		.elgg-module-popup {
			z-index: 9999;
			margin-bottom: 0;
			padding: 5px;
		}
		.description {
			height: 191px;
		}
		#bookmarks > div {
			background: white;
			border-radius: 6px;
			height: 33px;
			-webkit-box-shadow: inset 0 2px 2px 0 #1F2E3D;
			-moz-box-shadow: inset 0 2px 2px 0 #1F2E3D;
			box-shadow: inset 0 2px 2px 0 #1F2E3D;
			height: 360px;
			padding: 10px;
		}
		.tagsinput {
			width: 220px !important;
		}
		#b-overlay {
			position: absolute;
			width: 100%;
			height: 380px;
			z-index: 999;
			top: 0;
			left: 0;
			background: rgba(255, 255, 255, 0.5);
			border-radius: 6px;
		}
		#b-overlay div {
			width: 33px;
			margin: 20% 47%;
		}
	</style>

</head>
<body>

	<div class="elgg-page-messages">
		<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
	</div>

	<div class="elgg-bookmarklet-header">
		<div class="elgg-inner-bookmarklet">
			<?php echo $header; ?>
		</div>
	</div>

	<div id="elgg-page-header-container">
		<div class="elgg-page-header">
			<div class="elgg-inner">
				<?php echo $body; ?>
			</div>
		</div>
	</div>

	<?php
		echo elgg_view('deck_river/mustaches/linkbox');
		foreach (elgg_get_loaded_js('head') as $script) {
			echo '<script type="text/javascript" src="' . $script . '"></script>';
		}
	?>

	<script type="text/javascript">
		<?php echo elgg_view('bookmarklet_js');
			if (!$nolog) {
		?>
		var FBappID = <?php echo elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river') ?>;
		var site_shorturl = <?php $site_shorturl = elgg_get_plugin_setting('site_shorturl', 'elgg-deck_river'); echo json_encode($site_shorturl ? $site_shorturl : false); ?>;
		elgg.provide('elgg.deck_river');
		<?php
			echo elgg_view('deck_river/js/thewire');
			echo elgg_view('deck_river/js/shortener_url');
			echo elgg_view('deck_river/js/tools');
		?>



		// Inintialize tooltips
		$('.tooltip').tipsy({
			live: true,
			offset: function() {
				if ($(this).hasClass('o8')) return 8;
				return 5;
			},
			fade: true,
			html: true,
			delayIn: 500,
			gravity: function() {
				var t = $(this);

				if (t.hasClass('nw')) return 'nw';
				if (t.hasClass('n')) return 'n';
				if (t.hasClass('ne')) return 'ne';
				if (t.hasClass('w')) return 'e';
				if (t.hasClass('e')) return 'e';
				if (t.hasClass('sw')) return 'sw';
				if (t.hasClass('s')) return 's';
				if (t.hasClass('se')) return 'se';
				return 'n';
			}
		});

elgg.provide('elgg.autocomplete');

elgg.autocomplete.init = function() {
	$('.elgg-input-autocomplete').each(function() {
		var source = $(this).attr('aria-source');

		$(this).autocomplete({
			source: source, //gets set by input/autocomplete view
			minLength: 2,
			html: "html",
			position: {
				my: "left bottom",
				at: "left top",
				collision: "none"
			},
			select: function( event, ui ) {
				$(this).val(ui.item.name);
				$(this).next().val(ui.item.value);
				return false;
			}
		}).removeAttr('name');
	});
};

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);

$(document).ready(function() {
		elgg.thewire.init();
$('#pin-thewire').live('click', function() { // should add this ??
	$(this).toggleClass('pinned');
});
		elgg.thewire.resize();
		elgg.thewire.textCounter();
		if ($('.elgg-form-deck-river-wire-input').find('input[name="networks[]"][data-scrap]').length) {
			linkParsed = '<?php echo $url; ?>';
			elgg.thewire.scrapToLinkBox(linkParsed);
		}

	$('.elgg-subtype > li').click(function() {
		$('.elgg-subtype > li').removeClass('elgg-state-active');
		$(this).addClass('elgg-state-active');
		$('.elgg-page-header .elgg-inner').children().hide();
		$('#'+$(this).data('tab')).show();
		if ($(this).data('tab') == 'thewire') {
			elgg.thewire.resize();
		} else {
			console.log(linkParsed);
				console.log('<?php echo $url; ?>');
				console.log($(this).data('tab'));
			if ($(this).data('tab') == 'bookmarks' && linkParsed != '<?php echo $url; ?>') {
				linkParsed = '<?php echo $url; ?>';
				elgg.thewire.scrapToTagsCloud('<?php echo $url; ?>');
			}
			window.resizeTo($('.elgg-bookmarklet-header').width(), $('#'+$(this).data('tab')).outerHeight()+103 + ($.browser.mozilla ? -2 : 0));
		}
	});

	$('select[name="where"]').change(function() {
		var $eia = $('.elgg-input-autocomplete');
		if ($(this).val() == 'me') {
			$eia.addClass('hidden').next().val($eia.data('original_value'));
		} else {
			$eia.removeClass('hidden').val('').next().val('');
		}
	});

	$('#bookmarks .elgg-button-submit').click(function() {
		elgg.action('bookmarks/save', {
			data: $(this).closest('form').serialize(),
			success: function(response) {
				if (response.status == 0 && !$('#pin-thewire').hasClass('pinned')) window.close();
			}
		});
		return false;
	});
});


/**
 * Parse link and add data to tags cloud
 * @param  {[type]} url the url
 */
elgg.thewire.scrapToTagsCloud = function(url) {
	elgg.thewire.scrapWebpage(url, {
		beforeSend: function() {
			$('#b-overlay').removeClass('hidden');
		},
		success: function(data) {
			$('#b-overlay').addClass('hidden');
			if (data) {
				if (data.metatags) {
					$.grep(data.metatags, function(e) {
						if (e[0] == 'description') data.description = $('<div>').html(e[1]).text();
					});
					if (data.description) {
						$('textarea[name="description"]').val(data.description);
					}

					$.grep(data.metatags, function(e) {
						if (e[0] == 'keywords') data.keywords = $('<div>').html(e[1]).text();
					});
					if (data.keywords) {
						var tags = '';
						$.each(data.keywords.split(','), function(i, e) {
							if (i == 10) return false;
							tags += '<li class="elgg-tag link float"><a href"#" onclick="$(\'.elgg-input-tags\').addTag(\''+$.trim(e)+'\');">'+$.trim(e)+'</a></li>';
						});
						$('.tags-in-page').removeClass('hidden').filter('ul').append(tags);
					}

				}

			}
		},
		error: function() {
			$('#b-overlay').addClass('hidden');
		}
	});
};



/*
 * Xoxco tags
 */
elgg.provide('elgg.tags');

elgg.tags.init = function() {
	var time = (new Date).getTime(),
		i = 0,
		tidyTags = function(e){
			var tags = ($(e.target).val() + ',' + e.tags).split(',');
			var target = $(e.target);
			target.importTags('');
			for(var i = 0, z = tags.length; i<z; i++){
				var tag = $.trim(tags[i]);
				if(!target.tagExist(tag)){
					target.addTag(tag);
				}
			}
			$('#' + target[0].id + '_tag').trigger('focus');
		};
	$('.elgg-input-tags').each(function () {
		if (! this.id) {
			this.id = 't' + time + '_' + i++;
		}
	}).tagsInput({
		width: '100%',
		height: 'auto',
		placeholderColor:'#666',
		defaultText: elgg.echo('xoxco:input:default'),
		onAddTag: function (tag) {
			if (tag.indexOf(',') > 0) {
				tidyTags({target: this, tags: tag});
			}
		}
	});
	$('.tagsinput').find('input').focusin(function() {
		$('.tagsinput').addClass('focus');
	}).focusout(function() {
		$('.tagsinput').removeClass('focus');
	});
};

elgg.register_hook_handler('init', 'system', elgg.tags.init);



		<?php } ?>
	</script>

	<?php //echo elgg_view('page/elements/foot'); ?>

</body>
</html>

