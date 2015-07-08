/**
 * 
 */
elgg.provide('elgg.autocomplete');

elgg.autocomplete.init = function() {
	var type ='';
	
	$('.elgg-input-autocomplete').on('keydown', function(){
		var type = $(this).attr('data-type');
	});
	
	$('.elgg-input-autocomplete').devbridgeAutocomplete({
		serviceUrl: elgg.get_site_url() + 'search/?view=json&limit=4', //gets set by input/autocomplete view
		params : {
			type : $('.user-lookup').attr('data-type')
		},
		paramName: 'q',
		transformResult: function(response) {
			
			response = JSON.parse(response);
	
			var result = [];			
			
        	return {
				suggestions: $.map(response, function(response){
					result = [];
					guids = [];
					for(type in response){
						$.each(response[type], function(key, item){
							div = '';
							//console.log($.inArray(item.guid, guids));
							if($.inArray(item.guid, guids) >= 0)
								return;
								
							guids.push(item.guid);
							if(item.type == 'user'){
								avatar = '<img src="'+elgg.get_site_url() + 'icon/'+item.guid+'/tiny" class="tiny-icon"/>';
								div = avatar + item.name + '<span class="subtype">user</span>';
								div += '<div class="subtitle">'+ item.username +'</div>';
							} else {
								div = item.title + '<span class="subtype">' + item.subtype +'</span>';
								div += '<div class="subtitle">'+item.ownerObj.name+'</div>';
							}
							
							result.push({
								value : div,
								data : item
							});
						});

					}
					return result;
				})
			};
		},
		onSelect: function(suggestion){

			if($(this).attr('name')=='q'){
				window.location.href = suggestion.data.url;
			} else if($(this).attr('data-type') == 'user'){
			//	console.log(suggestion);
				$(this).val(suggestion.data.username);
				$(this).trigger('minds-ac-select', { username: suggestion.data.username });
			}	
		},
		formatResult: function (suggestion, currentValue) {
		    return suggestion.value;
		}
	});
	
	
};

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);
