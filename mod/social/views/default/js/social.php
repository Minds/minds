$(document).on('click', '.social-popup', function(e){
	e.preventDefault();
	
	window.open($(this).attr('href'), 'Authenticate');
});
