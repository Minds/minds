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
					for(type in response){
						$.each(response[type], function(key, item){
							div = '';
							
							if(item.type == 'user'){
								div = item.name + '<span class="subtype">user</span>';
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
				console.log(suggestion);
				$(this).val(suggestion.data.username);
			}	
		}
	});
	
	
};

elgg.register_hook_handler('init', 'system', elgg.autocomplete.init);
