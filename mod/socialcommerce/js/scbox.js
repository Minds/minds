(function($) {
  $.scbox = function(data, klass) {
    $.scbox.loading()

    if (data.ajax) fillscboxFromAjax(data.ajax, klass)
    else if (data.image) fillscboxFromImage(data.image, klass)
    else if (data.div) fillscboxFromHref(data.div, klass)
    else if ($.isFunction(data)) data.call($)
    else $.scbox.reveal(data, klass)
  }

  /*
   * Public, $.scbox methods
   */

  $.extend($.scbox, {
    settings: {
      opacity      : 0.2,
      overlay      : true,
      loadingImage : wwwroot + 'mod/socialcommerce/images/preloader.gif',
      closeImage   : wwwroot + 'mod/socialcommerce/images/closelabel.png',
      imageTypes   : [ 'png', 'jpg', 'jpeg', 'gif' ],
      scboxHtml  : '\
	    <div id="scbox" style="display:none;"> \
	      <div class="popup"> \
	        <div class="content"> \
	        </div> \
	        <a href="#" class="close"><img src= "' + wwwroot + 'mod/socialcommerce/closelabel.png" title="close" class="close_image" /></a> \
	      </div> \
	    </div>'
    },

    loading: function() {
      init();
      if ($('#scbox .loading').length == 1) return true;
      showOverlay();

      $('#scbox .content').empty();
      $('#scbox .body').children().hide().end().
        append('<div class="loading"><img src="'+$.scbox.settings.loadingImage+'"/></div>');

      $('#scbox').css({
    	top:	(getPageHeight() / 3), //$(window).height() / 2 - 200,//getPageScroll()[1] + 
        left:	$(window).width() / 2 - 205
      }).show();

      $(document).bind('keydown.scbox', function(e) {
        if (e.keyCode == 27) $.scbox.close();
        return true;
      });
      $(document).trigger('loading.scbox');
    },

    reveal: function(data, klass) {
      $(document).trigger('beforeReveal.scbox');
      if (klass) $('#scbox .content').addClass(klass);
      $('#scbox .content').append(data);
      $('#scbox .loading').remove();
      $('#scbox .body').children().fadeIn('normal');
      $('#scbox').css('left', $(window).width() / 2 - ($('#scbox .popup').width() / 2));
      $(document).trigger('reveal.scbox').trigger('afterReveal.scbox');
      
      $('#scbox').css({
    	top:	(($(window).height() - $('#scbox').height() - 20) / 2) + getPageScroll()[1] ,
        left:	($(window).width() - $('#scbox').width() - 20) / 2
      }).show();
    },

    close: function() {
      $(document).trigger('close.scbox');
      return false;
    }
  });

  /*
   * Public, $.fn methods
   */

  $.fn.scbox = function(settings) {
    if ($(this).length == 0) return

    init(settings);

    function clickHandler() {
      $.scbox.loading(true);

      // support for rel="scbox.inline_popup" syntax, to add a class
      // also supports deprecated "scbox[.inline_popup]" syntax
      var klass = this.rel.match(/scbox\[?\.(\w+)\]?/);
      if (klass) klass = klass[1];

      fillscboxFromHref(this.href, klass);
      return false;
    }

    return this.bind('click.scbox', clickHandler);
  };

  /*
   * Private methods
   */

  // called one time to setup scbox on this page
  function init(settings) {
    if ($.scbox.settings.inited) return true;
    else $.scbox.settings.inited = true;

    $(document).trigger('init.scbox');
    makeCompatible();

    var imageTypes = $.scbox.settings.imageTypes.join('|');
    $.scbox.settings.imageTypesRegexp = new RegExp('\.(' + imageTypes + ')$', 'i');

    if (settings) $.extend($.scbox.settings, settings);
    $('body').append($.scbox.settings.scboxHtml);

    var preload = [ new Image(), new Image() ];
    preload[0].src = $.scbox.settings.closeImage;
    preload[1].src = $.scbox.settings.loadingImage;

    $('#scbox').find('.b:first, .bl').each(function() {
      preload.push(new Image());
      preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1');
    });

    $('#scbox .close').click($.scbox.close);
    $('#scbox .close_image').attr('src', $.scbox.settings.closeImage);
  }

  // getPageScroll() by quirksmode.com
  function getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
      yScroll = self.pageYOffset;
      xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
      yScroll = document.documentElement.scrollTop;
      xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
      yScroll = document.body.scrollTop;
      xScroll = document.body.scrollLeft;
    }
    return new Array(xScroll,yScroll);
  }

  // Adapted from getPageSize() by quirksmode.com
  function getPageHeight() {
    var windowHeight;
    if (self.innerHeight) {	// all except Explorer
      windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
      windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
      windowHeight = document.body.clientHeight;
    }
    return windowHeight;
  }

  // Backwards compatibility
  function makeCompatible() {
    var $s = $.scbox.settings;

    $s.loadingImage = $s.loading_image || $s.loadingImage;
    $s.closeImage = $s.close_image || $s.closeImage;
    $s.imageTypes = $s.image_types || $s.imageTypes;
    $s.scboxHtml = $s.scbox_html || $s.scboxHtml;
  }

  // Figures out what you want to display and displays it
  // formats are:
  //     div: #id
  //   image: blah.extension
  //    ajax: anything else
  function fillscboxFromHref(href, klass) {
    // div
    if (href.match(/#/)) {
      var url    = window.location.href.split('#')[0];
      var target = href.replace(url,'');
      if (target == '#') return
      $.scbox.reveal($(target).html(), klass);

    // image
    } else if (href.match($.scbox.settings.imageTypesRegexp)) {
      fillscboxFromImage(href, klass);
    // ajax
    } else {
      fillscboxFromAjax(href, klass);
    }
  }

  function fillscboxFromImage(href, klass) {
    var image = new Image();
    image.onload = function() {
      $.scbox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass);
    };
    image.src = href;
  }

  function fillscboxFromAjax(href, klass) {
    $.get(href, function(data) { $.scbox.reveal(data, klass); });
  }

  function skipOverlay() {
    return $.scbox.settings.overlay == false || $.scbox.settings.opacity === null;
  }

  function showOverlay() {
    if (skipOverlay()) return;

    if ($('#scbox_overlay').length == 0)
      $("body").append('<div id="scbox_overlay" class="scbox_hide"></div>');

    $('#scbox_overlay').hide().addClass("scbox_overlayBG")
      .css('opacity', $.scbox.settings.opacity)
      .click(function() { $(document).trigger('close.scbox'); })
      .fadeIn(200);
    return false;
  }

  function hideOverlay() {
    if (skipOverlay()) return;

    $('#scbox_overlay').fadeOut(200, function(){
      $("#scbox_overlay").removeClass("scbox_overlayBG");
      $("#scbox_overlay").addClass("scbox_hide");
      $("#scbox_overlay").remove();
    });

    return false;
  }

  /*
   * Bindings
   */

  $(document).bind('close.scbox', function() {
    $(document).unbind('keydown.scbox');
    $('#scbox').fadeOut(function() {
      $('#scbox .content').removeClass().addClass('content');
      $('#scbox .content').html('');
      $('#scbox .loading').remove();
      $(document).trigger('afterClose.scbox');
    });
    hideOverlay();
  });

})(jQuery);