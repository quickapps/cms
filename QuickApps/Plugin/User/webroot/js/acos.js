function edit_aco(id) {
	$.ajax({
		url: QuickApps.settings.base_url + 'admin/user/permissions/edit/'+id,
		type: "POST",
		success: function(response){
			$('#aco-edit').html(response);
		}
	});
}

function toggle_permission(aco, aro) {
	$.ajax({
		url: QuickApps.settings.base_url + 'admin/user/permissions/toggle/'+aco+'/'+aro,
		type: "POST",
		success: function(response) {
			aco = isNaN(aco) ? aco.replace('.', '_') : aco;

			$('#permission-' + aco + '-' + aro).html(response);
		}
	});  
}