/**
* Plugin for the tinyMCE editor
*
* NOTE: This script is based on the media plugin for tinymce, needs cleaning...
**/

tinyMCE.addI18n('{MCE_LANG}.kaltura',{
	desc : '{MCE_DESC}'
});

// Create a new plugin class
tinymce.create('tinymce.plugins.KalturaVideoPlugin', {
    init : function(ed, url) {
		var t = this;

		t.editor = ed;
		t.url = url;

		function isKalturaElm(n) {
			return /^(mceItemKaltura)$/.test(n.className);
		};

		ed.onPreInit.add(function() {
			// Force in _value parameter this extra parameter is required for older Opera versions
			ed.serializer.addRules('param[name|value|_mce_value]');
		});

        // Register an example button
        ed.addButton('kaltura', {
            title : 'kaltura.desc',
            onclick : function() {
				KalturaModal.openModal("TB_window", "{URL}mod/kaltura_video/kaltura/editor/init.php", { width: 240, height: 60 } );
				cwWidth = 680;
				cwHeight = 360;
				//Kaltura.animateModalSize(cwWidth, cwHeight);
            },
			title : 'kaltura.desc',
        	cmd : 'mceKaltura',
      		'class': 'mce_kaltura'
        });

		ed.onNodeChange.add(function(ed, cm, n) {
			cm.setActive('kaltura', n.nodeName == 'IMG' && isKalturaElm(n));
		});

		ed.onInit.add(function() {
			var lo = {
				mceItemKaltura : 'flash'
			};
/*
			ed.selection.onSetContent.add(function() {
				t._spansToImgs(ed.getBody());
			});

			ed.selection.onBeforeSetContent.add(t._objectsToSpans, t);
*/
			if (ed.settings.content_css !== false)
				ed.dom.loadCSS(url + "/css/content.css");

			if (ed.theme.onResolveName) {
				ed.theme.onResolveName.add(function(th, o) {
					if (o.name == 'img') {
						tinymce.each(lo, function(v, k) {
							if (ed.dom.hasClass(o.node, k)) {
								o.name = v;
								o.title = ed.dom.getAttrib(o.node, 'title');
								return false;
							}
						});
					}
				});
			}

			if (ed && ed.plugins.contextmenu) {
				ed.plugins.contextmenu.onContextMenu.add(function(th, m, e) {
					if (e.nodeName == 'IMG' && /mceItemKaltura/.test(e.className)) {
						m.add({title : 'kaltura.edit', icon : 'kaltura', cmd : 'mceKaltura'});
					}
				});
			}
		});

		ed.onBeforeSetContent.add(t._objectsToSpans, t);

		ed.onSetContent.add(function() {
			t._spansToImgs(ed.getBody());
		});

		ed.onPreProcess.add(function(ed, o) {
			var dom = ed.dom;

			if (o.set) {
				t._spansToImgs(o.node);

				tinymce.each(dom.select('IMG', o.node), function(n) {
					var p;

					if (isKalturaElm(n)) {
						p = t._parse(n.title);
						dom.setAttrib(n, 'width', dom.getAttrib(n, 'width', p.width || 100));
						dom.setAttrib(n, 'height', dom.getAttrib(n, 'height', p.height || 100));
					}
				});
			}

			if (o.get) {
				tinymce.each(dom.select('IMG', o.node), function(n) {
					var ci, cb, mt;

					if (ed.getParam('kaltura_use_script')) {
						if (isKalturaElm(n))
							n.className = n.className.replace(/mceItem/g, 'mceTemp');

						return;
					}

					switch (n.className) {
						case 'mceItemKaltura':
							ci = 'd27cdb6e-ae6d-11cf-96b8-444553540000';
							cb = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
							mt = 'application/x-shockwave-flash';
							break;
					}

					if (ci) {
						dom.replace(t._buildObj({
							classid : ci,
							codebase : cb,
							type : mt
						}, n), n);
					}
				});
			}
		});

		ed.onPostProcess.add(function(ed, o) {
			o.content = o.content.replace(/_mce_value=/g, 'value=');
		});

		if (ed.getParam('kaltura_use_script')) {
			function getAttr(s, n) {
				n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);

				return n ? ed.dom.decode(n[1]) : '';
			};

			ed.onPostProcess.add(function(ed, o) {
				o.content = o.content.replace(/<img[^>]+>/g, function(im) {
					var cl = getAttr(im, 'class');

					if (/^(mceTempKaltura)$/.test(cl)) {
						at = t._parse(getAttr(im, 'title'));
						at.width = getAttr(im, 'width');
						at.height = getAttr(im, 'height');
						im = '<script type="text/javascript">write' + cl.substring(7) + '({' + t._serialize(at) + '});<'+'/script>';
					}

					return im;
				});
			});
		}
    },
    // Meta info method
    getInfo : function() {
        return {
            longname : 'Kaltura Video Plugin',
            author : 'Ivan Vergés',
            authorurl : 'http://microstudi.net/elgg/',
            infourl : 'http://microstudi.net/elgg/',
            version : "{VERSION}"
        };
    },

	// Private methods
	_objectsToSpans : function(ed, o) {
		var t = this, h = o.content;

		h = h.replace(/<script[^>]*>\s*writeKaltura\(\{([^\)]*)\}\);\s*<\/script>/gi, function(a, b, c) {
			var o = t._parse(c);
			return '<img class="mceItem' + b + '" title="' + ed.dom.encode(c) + '" src="' + o.thumbnail + '" width="' + o.width + '" height="' + o.height + '" />'
		});

		h = h.replace(/<object([^>]*)>/gi, '<span class="mceItemObject" $1>');
		h = h.replace(/<embed([^>]*)\/?>/gi, '<span class="mceItemEmbed" $1></span>');
		h = h.replace(/<embed([^>]*)>/gi, '<span class="mceItemEmbed" $1>');
		h = h.replace(/<\/(object)([^>]*)>/gi, '</span>');
		h = h.replace(/<\/embed>/gi, '');
		h = h.replace(/<param([^>]*)>/gi, function(a, b) {return '<span ' + b.replace(/value=/gi, '_mce_value=') + ' class="mceItemParam"></span>'});
		h = h.replace(/\/ class=\"mceItemParam\"><\/span>/gi, 'class="mceItemParam"></span>');

		o.content = h;
	},

	_buildObj : function(o, n) {
		var ob, ed = this.editor, dom = ed.dom, p = this._parse(n.title), stc;

		stc = ed.getParam('kaltura_strict', true) && o.type == 'application/x-shockwave-flash';


		p.width = o.width = dom.getAttrib(n, 'width') || 100;
		p.height = o.height = dom.getAttrib(n, 'height') || 100;

		if (p.src)
			p.src = ed.convertURL(p.src, 'src', n);

		if (stc) {
			ob = dom.create('span', {
				mce_name : 'object',
				type : 'application/x-shockwave-flash',
				data : p.src,
				width : o.width,
				height : o.height
			});
		} else {
			ob = dom.create('span', {
				mce_name : 'object',
				classid : "clsid:" + o.classid,
				codebase : o.codebase,
				width : o.width,
				height : o.height
			});
		}

		tinymce.each (p, function(v, k) {
			if (!/^(width|height|codebase|classid|_cx|_cy)$/.test(k)) {
				if (v)
					dom.add(ob, 'span', {mce_name : 'param', name : k, '_mce_value' : v});
			}
		});

		if (!stc)
			dom.add(ob, 'span', tinymce.extend({mce_name : 'embed', type : o.type}, p));

		return ob;
	},

	_spansToImgs : function(p) {
		var t = this, dom = t.editor.dom, im, ci;

		tinymce.each(dom.select('span', p), function(n) {
			// Convert object into image
			if (dom.getAttrib(n, 'class') == 'mceItemObject') {
				ci = dom.getAttrib(n, "classid").toLowerCase().replace(/\s+/g, '');

				switch (ci) {
					case 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000':
						dom.replace(t._createImg('mceItemKaltura', n), n);
						break;

					default:
						dom.replace(t._createImg('mceItemKaltura', n), n);
				}

				return;
			}

			// Convert embed into image
			if (dom.getAttrib(n, 'class') == 'mceItemEmbed') {
				switch (dom.getAttrib(n, 'type')) {
					case 'application/x-shockwave-flash':
						dom.replace(t._createImg('mceItemKaltura', n), n);
						break;

					default:
						dom.replace(t._createImg('mceItemKaltura', n), n);
				}
			}
		});
	},

	_createImg : function(cl, n) {
		var im, dom = this.editor.dom, pa = {}, ti = '', args;

		args = ['id', 'name', 'width', 'height', 'bgcolor', 'align', 'flashvars', 'src', 'wmode', 'allowfullscreen', 'quality','allownetworking', 'allowscriptaccess'];

		// Setup base parameters
		tinymce.each(args, function(na) {
			var v = dom.getAttrib(n, na);

			if (v)
				pa[na] = v;
		});

		// Add optional parameters
		tinymce.each(dom.select('span', n), function(n) {
			if (dom.hasClass(n, 'mceItemParam'))
				pa[dom.getAttrib(n, 'name')] = dom.getAttrib(n, '_mce_value');
		});

		// Use src not movie
		if (pa.movie) {
			pa.src = pa.movie;
			delete pa.movie;
		}

		// Merge with embed args
		n = dom.select('.mceItemEmbed', n)[0];
		if (n) {
			tinymce.each(args, function(na) {
				var v = dom.getAttrib(n, na);

				if (v && !pa[na])
					pa[na] = v;
			});
		}

		// Create image
		im = dom.create('img', {
			src : pa.thumbnail,
			width : dom.getAttrib(n, 'width') || pa.width,
			height : dom.getAttrib(n, 'height') || pa.height,
			'class' : cl
		});

		delete pa.width;
		delete pa.height;

		im.title = this._serialize(pa);

		return im;
	},

	_parse : function(s) {
		return tinymce.util.JSON.parse('{' + s + '}');
	},

	_serialize : function(o) {
		return tinymce.util.JSON.serialize(o).replace(/[{}]/g, '');
	}
});

// Register plugin with a short name
tinymce.PluginManager.add('kaltura', tinymce.plugins.KalturaVideoPlugin);
KALTURA_TINYMCE_REGISTERED = true;

