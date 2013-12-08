/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-deck_river
 *
 *	Elgg-deck_river tools js
 *
 */


/**
 * Return column settings for given column
 * @param  {element}   TheColumn    the jQuery element of the column
 * @return {object}                 the user settings
 */
elgg.deck_river.getColumnSettings = function(TheColumn) {
	if (TheColumn.attr('id').match(/^column/)) {
		var tab = TheColumn.closest('#deck-river-lists').data('tab'),
			column = TheColumn.attr('id') || TheColumn.closest('.column-river').attr('id'),
			dRS = deckRiverSettings[tab][column];

			// insert tab and column name in column settings. Aka helper.
			dRS.tab = tab;
			dRS.column = column;
		return dRS;
	} else { // this is a popup or other.
		return TheColumn.find('.column-header').data();
	}
};



/**
 * Set column settings for given column
 * @param  {element}   TheColumn    the jQuery element of the column
 * @param  {object}    Data         object of new settings
 * @return {object}                 the user settings
 */
elgg.deck_river.setColumnSettings = function(TheColumn, data) {
	deckRiverSettings[TheColumn.closest('#deck-river-lists').data('tab')][TheColumn.attr('id')] = data;
};


/**
 * Users storage
 * @todo use html5 localstorage ?
 */


// Global var for Entities : users and groups from elgg, users from Twitter
var DataEntities = DataEntities || {elgg: [], twitter: [], facebook: []};

/**
 * Put users and groups in global var DataEntities
 */
elgg.deck_river.storeEntity = function(entity, network) {
	var network = network || 'elgg';

	if (network == 'elgg') {
		if (!$.grep(DataEntities.elgg, function(e){ return e.guid === entity.guid; }).length) DataEntities.elgg.push(entity);
	} else if (network == 'twitter') {
		// Put user in global var DataEntities.twitter
		if (DataEntities.twitter.length) {
			var found = false;
			$.each(DataEntities.twitter, function(i, e) {
				if (e.screen_name === entity.screen_name) { // the same !
					if (!elgg.isUndefined(entity.id_str)) { // new user is complete
						DataEntities.twitter[i] = entity; // We can fill more the profile !
						found = true;
						return false;
					}
					found = true;
				}
			});
			if (!found) DataEntities.twitter.push(entity); // new
		} else {
			DataEntities.twitter.push(entity); // new
		}
	} else if (network == 'facebook') {
		if (!$.grep(DataEntities.facebook, function(e){ return e.uid === entity.uid; }).length) DataEntities.facebook.push(entity);
	}
};



/**
 * Find a user in DataEntities, query
 * @param  {[type]} name    [description]
 * @param  {[type]} network [description]
 * @return {[type]}         [description]
 */
elgg.deck_river.findUser = function(name, network, key) {
	var network = network || 'elgg',
		key = key || null;

	if (!key) {
		if (network == 'elgg') {
			key = 'username';
		} else if (network == 'twitter') {
			key = 'screen_name';
		} else if (network == 'facebook') {
			key = 'uid';
		}
	}

	return $.grep(DataEntities[network], function(e) {
		return e[key] == name;
	})[0];
};



/**
 * Search users in DataEntities (eg: twitt return all user with name started by 'twitt')
 * @param  {string}  query    The name of the user to match
 * @param  {string}  network  The network to search, default Elgg
 * @return {array}            An array of matches
 */
elgg.deck_river.searchUsers = function(query, network, key) {
	var network = network || 'elgg',
		key = key || 'username';

	if (network == 'twitter') key = key || 'screen_name';
	if (network == 'all') {
		var ret = [];
		$.each(DataEntities, function(e) {
			$.extend(ret, elgg.deck_river.searchUsers(query, e));
		});
		return ret;
	} else {
		return $.grep(DataEntities[network], function(e) {
			return e[key].match(new RegExp(query+'.*', 'i'));
		});
	}
};



/**
 * Called after a river call to resize images who are bigger than column width
 * @return {[type]} [description]
 */
