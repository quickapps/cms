jQuery.FontPanel = {
	fonts: new Array('Arial', 'Helvetica', 'Georgia', 'Verdana', 'Trebuchet MS', 'Book Antiqua', 'Tahoma', 'Times New Roman', 'Courier New', 'Arial Black', 'Comic Sans MS'),
	sizes: new Array(8, 10, 11, 12, 13, 14, 16, 18, 21, 24, 28, 36, 48, 64),
	styles: new Array('normal', 'italic', 'oblique', 'inherit'),
	weights: new Array('normal', 'bold', 'bolder', 'lighter', 'inherit'),
	activePanel: false,
	init: function(font, size, weight, style) {
		var html = '<div id="jquery-font-panel"><div id="jquery-font-panel-list-fonts" class="jquery-font-panel-list">';

		for (i = 0; i < this.fonts.length; i++) {
			html += '<div font-panel-font="' + this.fonts[i] + '" style="font-family:' + this.fonts[i] + '">' + this.fonts[i] + '<\/div>';
		}

		html += '<\/div><div id="jquery-font-panel-list-sizes" class="jquery-font-panel-list">';

		for (i = 0; i < this.sizes.length; i++) {
			html += '<div font-panel-size="' + this.sizes[i] + '">' + this.sizes[i] + '<\/div>';
		}

		html += '<\/div><div id="jquery-font-panel-list-styles" class="jquery-font-panel-list">';

		for (i = 0; i < this.styles.length; i++) {
			html += '<div font-panel-style="' + this.styles[i] + '" style="font-style:' + this.styles[i] + '">' + this.styles[i] + '<\/div>';
		}

		html += '<\/div><div id="jquery-font-panel-list-weights" class="jquery-font-panel-list">';

		for (i = 0; i < this.weights.length; i++) {
			html += '<div font-panel-weight="' + this.weights[i] + '" style="font-weight:' + this.weights[i] + '">' + this.weights[i] + '<\/div>';
		}

		html +='<\/div><div id="jquery-font-panel-save"><input type="button" name="save" value="OK" /><\/<div><\/div>';

		if ($('#jquery-font-panel').length == 0) {
			$(document.body).append(html);
		}
	},
	showPanel: function(parent) {
		this.setupFonts();
		this.setupSizes();
		this.setupWeights();
		this.setupStyles();
		this.setupSubmit();

		this.activePanel = parent;
		var panel = $('#jquery-font-panel');
		var dim = $(parent).offset();

		panel.css('top', dim.top + 36);
		panel.css('left', dim.left + 5);
		panel.toggle();
	},
	hidePanel: function() {
		var panel = $('#jquery-font-panel');
		$(document).unbind('mousedown');
		panel.hide();
	},
	setupSubmit: function() {
		var panel = this;

		$('div#jquery-font-panel-save input').click(function() {
			var font = $('div#jquery-font-panel-list-fonts div.font-panel-list-selected').attr('font-panel-font');
			var size = $('div#jquery-font-panel-list-sizes div.font-panel-list-selected').attr('font-panel-size');
			var weight = $('div#jquery-font-panel-list-weights div.font-panel-list-selected').attr('font-panel-weight');
			var style = $('div#jquery-font-panel-list-styles div.font-panel-list-selected').attr('font-panel-style');
			var active = $(jQuery.FontPanel.activePanel);

			style = style == undefined ? '' : style;
			weight = weight == undefined ? '' : weight;
			size = size == undefined ? '' : size + 'px';
			font = font == undefined ? '' : font;
			var selectedString = [style, weight, size, font].join(' ').trim();

			$("#" + active.attr('id')).val(selectedString);
			$("#" + active.attr('id') + '-preview').attr('style', 'font:' + selectedString + ';');
			panel.hidePanel();
		});
	},
	setupFonts: function() {
		$('div#jquery-font-panel-list-fonts div').click(function() {
			$('div#jquery-font-panel-list-fonts div').removeClass('font-panel-list-selected');
			$(this).addClass("font-panel-list-selected");
		});
	},
	setupSizes: function() {
		$('div#jquery-font-panel-list-sizes div').click(function() {
			$('div#jquery-font-panel-list-sizes div').removeClass('font-panel-list-selected');
			$(this).addClass("font-panel-list-selected");
		});
	},
	setupWeights: function() {
		$('div#jquery-font-panel-list-weights div').click(function() {
			$('div#jquery-font-panel-list-weights div').removeClass('font-panel-list-selected');
			$(this).addClass("font-panel-list-selected");
		});
	},
	setupStyles: function() {
		$('div#jquery-font-panel-list-styles div').click(function() {
			$('div#jquery-font-panel-list-styles div').removeClass('font-panel-list-selected');
			$(this).addClass("font-panel-list-selected");
		});
	}
}

jQuery.fn.FontPanel = function() {
	jQuery.FontPanel.init();
	$(this).click(function() { jQuery.FontPanel.showPanel(this); });
}