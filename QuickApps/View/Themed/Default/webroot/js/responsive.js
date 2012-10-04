$(function() {
	if ($.browser.msie && $.browser.version.substr(0,1) < 7) {
		$('li').has('ul').mouseover(function(){
			$(this).children('ul').css('visibility', 'visible');
		}).mouseout(function(){
			$(this).children('ul').css('visibility', 'hidden');
		});
	}

	/* Mobile */
	$('#main-menu-wrap').prepend('<div id="menu-trigger"></div>');
	$("#menu-trigger").on('click', function () {
		$("#menu").slideToggle();
	});

	$('#sidebar-left .block h2').on('click', function () {
		if ($(window).width() <= 650) {
			$(this).parent('.block').children('.content').toggle('slow');
		}
	});

	// iPad
	var isiPad = navigator.userAgent.match(/iPad/i) != null;
	if (isiPad) $('#menu ul').addClass('no-transition');      
});