elgg.deck_river.resizeRiverImages = function() {
	$.each($('.elgg-page-body #deck-river-lists .elgg-river-image .elgg-image'), function(i, e) {
		var s = $(e).data('img'),
			$eri = $(e).parent();

		if (!s) {
			var url = $(e).css('background-image').slice(4, -1),
				img = new Image();

			img.src = url;
			s = [img.width, img.height];
		}
		//if (s && (s[0] >= $eri.width() || s[0] >= 600 || $eri.find('.elgg-body').html().replace(/\s+/, '') == '')) {
		if ((s[0] >= $eri.width() || s[0] >= 600)) {
			$(e).height(Math.min($eri.addClass('big').width(),'600')/s[0]*s[1]);
		} else {
			$eri.removeAttr('big')
		}
	});
};



/**
 * String tools
 */



String.prototype.FormatDate = function() {
	return $.datepicker.formatDate('@', new Date(this))/1000;
};

String.prototype.ParseURL = function(reduce, videopopup) {
	return this.replace(/\s+/g, ' ').replace(/(.{2})?((?:https?:\/\/|www\.)[A-Za-z0-9-_]+\.[A-Za-z0-9-_:,%&\?\/.=~+]+)/g, function(match, pre, url) {
		if (pre == '="') return pre+url;
		if (elgg.isUndefined(pre)) pre = '';
		if (/^www/.test(url)) {
			var href = 'http://'+url;
		} else {
			var href = url;
		}
		if (reduce) {
			url = url.replace(/https?:\/\//, '');
			if (url.length > 35) url = url.substr(0, 32)+'…';
		}
		var iframeUrl = null;
		if (videopopup && (iframeUrl = elgg.deck_river.setVideoURLToIframe(href))) {
			return pre+'<a class="media-video-popup" href="'+href+'" onclick="javascript:void(0)" data-source="'+iframeUrl+'">'+url+'</a>';
		} else {
			return pre+'<a target="_blank" rel="nofollow" href="'+href+'">'+url+'</a>';
		}
	});
};

String.prototype.ParseTwitterURL = function(entities) {
	var text = this,
		urls = [],
		replaceEntities = function(type) {
			$.each(entities[type], function(i, e) {
				var token = (Math.random()+'xxxxxxxxxxxxxxxx').replace('.', '').substr(0, e.indices[1]-e.indices[0]),
					url = '',
					iframeUrl = null;

				if (type == 'media') {
					url = '<a class="media-image-popup" href="'+e.media_url_https+'" onclick="javascript:void(0)" data-media="'+e.media_url_https+'" data-type="'+e.type+'" data-size_width="'+e.sizes.medium.w+'" data-size_height="'+e.sizes.medium.h+'">'+e.display_url+'</a>';
				} else if (iframeUrl = elgg.deck_river.setVideoURLToIframe(e.expanded_url)) {
					url = '<a class="media-video-popup" href="'+e.expanded_url+'" onclick="javascript:void(0)" data-source="'+iframeUrl+'">'+e.display_url+'</a>';
				} else {
					url = '<a target="_blank" rel="nofollow" href="'+e.expanded_url+'">'+e.display_url+'</a>';
				}

				urls.push({
					token: token,
					url: url
				});
				text = text.substr(0, e.indices[0]) + token + text.substr(e.indices[1], text.length);
			});
		};

	if (entities.urls) replaceEntities('urls');
	if (entities.media) replaceEntities('media');
	$.each(urls, function(i, e) {
		text = text.replace(e.token, e.url);
	});
	return text.ParseURL(); // some link are not in entities
};

/**
 * Linkify youtube URLs which are not already links.
 *
 * http://stackoverflow.com/questions/5830387/how-to-find-all-youtube-video-ids-in-a-string-using-a-regex?lq=1
 * @param  {[type]} url to check for video
 * @return {[type]}      url for iframe video
 */
elgg.deck_river.setVideoURLToIframe = function(url) {
	var yt = /https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube\.com\S*[^\w\-\s])([\w\-]{11})(?=[^\w\-\=]|$)(?![?=&+%\w.-]*(?:['"][^<>]*>|<\/a>))[?=&+%\w.-]*/i,
		vo = /https?:\/\/(?:www\.)?(?:vimeo\.com\/)(?:(?:channels\/[A-z]+\/)|(?:groups\/[A-z]+\/videos\/))?(\d+)/,
		id = null;

	if (id = url.match(yt)) {
		return '//youtube.com/v/'+id[1]+'?autoplay=1';
	} if (id = url.match(vo)) {
		return '//player.vimeo.com/video/'+id[1]+'?autoplay=1';
	} else {
		return null;
	}
};

String.prototype.ParseGroup = function() {
	return this.replace(/(\s|^|>)(![A-Za-z0-9-_-àâæéèêëîïôöœùûüç]+)/g, function(match, pre, group) {
		return pre+'<a href="#" class="group-info-popup info-popup" title="'+group.replace("!", "")+'">'+group+'</a>';
	});
};

String.prototype.ParseUsername = function(network) {
	return this.replace(/(\W|^)(@[A-Za-z0-9-_]+)/g, function(match, pre, user) {
		return pre+'<a href="#" class="'+network+'-user-info-popup info-popup" title="'+user.replace("@", "")+'">'+user+'</a>';
	});
};

String.prototype.ParseHashtag = function(network) {
	return this.replace(/(\s|^)(#[A-Za-z0-9_-àâæéèêëîïôöœùûüç]+)/g, function(h, pre, hashtag) {
		return pre+'<a href="#" class="hashtag-info-popup" title="'+hashtag+'" data-network="'+network+'">'+hashtag+'</a>';
	});
};

String.prototype.ParseEverythings = function(network) {
	return this.ParseURL().ParseUsername(network).ParseHashtag(network);
};

String.prototype.TruncateString = function(length, more) {
	var length = length || 140,
		more = more || '[...]',
		trunc = '';

	do {
		length++;
		trunc = this.substring(0, length);
	} while (trunc.length !== this.length && trunc.slice(-1) != ' ');
	if (length+100 < this.length) {
		var rand = (Math.random()+"").replace('.','');
		return this.substring(0, length-1) +
				'<span id="text-part-'+rand+'" class="hidden">' + this.substring(length-1, this.length) + '</span>' +
				'<a rel="toggle" href="#text-part-'+rand+'"> ' + more + '</a>';
	} else {
		return this;
	}
};

String.prototype.addToLargeInt = function (value) {
	return this.substr(0, this.length-3)+(parseInt(this.substr(-3)) + value);
};

String.prototype.getWireLength = function(urls) {
	var urls_length = 0;

	if (urls) {
		$.each(urls, function(i, e) {
			urls_length += e.length;
		});
		return this.length - urls_length;
	} else {
		return this.length;
	}
};

/**
 * Returns the length of Tweet text with consideration to t.co URL replacement
 * and chars outside the basic multilingual plane that use 2 UTF16 code points
 * These come from https://api.twitter.com/1/help/configuration.json
 * described by https://dev.twitter.com/docs/api/1/get/help/configuration
 *
 * @param  {array}  urls    an array of urls in the text
 * @return {integer}        length of the text
 */
String.prototype.getTweetLength = function(urls) {
	var urls_length = 0,
		tco_urls_length = 0;

	if (urls) {
		$.each(urls, function(i, e) {
			urls_length += e.length;
			/^https/.test(e) ? tco_urls_length += 23 : tco_urls_length += 22;
		});
		return this.length - urls_length + tco_urls_length;
	} else {
		return this.length;
	}
};


/**
 * Update each minute all friendly times
 *
 */
elgg.provide('elgg.friendly_time');

elgg.friendly_time = function(time) {

	//TODO friendly:time hook

	diff = new Date().getTime()/1000 - parseInt(time);

	minute = 60;
	hour = minute * 60;
	day = hour * 24;

	if (diff < minute) {
			return elgg.echo("friendlytime:justnow");
	} else if (diff < hour) {
		diff = Math.round(diff / minute);
		if (diff == 0) {
			diff = 1;
		}

		if (diff > 1) {
			return elgg.echo("friendlytime:minutes", [diff]);
		} else {
			return elgg.echo("friendlytime:minutes:singular", [diff]);
		}
	} else if (diff < day) {
		diff = Math.round(diff / hour);
		if (diff == 0) {
			diff = 1;
		}

		if (diff > 1) {
			return elgg.echo("friendlytime:hours", [diff]);
		} else {
			return elgg.echo("friendlytime:hours:singular", [diff]);
		}
	} else {
		diff = Math.round(diff / day);
		if (diff == 0) {
			diff = 1;
		}

		if (diff > 1) {
			return elgg.echo("friendlytime:days", [diff]);
		} else {
			return elgg.echo("friendlytime:days:singular", [diff]);
		}
	}
}

elgg.friendly_time.update = function() {
	$('.elgg-page .elgg-friendlytime').each(function(){
		var acronym = $(this).find('acronym');
		acronym.html(elgg.friendly_time(acronym.attr('time')));
	});
}

elgg.friendly_time.init = function() {
	elgg.friendly_time.update();
	setInterval(elgg.friendly_time.update, 1000*60); // each 60 sec
};
elgg.register_hook_handler('init', 'system', elgg.friendly_time.init);




/**
 * FACEBOOK
 */



/**
 * Initialise Facebook javascript SDK
 * @return void
 */
var FBloaded = 0; // var to prevent already been called.
var FBstackCallback = []; // an array of functions that will be executed after FB init ready
elgg.deck_river.initFacebook = function() {
	if (!FBloaded) {
		$.ajaxSetup({ cache: true });
		$.getScript('//connect.facebook.net/en_UK/all.js', function(){
			FBloaded = 1;
			FB.init({
				appId: FBappID,
				channelUrl: elgg.get_site_url()+'mod/elgg-deck_river/lib/channel.php',
				oauth: true,
				cookie: true
			});
			$.each(FBstackCallback, function(i, e) {
				e();
			})
		});
	}
};



/**
 * Ajax call to Facebook API
 * @param {[type]}   account  ID of account
 * @param {[type]}   query    the query
 * @param {[type]}   params   params
 * @param {Function} callback a function to execute
 */
elgg.deck_river.FBajax = function(account, query, params, callback, method) {
	FB.api(account+'/'+query, method, params, function(response) {
		if (response) {
			callback(response);
		} else {
			elgg.register_error(elgg.echo('deck_river:facebook:error'));
		}
	});
};



/**
 * Ajax get to Facebook API
 * @param {[type]}   account  ID of account
 * @param {[type]}   query    the query
 * @param {[type]}   token    the token of the account
 * @param {Function} callback a function to execute
 */
elgg.deck_river.FBget = function(account, query, token, callback) {
	elgg.deck_river.FBajax(account, query, {access_token: token}, callback, 'GET');
};



/**
 * Ajax get to Facebook API
 * @param {[type]}   object     ID of object
 * @param {[type]}   query      the query
 * @param {[type]}   params     params to pass in POST
 * @param {Function} callback a function to execute
 */
elgg.deck_river.FBpost = function(object, query, params, callback) {
	elgg.deck_river.FBajax(object, query, params, callback, 'POST');
};



/**
 * Ajax get to Facebook API
 * @param {[type]}   object     ID of object
 * @param {[type]}   query      the query
 * @param {[type]}   params     params to pass in POST
 * @param {Function} callback a function to execute
 */
elgg.deck_river.FBdelete = function(object, query, params, callback) {
	elgg.deck_river.FBajax(object, query, params, callback, 'DELETE');
};


/**
 * Ajax get to Facebook API
 * @param {[string]}   token      facebook token
 * @param {[object]}   query      object containing select, from and where clauses
 * @param {Function}   callback   a function to execute
 */
elgg.deck_river.FBfql = function(token, query, callback) {
	FB.api({
		method: 'fql.query',
		query: 'SELECT '+query.select+' FROM '+query.from+' WHERE '+query.where+(query.limit?' LIMIT '+query.limit:''),
		access_token: token
	}, function(data) {
		if (data) {
			callback(data);
		} else {
			elgg.register_error(elgg.echo('deck_river:facebook:error'));
		}
	});
};



/**
 * Register facebook error
 * @param {[type]}   code   facebook code error
 */
elgg.deck_river.FBregister_error = function(code) {
	var echo = '';
	if (elgg.echo('deck_river:facebook:error:'+code) == 'deck_river:facebook:error:'+code) {
		echo = elgg.echo('deck_river:facebook:error:code', [code]);
	} else {
		echo = elgg.echo('deck_river:facebook:error:'+code);
	}
	elgg.register_error(echo);
};



/**
 * Ugly way to get facebook token. Used in popup.
 */
elgg.deck_river.FBgetToken = function() {
	var token;
	$.each(deckRiverSettings, function(i, e) {
		$.each(e, function(j,f) {
			if (f.network == 'facebook' && (token = f.token)) return false;
		});
	});
	return token;
};



/**
 * Clean some data from FB user. Store cleaned user's data, store it and return it.
 * @param {[type]} userData [description]
 */
elgg.deck_river.FBformatUser = function(userData) {
	$.each(['friend_count', 'mutual_friend_count', 'subscriber_count', 'likes_count', 'wall_count', 'notes_count'], function(i, e) {
		if (elgg.isNullOrUndefined(userData[e])) userData[e] = '-';
	});
	if (userData.profile_update_time) userData.profile_update_time = elgg.friendly_time(userData.profile_update_time);
	if (userData.friend_count == userData.mutual_friend_count) delete userData.mutual_friend_count; // this is owner profile
	if (userData.website) {
		if (/^www/.test(userData.website)) userData.website = 'http://'+userData.website;
		userData.website = userData.website.ParseURL();
	}
	elgg.deck_river.storeEntity(userData, 'facebook');

	return userData;
}


// functions not used
FBgraph = function(query, callback) {
	$.ajax({
		url: 'https://graph.facebook.com/' + query,
		dataType: 'json',
	})
	.done(function(rep) {
		callback(rep);
	})
	.fail(function() {
		return false;
	});
};

FBfql = function(query, callback) { //.replace(/foo/g, "bar")
	$.ajax({
		url: 'https://graph.facebook.com/' + query,
		dataType: 'json',
	})
	.done(function(rep) {
		callback(rep);
	})
	.fail(function() {
		return false;
	});
};






/*! Installing mustache for waiting which MVC elgg core team going to choose.
 * mustache.js - Logic-less {{mustache}} templates with JavaScript
 * http://github.com/janl/mustache.js
 */
(function(a,b){if(typeof exports==="object"&&exports){module.exports=b}else{if(typeof define==="function"&&define.amd){define(b)}else{a.Mustache=b}}}(this,(function(){var v={};v.name="mustache.js";v.version="0.7.2";v.tags=["{{","}}"];v.Scanner=t;v.Context=r;v.Writer=p;var d=/\s*/;var k=/\s+/;var h=/\S/;var g=/\s*=/;var m=/\s*\}/;var s=/#|\^|\/|>|\{|&|=|!/;var i=RegExp.prototype.test;var u=Object.prototype.toString;function n(y,x){return i.call(y,x)}function f(x){return !n(h,x)}var j=Array.isArray||function(x){return u.call(x)==="[object Array]"};function e(x){return x.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")}var c={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;","/":"&#x2F;"};function l(x){return String(x).replace(/[&<>"'\/]/g,function(y){return c[y]})}v.escape=l;function t(x){this.string=x;this.tail=x;this.pos=0}t.prototype.eos=function(){return this.tail===""};t.prototype.scan=function(y){var x=this.tail.match(y);if(x&&x.index===0){this.tail=this.tail.substring(x[0].length);this.pos+=x[0].length;return x[0]}return""};t.prototype.scanUntil=function(y){var x,z=this.tail.search(y);switch(z){case -1:x=this.tail;this.pos+=this.tail.length;this.tail="";break;case 0:x="";break;default:x=this.tail.substring(0,z);this.tail=this.tail.substring(z);this.pos+=z}return x};function r(x,y){this.view=x;this.parent=y;this._cache={}}r.make=function(x){return(x instanceof r)?x:new r(x)};r.prototype.push=function(x){return new r(x,this)};r.prototype.lookup=function(x){var A=this._cache[x];if(!A){if(x=="."){A=this.view}else{var z=this;while(z){if(x.indexOf(".")>0){A=z.view;var B=x.split("."),y=0;while(A&&y<B.length){A=A[B[y++]]}}else{A=z.view[x]}if(A!=null){break}z=z.parent}}this._cache[x]=A}if(typeof A==="function"){A=A.call(this.view)}return A};function p(){this.clearCache()}p.prototype.clearCache=function(){this._cache={};this._partialCache={}};p.prototype.compile=function(z,x){var y=this._cache[z];if(!y){var A=v.parse(z,x);y=this._cache[z]=this.compileTokens(A,z)}return y};p.prototype.compilePartial=function(y,A,x){var z=this.compile(A,x);this._partialCache[y]=z;return z};p.prototype.getPartial=function(x){if(!(x in this._partialCache)&&this._loadPartial){this.compilePartial(x,this._loadPartial(x))}return this._partialCache[x]};p.prototype.compileTokens=function(z,y){var x=this;return function(A,C){if(C){if(typeof C==="function"){x._loadPartial=C}else{for(var B in C){x.compilePartial(B,C[B])}}}return o(z,x,r.make(A),y)}};p.prototype.render=function(z,x,y){return this.compile(z)(x,y)};function o(E,y,x,H){var B="";var z,F,G;for(var C=0,D=E.length;C<D;++C){z=E[C];F=z[1];switch(z[0]){case"#":G=x.lookup(F);if(typeof G==="object"){if(j(G)){for(var A=0,J=G.length;A<J;++A){B+=o(z[4],y,x.push(G[A]),H)}}else{if(G){B+=o(z[4],y,x.push(G),H)}}}else{if(typeof G==="function"){var I=H==null?null:H.slice(z[3],z[5]);G=G.call(x.view,I,function(K){return y.render(K,x)});if(G!=null){B+=G}}else{if(G){B+=o(z[4],y,x,H)}}}break;case"^":G=x.lookup(F);if(!G||(j(G)&&G.length===0)){B+=o(z[4],y,x,H)}break;case">":G=y.getPartial(F);if(typeof G==="function"){B+=G(x)}break;case"&":G=x.lookup(F);if(G!=null){B+=G}break;case"name":G=x.lookup(F);if(G!=null){B+=v.escape(G)}break;case"text":B+=F;break}}return B}function w(D){var y=[];var C=y;var E=[];var A;for(var z=0,x=D.length;z<x;++z){A=D[z];switch(A[0]){case"#":case"^":E.push(A);C.push(A);C=A[4]=[];break;case"/":var B=E.pop();B[5]=A[2];C=E.length>0?E[E.length-1][4]:y;break;default:C.push(A)}}return y}function a(C){var z=[];var B,y;for(var A=0,x=C.length;A<x;++A){B=C[A];if(B){if(B[0]==="text"&&y&&y[0]==="text"){y[1]+=B[1];y[3]=B[3]}else{y=B;z.push(B)}}}return z}function q(x){return[new RegExp(e(x[0])+"\\s*"),new RegExp("\\s*"+e(x[1]))]}v.parse=function(N,D){N=N||"";D=D||v.tags;if(typeof D==="string"){D=D.split(k)}if(D.length!==2){throw new Error("Invalid tags: "+D.join(", "))}var H=q(D);var z=new t(N);var F=[];var E=[];var C=[];var O=false;var M=false;function L(){if(O&&!M){while(C.length){delete E[C.pop()]}}else{C=[]}O=false;M=false}var A,y,G,I,B;while(!z.eos()){A=z.pos;G=z.scanUntil(H[0]);if(G){for(var J=0,K=G.length;J<K;++J){I=G.charAt(J);if(f(I)){C.push(E.length)}else{M=true}E.push(["text",I,A,A+1]);A+=1;if(I=="\n"){L()}}}if(!z.scan(H[0])){break}O=true;y=z.scan(s)||"name";z.scan(d);if(y==="="){G=z.scanUntil(g);z.scan(g);z.scanUntil(H[1])}else{if(y==="{"){G=z.scanUntil(new RegExp("\\s*"+e("}"+D[1])));z.scan(m);z.scanUntil(H[1]);y="&"}else{G=z.scanUntil(H[1])}}if(!z.scan(H[1])){throw new Error("Unclosed tag at "+z.pos)}B=[y,G,A,z.pos];E.push(B);if(y==="#"||y==="^"){F.push(B)}else{if(y==="/"){if(F.length===0){throw new Error('Unopened section "'+G+'" at '+A)}var x=F.pop();if(x[1]!==G){throw new Error('Unclosed section "'+x[1]+'" at '+A)}}else{if(y==="name"||y==="{"||y==="&"){M=true}else{if(y==="="){D=G.split(k);if(D.length!==2){throw new Error("Invalid tags at "+A+": "+D.join(", "))}H=q(D)}}}}}var x=F.pop();if(x){throw new Error('Unclosed section "'+x[1]+'" at '+z.pos)}E=a(E);return w(E)};var b=new p();v.clearCache=function(){return b.clearCache()};v.compile=function(y,x){return b.compile(y,x)};v.compilePartial=function(y,z,x){return b.compilePartial(y,z,x)};v.compileTokens=function(y,x){return b.compileTokens(y,x)};v.render=function(z,x,y){return b.render(z,x,y)};v.to_html=function(A,y,z,B){var x=v.render(A,y,z);if(typeof B==="function"){B(x)}else{return x}};return v}())));



/**
 * Function serializeObject
 * Copied from https://github.com/macek/jquery-serialize-object
 * Version 1.0.0
 */
(function(f){return f.fn.serializeObject=function(){var k,l,m,n,p,g,c,h=this;g={};c={};k=/^[a-zA-Z_][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/;l=/[a-zA-Z0-9_]+|(?=\[\])/g;m=/^$/;n=/^\d+$/;p=/^[a-zA-Z0-9_]+$/;this.build=function(d,e,a){d[e]=a;return d};this.push_counter=function(d){void 0===c[d]&&(c[d]=0);return c[d]++};f.each(f(this).serializeArray(),function(d,e){var a,c,b,j;if(k.test(e.name)){c=e.name.match(l);b=e.value;for(j=e.name;void 0!==(a=c.pop());)m.test(a)?(a=RegExp("\\["+a+"\\]$"),j=
j.replace(a,""),b=h.build([],h.push_counter(j),b)):n.test(a)?b=h.build([],a,b):p.test(a)&&(b=h.build({},a,b));return g=f.extend(!0,g,b)}});return g}})(jQuery);


Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};




/**
 * jQuery plugin for getting position of cursor in textarea

 * @license under GNU license
 * @author Bevis Zhao (i@bevis.me, http://bevis.me)
 */
$(function() {

	var calculator = {
		// key styles
		primaryStyles: ['fontFamily', 'fontSize', 'fontWeight', 'fontVariant', 'fontStyle',
			'paddingLeft', 'paddingTop', 'paddingBottom', 'paddingRight',
			'marginLeft', 'marginTop', 'marginBottom', 'marginRight',
			'borderLeftColor', 'borderTopColor', 'borderBottomColor', 'borderRightColor',
			'borderLeftStyle', 'borderTopStyle', 'borderBottomStyle', 'borderRightStyle',
			'borderLeftWidth', 'borderTopWidth', 'borderBottomWidth', 'borderRightWidth',
			'line-height', 'outline'],

		specificStyle: {
			'word-wrap': 'break-word',
			'overflow-x': 'hidden',
			'overflow-y': 'auto'
		},

		simulator : $('<div id="textarea_simulator"/>').css({
				position: 'absolute',
				top: 0,
				left: 0,
				visibility: 'hidden'
			}).appendTo(document.body),

		toHtml : function(text) {
			return text.replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g, '<br>&nbsp;')
				.split(' ').join('<span style="white-space:prev-wrap">&nbsp;</span>');
		},
		// calculate position
		getCaretPosition: function() {
			var cal = calculator, self = this, element = self[0], elementOffset = self.offset();

			// IE has easy way to get caret offset position
			if ($.browser.msie) {
				// must get focus first
				element.focus();
			    var range = document.selection.createRange();
			    $('#hskeywords').val(element.scrollTop);
			    return {
			        left: range.boundingLeft - elementOffset.left,
			        top: parseInt(range.boundingTop) - elementOffset.top + element.scrollTop
						+ document.documentElement.scrollTop + parseInt(self.getComputedStyle("fontSize"))
			    };
			}
			cal.simulator.empty();
			// clone primary styles to imitate textarea
			$.each(cal.primaryStyles, function(index, styleName) {
				self.cloneStyle(cal.simulator, styleName);
			});

			// caculate width and height
			cal.simulator.css($.extend({
				'width': self.width(),
				'height': self.height()
			}, cal.specificStyle));

			var value = self.val(), cursorPosition = self.getCursorPosition();
			var beforeText = value.substring(0, cursorPosition),
				afterText = value.substring(cursorPosition);

			var before = $('<span class="before"/>').html(cal.toHtml(beforeText)),
				focus = $('<span class="focus"/>'),
				after = $('<span class="after"/>').html(cal.toHtml(afterText));

			cal.simulator.append(before).append(focus).append(after);
			var focusOffset = focus.offset(), simulatorOffset = cal.simulator.offset();
			// alert(focusOffset.left  + ',' +  simulatorOffset.left + ',' + element.scrollLeft);
			return {
				top: focusOffset.top - simulatorOffset.top - element.scrollTop,
					// calculate and add the font height except Firefox
					//+ ($.browser.mozilla ? 0 : parseInt(self.getComputedStyle("fontSize"))),
				left: focus[0].offsetLeft -  cal.simulator[0].offsetLeft - element.scrollLeft
			};
		}
	};

	$.fn.extend({
		getComputedStyle: function(styleName) {
			if (this.length == 0) return;
			var thiz = this[0];
			var result = this.css(styleName);
			result = result || ($.browser.msie ?
				thiz.currentStyle[styleName]:
				document.defaultView.getComputedStyle(thiz, null)[styleName]);
			return result;
		},
		// easy clone method
		cloneStyle: function(target, styleName) {
			var styleVal = this.getComputedStyle(styleName);
			if (!!styleVal) {
				$(target).css(styleName, styleVal);
			}
		},
		cloneAllStyle: function(target, style) {
			var thiz = this[0];
			for (var styleName in thiz.style) {
				var val = thiz.style[styleName];
				typeof val == 'string' || typeof val == 'number'
					? this.cloneStyle(target, styleName)
					: NaN;
			}
		},
		getCursorPosition : function() {
	        var thiz = this[0], result = 0;
	        if ('selectionStart' in thiz) {
	            result = thiz.selectionStart;
	        } else if('selection' in document) {
	        	var range = document.selection.createRange();
	        	if (parseInt($.browser.version) > 6) {
		            thiz.focus();
		            var length = document.selection.createRange().text.length;
		            range.moveStart('character', - thiz.value.length);
		            result = range.text.length - length;
	        	} else {
	                var bodyRange = document.body.createTextRange();
	                bodyRange.moveToElementText(thiz);
	                for (; bodyRange.compareEndPoints("StartToStart", range) < 0; result++)
	                	bodyRange.moveStart('character', 1);
	                for (var i = 0; i <= result; i ++){
	                    if (thiz.value.charAt(i) == '\n')
	                        result++;
	                }
	                var enterCount = thiz.value.split('\n').length - 1;
					result -= enterCount;
                    return result;
	        	}
	        }
	        return result;
	    },
		getCaretPosition: calculator.getCaretPosition
	});
});


/*
 * set cursor position in textarea
 */
$.fn.setCursorPosition = function(pos) {
	if ($(this).get(0).setSelectionRange) {
		$(this).get(0).setSelectionRange(pos, pos);
	} else if ($(this).get(0).createTextRange) {
		var range = $(this).get(0).createTextRange();
		range.collapse(true);
		range.moveEnd('character', pos);
		range.moveStart('character', pos);
		range.select();
	}
}



