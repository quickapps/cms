function changeFlag() {
	var icon = $('#flag-icons').val();
	if (icon) {
		$('span.flag').html('<img src="' + baseURL +  icon + '" />');
	}
}

$(document).ready(changeFlag);