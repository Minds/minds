$(function(){
	tabs.init();
});	
tabs = {
	init : function(){
		$('.tabs').each(function(){
			$(this).find('.tab-content').hide();
			$($(this).find('ul.nav .selected a').attr('href')).fadeIn(300);
			$(this).find('ul.nav a').click(function(){
				$(this).parents('.tabs').find('.tab-content').hide();
				$($(this).attr('href')).fadeIn(300);
				$(this).parent().addClass('selected').siblings().removeClass('selected');
				return false;
			});
		});
	}
}