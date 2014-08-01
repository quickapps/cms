var CommentForm = {
	init: function () {
		if (
			$('form.comment-form .has-error').length > 0 &&
			parseInt($('form.comment-form #comment-parent-id').val()) > 0
		) {
			id = $('form.comment-form #comment-parent-id').val();
			CommentForm.replyTo(id);
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
		$('form.comment-form #comment-parent-id').val('');
		$('section.comments-form').appendTo('div.comment-form-container');
		$('section.comments-form header .cancel-reply').hide();
	}
}

$(document).ready(function () {
	CommentForm.init();
})