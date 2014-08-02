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
				'onchange' => 'toggleCommentOptions();',
			]);
		?>
		<em class="help-block"><?php echo __d('node', 'Default comment setting for new content.'); ?></em>

		<div class="comment-options">
			<?php echo $this->Form->input('defaults.comment_autoapprove', ['type' => 'checkbox', 'label' => __d('node', 'Auto approve comments')]); ?>
			<em class="help-block"><?php echo __d('node', 'Comments will automatically approved an published.'); ?></em>

			<?php echo $this->Form->input('defaults.comment_allow_anonymous', ['type' => 'checkbox', 'label' => __d('node', 'Anonymous commenting'), 'id' => 'allow-anonymous-comments', 'onclick' => 'toggleAnonymousCommentOptions();']); ?>
			<em class="help-block"><?php echo __d('node', 'Anonymous users can comment.'); ?></em>

			<div class="anonymous-comments-options">
				<?php echo $this->Form->input('defaults.comment_anonymous_name', ['type' => 'checkbox', 'label' => __d('node', "Anonymous's name")]); ?>
				<em class="help-block">
					<?php
						echo __d(
							'node',
							'Anonymous users %s leave their name.', 
							$this->Form->input('defaults.comment_anonymous_name_required', [
								'type' => 'select',
								'label' => false,
								'bootstrap' => false,
								'options' => [
									1 => __d('node', 'Must'),
									0 => __d('node', 'May'),
								],
							])
						);
					?>
				</em>

				<?php echo $this->Form->input('defaults.comment_anonymous_email', ['type' => 'checkbox', 'label' => __d('node', "Anonymous's email")]); ?>
				<em class="help-block">
					<?php
						echo __d(
							'node',
							'Anonymous users %s leave an email address.', 
							$this->Form->input('defaults.comment_anonymous_email_required', [
								'type' => 'select',
								'label' => false,
								'bootstrap' => false,
								'options' => [
									1 => __d('node', 'Must'),
									0 => __d('node', 'May'),
								]
							])
						);
					?>
				</em>

				<?php echo $this->Form->input('defaults.comment_anonymous_web', ['type' => 'checkbox', 'label' => __d('node', "Anonymous's website")]); ?>
				<em class="help-block">
					<?php
						echo __d(
							'node',
							'Anonymous users %s leave a website URL.', 
							$this->Form->input('defaults.comment_anonymous_web_required', [
								'type' => 'select',
								'label' => false,
								'bootstrap' => false,
								'options' => [
									1 => __d('node', 'Must'),
									0 => __d('node', 'May'),
								]
							])
						);
					?>
				</em>
			</div>
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
		if (parseInt($('#defaults-comment-status').val()) > 0) {
			$('.comment-options').show();
		} else {
			$('.comment-options').hide();
		}
	}

	function toggleAnonymousCommentOptions() {
		if ($('#allow-anonymous-comments').is(':checked')) {
			$('.anonymous-comments-options').show();
		} else {
			$('.anonymous-comments-options').hide();
		}
	}

	$(document).ready(function () {
		toggleCommentOptions();
		toggleAnonymousCommentOptions();
	});
</script>

<style>
	.anonymous-comments-options div.select { display:inline; }
</style>