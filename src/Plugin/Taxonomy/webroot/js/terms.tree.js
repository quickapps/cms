$(document).ready(function () {
	$('.sortable').nestedSortable({
		listType: 'ul',
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		items: 'li',
		opacity: .6,
		placeholder: 'placeholder',
		tabSize: 15,
		tolerance: 'pointer',
		toleranceElement: '> div',
		maxLevels: 0,
		startCollapsed: false,
		relocate: function(){
			$('#tree_order').val(
				$.toJSON($('.sortable').nestedSortable('toArray', {startDepthCount: 0}))
			);
		}
	});
});