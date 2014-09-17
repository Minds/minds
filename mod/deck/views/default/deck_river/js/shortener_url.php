/**
 *  Elgg-deck_river plugin
 *  @package elgg-deck_river
 *  @author Emmanuel Salomon @ManUtopiK
 *  @license GNU Affero General Public License, version 3 or late
 *  @link https://github.com/ManUtopiK/elgg-deck_river
 *
 *  Elgg-deck_river shortener url js
 *
 */


/**
 * Elgg-deck_river shortener url init
 *
 * @return void
 */
elgg.deck_river.ShortenerUrlInit = function() {
	var $thus = $('#thewire-header .url-shortener');

	$thus.find('.elgg-input-text').focusin(function() {
		if (this.value == elgg.echo('deck-river:reduce_url:string')) {
			this.value = '';
		}
	}).focusout(function() {
		if (this.value == '') {
			this.value = elgg.echo('deck-river:reduce_url:string');
			$(this).parent().find('.elgg-button-action, .elgg-icon').addClass('hidden');
		}
	}).keydown(function(e) {
		if (e.keyCode == 13) {
			$thus.find('.elgg-button-submit').click();
			return false;
		}
	});
	$thus.find('.elgg-button-submit').on('click', function() {
		var input = $(this).parent().find('.elgg-input-text'),
			longUrl = input.val().trim(),
			shortUrl = false;

		if (longUrl == elgg.echo('deck-river:reduce_url:string') || longUrl == '') {
			elgg.register_error(elgg.echo('deck_river:url-not-exist'));
		} else {
			elgg.deck_river.ShortenUrl(longUrl, function(shortUrl) {
				input.val(shortUrl);
				if (shortUrl != longUrl) input.parent().find('.elgg-button-action, .elgg-icon').removeClass('hidden');
			});
		}
	});
	$thus.find('.elgg-button-action').on('click', function() {
		var shortUrl = $(this).parent().find('.elgg-input-text').val();

		if (shortUrl == elgg.echo('deck-river:reduce_url:string')) return;
		elgg.thewire.insertInThewire(shortUrl);
	});
	$thus.find('.elgg-icon').on('click', function() {
		var urlShortner = $(this).parent();
		urlShortner.find('.elgg-input-text').val(elgg.echo('deck-river:reduce_url:string'));
		urlShortner.find('.elgg-button-action, .elgg-icon').addClass('hidden');
		$('.tipsy').remove();
	});

}
elgg.register_hook_handler('init', 'system', elgg.deck_river.ShortenerUrlInit);



/**
 * Shortener url
 */
elgg.deck_river.ShortenUrl = function(url, callback) {
	var guid = url.match(/^[:=](\d+)/) || null,
		ajaxShort = function (url) {
			elgg.post('ajax/view/deck_river/ajax_json/url_shortener', {
				dataType: "html",
				data: {
					url: url
				},
				success: function(response) {
					if (response == 'badurl') {
						elgg.register_error(elgg.echo('deck_river:url-bad-format'));
						callback(url);
					} else {
						callback(response);
					}
				},
				error: function(response) {
					// error with server
				}
			});
		};

	// check if it's an internal link
	if ((guid || url.indexOf(elgg.get_site_url()) === 0) && site_shorturl) {
		var Purl = elgg.parse_url(url).path;

		/* Avaible query
		 * view/GUID
		 * profile/GUID
		 * page/GUID
		 * board/GUID
		 * card/GUID
		 * candidat/GUID
		 * :GUID or = GUID
		*/

		if (guid || (guid = (
			Purl.match(/view\/(\d+)/) ||
			Purl.match(/profile\/(\d+)/) ||
			Purl.match(/page\/(\d+)/) ||
			Purl.match(/board\/(\d+)/) ||
			Purl.match(/card\/(\d+)/) ||
			Purl.match(/candidat\/(\d+)/) ||
			Purl.match(/^[:=](\d+)/)
		))) {
			callback(site_shorturl+AlphabeticID.encode(parseInt(guid.pop())));
		} else {
			ajaxShort(url);
		}
	} else {
		ajaxShort(url);
	}
};



/**
 *  Javascript AlphabeticID class
 *  (based on a script by Kevin van Zonneveld <kevin@vanzonneveld.net>)
 *
 *  Author: Even Simon <even.simon@gmail.com>
 *
 *  Description: Translates a numeric identifier into a short string and backwords.
 *
 *  Usage:
 *    var str = AlphabeticID.encode(9007199254740989); // str = 'fE2XnNGpF'
 *    var id = AlphabeticID.decode('fE2XnNGpF'); // id = 9007199254740989;
 **/

var AlphabeticID = {
	index:'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	/**
	*  [@function](http://twitter.com/function) AlphabeticID.encode
	*  [@description](http://twitter.com/description) Encode a number into short string
	*  [@param](http://twitter.com/param) integer
	*  [@return](http://twitter.com/return) string
	**/
	encode:function(_number){
		if('undefined' == typeof _number){
			return null;
		}
		else if('number' != typeof(_number)){
			throw new Error('Wrong parameter type');
		}

		var ret = '';

		for(var i=Math.floor(Math.log(parseInt(_number))/Math.log(AlphabeticID.index.length));i>=0;i--){
			ret = ret + AlphabeticID.index.substr((Math.floor(parseInt(_number) / AlphabeticID.bcpow(AlphabeticID.index.length, i)) % AlphabeticID.index.length),1);
		}

		return ret.reverse();
	},

	/**
	*  [@function](http://twitter.com/function) AlphabeticID.decode
	*  [@description](http://twitter.com/description) Decode a short string and return number
	*  [@param](http://twitter.com/param) string
	*  [@return](http://twitter.com/return) integer
	**/
	decode:function(_string){
		if('undefined' == typeof _string){
			return null;
		}
		else if('string' != typeof _string){
			throw new Error('Wrong parameter type');
		}

		var str = _string.reverse();
		var ret = 0;

		for(var i=0;i<=(str.length - 1);i++){
			ret = ret + AlphabeticID.index.indexOf(str.substr(i,1)) * (AlphabeticID.bcpow(AlphabeticID.index.length, (str.length - 1) - i));
		}

		return ret;
	},

	/**
	*  [@function](http://twitter.com/function) AlphabeticID.bcpow
	*  [@description](http://twitter.com/description) Raise _a to the power _b
	*  [@param](http://twitter.com/param) float _a
	*  [@param](http://twitter.com/param) integer _b
	*  [@return](http://twitter.com/return) string
	**/
	bcpow:function(_a, _b){
		return Math.floor(Math.pow(parseFloat(_a), parseInt(_b)));
	}
};

/**
 *  [@function](http://twitter.com/function) String.reverse
 *  [@description](http://twitter.com/description) Reverse a string
 *  [@return](http://twitter.com/return) string
 **/
String.prototype.reverse = function(){
	return this.split('').reverse().join('');
};