setTimeout(
	function () {
		$('div.alert').each(
			function(i, t) {
				$(this).delay(i * 1000).fadeOut("slow");
			}
		); 
	}, 5000);