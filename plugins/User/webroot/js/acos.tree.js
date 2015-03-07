$(document).ready(function () {
	$('#acos-tree')
		.on('activate_node.jstree', function (event, data) {
			$data = $(data);
			$data.each(function (k, v) {
				if (v.node.children.length == 0) {
					var aco_id = v.node.a_attr['data-aco-id'];

					$.ajax({
						url: baseURL + aco_id,
						context: document.body,
					}).done(function(data) {
						$('.permissions-table').html(data);
						$('a.has-spinner').click(function() {
							$a = $(this);
							$a.addClass('disabled');
							$a.children('.glyphicon-refresh-animate').show();
							savePermissions(aco_id);
						});
					});
				}
			});
			
		})
		.jstree();

	if (expandPlugin && $('#node-' + expandPlugin)) {
		$('#acos-tree')
			.jstree(true)
			.open_node('node-' + expandPlugin);
	}

	$('#acos-tree').show();
});

function savePermissions(aco_id) {
	$.ajax({
		type: 'POST',
		url: baseURL + aco_id,
		context: document.body,
		data: $('#permissions-form').serialize(),
	}).done(function(data) {
		$('a.has-spinner').removeClass('disabled');
		$('a.has-spinner').children('.glyphicon-refresh-animate').hide();
	});
}
