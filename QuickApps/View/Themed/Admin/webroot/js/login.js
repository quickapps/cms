$(document).ready(function() {
	$('#UserUsername').val(QuickApps.__t('Username...'));
	$('#UserPassword').val(QuickApps.__t('Password...'));

	$('#UserUsername').focus(function() {
		if ($(this).val() == QuickApps.__t('Username...')) {
			$(this).val('');
		}
	});

	$('#UserPassword').focus(function() {
		if ($(this).val() == QuickApps.__t('Password...')) {
			$(this).val('');
		}
	});
	
	$('#UserPassword').blur(function() {
		if (!$(this).val()) {
			$(this).val(QuickApps.__t('Password...'));
		}
	});

	$('#UserUsername').blur(function() {
		if (!$(this).val()) {
			$(this).val(QuickApps.__t('Username...'));
		}
	});
});

setTimeout(
	function () {
		$('.sessionFlash div').each(
			function(i, t) {
				$(this).delay(i * 1000).fadeOut("slow");
			}
		); 
	}, 5000);