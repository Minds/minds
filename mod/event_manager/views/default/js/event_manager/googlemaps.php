<?php ?>
//<script>
var event_manager_gmap;
var event_manager_geocoder;
var event_manager_gmarkers = [];
var markerTimeout;

$(function() {
	$('.openRouteToEvent').live('click', function(e) {
		var $elem = $(this);
		
		$.fancybox({
			
			'href': $elem.attr('href'),
			'onComplete': function() {
				initMaps('map_canvas');
				event_manager_geocoder.geocode( { 'address': $elem.html() }, function(results, status) {
			      	if (status == google.maps.GeocoderStatus.OK) {
			        	event_manager_gmap.setCenter(results[0].geometry.location);
			        	new google.maps.Marker({ map: event_manager_gmap, position: results[0].geometry.location });
			      	}
			    });
			}});
		e.preventDefault();
	});
	
	// used for edit event form //@todo improve
	$('#openmaps').click(function()	{
		$("#openGoogleMaps").click();
	});
	
	// used for edit event form
	$("#openGoogleMaps").fancybox({
			'onComplete': function() {
				var location = $('#event_manager_event_edit input[name="location"]').val();
				initMaps('map_canvas');
				
				if(location) {
					$("#address_search").val(location);
					event_manager_geocoder.geocode( { 'address': location}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							event_manager_gmap.setCenter(results[0].geometry.location);
							new google.maps.Marker({ map: event_manager_gmap, position: results[0].geometry.location });
						}
				    });
				}
			}
	});
			
	$('#event_manager_address_search').live("submit", function(e) {
		searchAddress($('#address_search').val());
		e.preventDefault();
	});

	$('#address_search_save').live("click", function() {
		var address = $('#address_search').val();
	
		$('#event_manager_event_edit input[name="location"]').val(address);
		if(address){
			event_manager_geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					$('#event_latitude').val(results[0].geometry.location.lat());
					$('#event_longitude').val(results[0].geometry.location.lng());
				}
		    });
		} else {
			$('#event_latitude').val("");
	   		$('#event_longitude').val("");
	   	}
		
		$.fancybox.close();
	});
	
	$('#event_manager_address_route_search').live("submit", function(e)	{
		frmAddress = $('#address_from').val();
		dstAddress = $('#address_to').html();
		
		if(frmAddress == '') {
			alert(elgg.echo('event_manager:action:event:edit:error_fields'));
		} else {
			window.open( '//maps.google.com/maps?f=d&source=s_d&saddr=' + frmAddress + '&daddr=' + dstAddress );
		}
		
		e.preventDefault();
	});
});


/*
 * Global GoogleMaps init
 */
function initMaps(element){
	event_manager_geocoder = new google.maps.Geocoder();
	
	var myOptions = {
    	zoom: EVENT_MANAGER_BASE_ZOOM,
    	mapTypeId: google.maps.MapTypeId.ROADMAP
  	}
	event_manager_gmap = new google.maps.Map(document.getElementById(element), myOptions);
	
	event_manager_geocoder.geocode( { 'address': EVENT_MANAGER_BASE_LOCATION}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        event_manager_gmap.setCenter(results[0].geometry.location);
      }
    });
}

function initOnthemaps() {
	
<!-- 	GEvent.addListener(event_manager_gmap, "dragend", function() { -->
<!-- 		getMarkersJson(); -->
<!-- 	}); -->
	
<!-- 	GEvent.addListener(event_manager_gmap, "movestart", function() { -->
<!-- 		getMarkersJson();		 -->
<!-- 	}); -->
	
<!-- 	GEvent.addListener(event_manager_gmap, "moveend", function() { -->
<!-- 		getMarkersJson();		 -->
<!-- 	}); -->
	
<!-- 	GEvent.addListener(event_manager_gmap, "dblclick", function() { -->
<!-- 		getMarkersJson();	 -->
<!-- 	}); -->
	
<!-- 	GEvent.addListener(event_manager_gmap, "zoomend", function() { -->
<!-- 		getMarkersJson();	 -->
<!-- 	}); -->
}

function makeSidebar() {
	$('#event_manager_onthemap_sidebar').remove();

	var foundMarkers = 0;
	var html = '<div class="elgg-module elgg-module-aside" id="event_manager_onthemap_sidebar">';
	html += '<div class="elgg-head"><h3>' + elgg.echo('event_manager:sidebar:title') + '</h3></div>';
	html += '<ul class="elgg-menu elgg-menu-extras">'; 
	
	$.each(event_manager_gmarkers, function(i, event) {
		if (!event.isHidden()) {
			foundMarkers = (parseInt(foundMarkers, 10)+1);
			html += '<li class="elgg-menu-item"><a href="javascript:openInfowindow(' + i + ');">' + event.myname + '<\/a><\/li>';
		}
	});
	
	html += '</ul></div>';

	if(foundMarkers > 0) {
		$('div.elgg-sidebar').append(html);
	}
}

function getMarkersJson() { 
	var bounds = event_manager_gmap.getBounds(), southWest = bounds.getSouthWest(), northEast = bounds.getNorthEast();

	$.each(event_manager_gmarkers, function(i, event) {
		var lat = event.getLatLng().lat();
		var lng = event.getLatLng().lng();
		
		if(lat <= northEast.lat() && lng >= southWest.lng() && lat >= southWest.lat() && lng <= northEast.lng()) {
			if(event.isHidden()) {
				event.show();
			}
		} else {
			event.closeInfoWindow(); 
			event.hide();
		}
	});
	
	makeSidebar();
}

function searchAddress(address) {
	if (event_manager_geocoder == null)	{
		event_manager_geocoder = new google.maps.Geocoder();
	}
	
	event_manager_geocoder.geocode( { 'address': address }, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
        	event_manager_gmap.setCenter(results[0].geometry.location);
        	new google.maps.Marker({ map: event_manager_gmap, position: results[0].geometry.location });
        	$('#address_search').val(results[0].formatted_address);
      	}
    });
}

function setLatLngFields(point) {
   $('#event_latitude').val(point.lat());
   $('#event_longitude').val(point.lng());
}

function getAdressFromCoords(coords, fields) {
	var address = null;
	if(coords == null)
	{
		coords = event_manager_gmap.getCenter();
	}
	if (event_manager_geocoder == null)
	{
		event_manager_geocoder = new GClientGeocoder();
	}
	event_manager_geocoder.getLocations(coords, function(response)
	{
		if(response)
		{
			address = response.Placemark[0].address;
			setAddressFields(address);
		}
	});	
}

function setAddressFields(address) {
   $('#address_search').val(address);
   $('#event_manager_event_edit input[name="location"]').val(address);
}