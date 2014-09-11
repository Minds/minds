<?php if(0){?><script><?php } ?>

elgg.provide('archive');

archive.init = function(){
	if($('#archive_player2').length){
	videojs("archive_player2", 
		{
			autoplay: true,
			"height":"auto", 
			"width":"auto",
			plugins : {
				resolutionSelector : {
					// Pass any options here
					default_res : "360p"
				},
				ads: {},
				vast: {
				//	url : 'https://ads.adap.tv/a/t/integration_test'
			//		url : 'https://www.minds.io/www/delivery/fc.php?script=bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml&zones=pre-roll0-0%3D1&nz=1&block=1&format=vast&charset=UTF-8',
					url : 'https://u-ads.adap.tv/a/h/lHGLrXrmVyXT0CUmlN9BKBg48KA_lCq_HpYLH5qBiuY=?cb={cachebreaker}&pet=preroll&pageUrl=EMBEDDING_PAGE_URL&description=VIDEO_DESCRIPTION&duration=VIDEO_DURATION&id=VIDEO_ID&keywords=VIDEO_KEYWORDS&title=VIDEO_TITLE&url=VIDEO_URL&injectCompanionDummy=true&eov=eov'
				
					//url: 'https://ad.doubleclick.net/pfadx/N270.126913.6102203221521/B3876671.21;dcadv=2215309;sz=0x0;ord=%5btimestamp%5d;dcmt=text/xml'
				}
			 }
		}
	).ready(function(){
	    var myPlayer = this;    // Store the video object
	    var aspectRatio = 9/16; // Make up an aspect ratio

	    function resizeVideoJS(){
	      // Get the parent element's actual width
	      var width = document.getElementById(myPlayer.id()).parentElement.offsetWidth;
	      // Set width to fill parent element, Set height
	      myPlayer.width(width).height( width * aspectRatio );
		console.log('resizing');
	    }

	    resizeVideoJS(); // Initialize the function
	    window.onresize = resizeVideoJS; // Call the function on resize
	  });
        }
	
	var $scrubber = $("#scrubber");
        var $progress = $("#progress");
	var $video = $('video');
        var thecanvas = document.getElementById('thecanvas');
        var img = document.getElementById('thumbnail_img');
	   

	//$video.get(0).currentTime = $video.attr('data-thumbSec');
          
        $video.bind('seeked', function(){
        	draw($video.get(0), thecanvas, img);
        });

	$video.bind('timeupdate', function(e){
		var video = $video.get(0);
        	var percent = video.currentTime / video.duration;
        	updateProgressWidth(percent);
	});


         $scrubber.bind("mousedown", function(e) {
            var $this = $(this);
            var x = e.pageX - $this.offset().left;
            var percent = x / $this.width();
            updateProgressWidth(percent);
            updateVideoTime(percent);
        });

	function updateProgressWidth(percent) {
            $progress.width((percent * 100) + "%");
        }
        
        function updateVideoTime(percent) {
            var video = $video.get(0);
            video.currentTime = percent * video.duration;
	    $('#thumbSec').val(video.currentTime);
        }
                 
                 
        function draw( video, thecanvas, img ){
                // get the canvas context for drawing
                var context = thecanvas.getContext('2d');
                         
                // draw the video contents into the canvas x, y, width, height
                context.drawImage( video, 0, 0, thecanvas.width, thecanvas.height);
               
	//	var data = context.getImageData(0,0,thecanvas.width , thecanvas.height);
                var dataURL = thecanvas.toDataURL("image/jpeg");
                         
                // set the source of the img tag
                $('#thumbnailData').val(dataURL);
       }

	if($.magnificPopup) {
       archive.origin_url = window.location.href;
	
		$(document).on('click', '.lightbox-image', function(e){
       		e.preventDefault();
       		base = this;

			var active = $.magnificPopup.open({
				type: 'ajax',
				gallery:{
					enabled:true
				},
				items:[
					{
						id : 0,
						src: $(base).attr('href')
					},
					{
						id :1,
						src: $(base).attr('href')
					}
				],
				preloader: [0,2],
				removalDelay: 500,
				callbacks: {
					elementParse: function(item) {
						window.history.pushState("", "",item.src);
						//archive.resize();
					},
					resize: archive.resize,
					
					beforeOpen: function() {
						
						var album_guid = $(base).attr('data-album-guid');  
			       		var items = [{
							id : 0,
							src: $(base).attr('href')
						}];
						_this = this;
						active = this;	
						//download the full list of images in this album
						$.ajax({
							url: elgg.get_site_url() + 'archive/view/' + album_guid + '?view=json&type=album&limit=1000000',
							dataType: 'json',
							success: function(data){
								images = data.object['image'];
								$i = 1;
								$.each(images, function(k, v){
									items.push({
										id: $i,
										src: v.url+'?view=spotlight'
									});
									$i++;
								});
								
								_this.items = items;
								//$.magnificPopup.instance.index = $(base).parent().index();
								$.magnificPopup.instance.index = 0;
								$.magnificPopup.instance.updateItemHTML();
							},
							error: function(data){
								console.log($(this));
							}
						});
					},
					change: function(){
						//archive.resize();
					},
					updateStatus: function(data){
				       		$('.minds-spotlight .main img').imagesLoaded( function(){
				       			archive.resize();
				       		});
					},
					close: function(){
						window.history.pushState("", "", archive.origin_url);
					}
				}
		});
         
       });
       $(document).on('click', '.minds-spotlight .main img', function(e){
       		$.magnificPopup.instance.next();
       		setTimeout(function(){
       			$.magnificPopup.resize();
       		}, 300);
       });
	}
	

};

archive.resize = function(){

	$('.minds-spotlight .main img').each(function() {
       		//reset
		$(this).css({"width":"", "height":"", 'margin-top':''});
	
		var maxWidth = $('.minds-spotlight .main').width() ; // Max width for the image
       	var maxHeight =$('.minds-spotlight .main').height();    // Max height for the image
		var ratio = 0;  // Used for aspect ratio
		var width = $(this).width();    // Current image width
		var height = $(this).height();  // Current image height
		// Check if the current width is larger than the max
		if(width > maxWidth){
		    ratio = maxWidth / width;   // get ratio for scaling image
		    $(this).css("width", maxWidth); // Set new width
		    $(this).css("height", height * ratio);  // Scale height based on ratio
		    height = height * ratio;    // Reset height to match scaled image
		    width = width * ratio;    // Reset width to match scaled image
		}

		// Check if current height is larger than max
		if(height > maxHeight){
		    ratio = maxHeight / height; // get ratio for scaling image
		    $(this).css("height", maxHeight);   // Set new height
		  	 $(this).css("width", width * ratio);    // Scale width based on ratio
		    width = width * ratio;    // Reset width to match scaled image
		    height = height * ratio;    // Reset height to match scaled image
		    if(height == 0|| width == 0)
		    	$(this).css({"width":"", "height":"", 'margin-top':''}); //reset
		}

		//now centre vertically
		$(this).css('margin-top', (maxHeight - height) /2);	

	});


}

elgg.register_hook_handler('init', 'system', archive.init);
