$(document).ready(function () {
	$('#acos-tree')
		.on('activate_node.jstree', function (event, data) {
			$data = $(data);
			$data.each(function (k, v) {
				if (v.node.children.length == 0) {
					var aco_id = $('#' + v.node.id)
						.children('a.leaf-aco')
						.data('aco-id');

					$.ajax({
						url: '<?php echo $this->Url->build(['plugin' => 'User', 'controller' => 'permissions', 'action' => 'aco']); ?>/' + aco_id,
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
});

function savePermissions(aco_id) {
	$.ajax({
		type: 'POST',
		url: '<?php echo $this->Url->build(['plugin' => 'User', 'controller' => 'permissions', 'action' => 'aco']); ?>/' + aco_id,
		context: document.body,
		data: $('#permissions-form').serialize(),
	}).done(function(data) {
		$('a.has-spinner').removeClass('disabled');
		$('a.has-spinner').children('.glyphicon-refresh-animate').hide();
	});
}
