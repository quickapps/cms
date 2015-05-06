CKEDITOR.on('dialogDefinition', function(ev) {
	var dialogName = ev.data.name;

	if (dialogName == 'link') {
		var dialogDefinition = ev.data.definition;
		var infoTab = dialogDefinition.getContents('info');
		infoTab.add({
			type : 'vbox',
			id : 'localPageOptions',
			children: [{
				type: 'select',
				label: linksToContentsLabel,
				id: 'localPage',
				title: linksToContentsLabel,
				items: linksToContentsItems,
				onChange : function(ev) {
					var diag = CKEDITOR.dialog.getCurrent();
					var url = diag.getContentElement('info', 'url');
					url.setValue(ev.data.value);
				}
			}]
		});

		dialogDefinition.onFocus = function() {
			var urlField = this.getContentElement('info', 'url');
			urlField.select();
		};
	}
});
