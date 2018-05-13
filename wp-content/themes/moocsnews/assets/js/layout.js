var $=jQuery;
$(document).ready(function(){
	//---> Set Width - Height for Indicator Loading
	$('.home-page-loading').width($(window).width());
	$('.home-page-loading').height($(window).height());

	//---> Handling Indicator Loading for Home Page
	setTimeout(function() {
	  $('.home-page-loading').removeClass('loading');
	  $('.home-page-loading').addClass('loaded');
	}, 800);
});