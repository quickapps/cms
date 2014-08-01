<?php echo $this->Form->create($type); ?>
	<fieldset>
		<legend><?php echo __d('node', 'Content Type Information'); ?></legend>
		<?php echo $this->Form->input('name', ['label' => __d('node', 'Name')]); ?>
		<em class="help-block"><?php echo __d('node', 'This text will be displayed as part of the list on the "Add New Content" page.'); ?></em>

		<?php echo $this->Form->input('title_label', ['label' => __d('node', 'Title field label')]); ?>
		<em class="help-block"><?php echo __d('node', 'Label name for the "Title" field. e.g. "Product name", "Author name", etc.'); ?></em>

		<?php echo $this->Form->input('description', ['label' => __d('node', 'Description'), 'type' => 'textarea']); ?>
		<em class="help-block"><?php echo __d('node', 'Describe this content type. The text will be displayed on the Add new content page.'); ?></em>

		<?php echo $this->Form->input('slug', ['label' => __d('node', 'Machine name')]); ?>
		<em class="help-block">
			<?php echo __d('node', 'A unique name for this content type. It must only contain lowercase letters, numbers, and minus symbol (a-z, 0-9, -).'); ?>
			<br />
			<strong><?php echo __d('node', 'WARNING'); ?>:</strong> <?php echo __d('node', 'Changing this value may break incoming URLs. Use with caution on a production site.'); ?>
		</em>
	</fieldset>

	<hr />

	<fieldset>
		<legend><?php echo __d('node', 'Author & Publish Date'); ?></legend>

		<?php echo $this->Form->input('defaults.author_name', ['type' => 'checkbox', 'label' => __d('node', "Show author's name")]); ?>
		<em class="help-block"><?php echo __d('node', "Author's username will be displayed."); ?></em>

		<?php echo $this->Form->input('defaults.show_date', ['type' => 'checkbox', 'label' => __d('node', 'Show date')]); ?>
		<em class="help-block"><?php echo __d('node', 'Publish date will be displayed'); ?></em>
	</fieldset>

	<fieldset>
		<legend><?php echo __d('node', 'Comments'); ?></legend>

		<?php
			echo $this->Form->input('defaults.comment_status', [
				'type' => 'select',
				'label' => __d('node', 'Comments default status'),
				'options' => [
					0 => __d('node', 'Closed'),
					1 => __d('node', 'Open'),
					2 => __d('node', 'Read only'),
				],
				'onchange' => 'toggleCommentOptions()',
			]);
		?>
		<em class="help-block"><?php echo __d('node', 'Default comment setting for new content.'); ?></em>

		<div class="comment-options">
			<?php echo $this->Form->input('defaults.comment_autoapprove', ['type' => 'checkbox', 'label' => __d('node', 'Auto approve comments')]); ?>
			<em class="help-block"><?php echo __d('node', 'Comments will automatically approved an published.'); ?></em>

			<?php echo $this->Form->input('defaults.comment_anonymous', ['type' => 'checkbox', 'label' => __d('node', 'Anonymous commenting')]); ?>
			<em class="help-block"><?php echo __d('node', 'Anonymous users can comment.'); ?></em>
		</div>
	</fieldset>

	<fieldset>
		<legend><?php echo __d('node', 'Language'); ?></legend>

		<?php
			echo $this->Form->input('defaults.language', [
				'type' => 'select',
				'label' => __d('node', 'Language'),
				'options' => $languages,
				'empty' => __d('node', '-- ANY --'),
			]);
		?>
		<em class="help-block"><?php echo __d('node', 'Default language for new contents.'); ?></em>
	</fieldset>

	<fieldset>
		<legend><?php echo __d('node', 'Publishing'); ?></legend>

		<?php echo $this->Form->input('defaults.status', ['type' => 'checkbox', 'label' => __d('node', 'Published')]); ?>
		<?php echo $this->Form->input('defaults.promote', ['type' => 'checkbox', 'label' => __d('node', 'Promoted to front page')]); ?>
		<?php echo $this->Form->input('defaults.sticky', ['type' => 'checkbox', 'label' => __d('node', 'Sticky at top of lists')]); ?>
	</fieldset>

	<?php echo $this->Form->submit(__d('node', 'Seve changes')); ?>
<?php echo $this->Form->end(); ?>

<script>
	function toggleCommentOptions() {
		var v = $('#defaults-comment-status').val();
		if (parseInt(v) > 0) {
			$('.comment-options').show();
		} else {
			$('.comment-options').hide();
		}
	}

	$(document).ready(function () {
		toggleCommentOptions();
	});
</script>