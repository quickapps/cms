function changeFlag(selectbox, baseURL) {
	var icon = $(selectbox).val();
	if (icon) {
		$('span.flag').html('<img src="' + baseURL +  icon + '" />');
	}
}
