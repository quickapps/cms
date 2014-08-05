var CommentForm = {
	init: function () {
		// if there was an error when posting a REPLY, we reposition the form to the parent comment again
		if ($('#comments-form .has-error').length > 0 && parseInt($('#comment-parent-id').val()) > 0) {
			var id = $('#comment-parent-id').val();
			CommentForm.replyTo(id);
		}

		// scroll to form if there is any alert, so users can read it
		if ($('#comments-form .has-error').length) {
			$('html,body').animate({scrollTop: $('#comments-form').offset().top}, 500);
		}
	},
	replyTo: function(id) {
		$target = $('#comment-' + id);

		if ($target.length > 0) {
			$('#comment-parent-id').val(id);
			$('section.comments-form').appendTo($target);
			$('section.comments-form header .cancel-reply').show();
		}
	},
	cancelReply: function () {
		$('#comment-parent-id').val('');
		$('section.comments-form .cancel-reply').hide();
		$('section.comments-form').appendTo('div.comments-form-container');
	}
}

$(document).ready(function () {
	CommentForm.init();
});