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

};

elgg.register_hook_handler('init', 'system', archive.init);
