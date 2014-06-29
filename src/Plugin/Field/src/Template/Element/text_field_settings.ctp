<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<div class="form-group">
	<?php
		echo $this->Form->input('settings.type',
			array(
				'type' => 'select',
				'options' => array(
					'text' => __('Short field [text box]'),
					'textarea' => __('Long text [text area]')
				),
				'label' => __('Type of content'),
				'onchange' => 'toggleMaxLen()',
				'class' => 'type-select',
			)
		);
	?>
</div>

<div class="form-group">
	<?php
		echo $this->Form->input('settings.text_processing',
			array(
				'type' => 'select',
				'options' => array(
					'plain' => __('Plain text'),
					'full' => __('Full HTML'),
					'filtered' => __('Filtered HTML'),
					'markdown' => __('Markdown')
				),
				'label' => __('Text processing')
			)
		);
	?>
</div>

<div class="form-group">
	<blockquote>
		<ul>
			<li>
				<b><?php echo __('Plain text'); ?>:</b>
				<ul>
					<li><?php echo __('No HTML tags allowed.'); ?></li>
					<li><?php echo __('Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
					<li><?php echo __('Lines and paragraphs break automatically.'); ?></li>
				</ul>
			</li>
			<li>
				<b><?php echo __('Full HTML'); ?>:</b>
				<ul>
					<li><?php echo __('Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
				</ul>
			</li>
			<li>
				<b><?php echo __('Filtered HTML'); ?>:</b>
				<ul>
					<li><?php echo __('Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
					<li><?php echo __('Allowed HTML tags: &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;cite&gt; &lt;blockquote&gt; &lt;code&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt;'); ?></li>
					<li><?php echo __('Lines and paragraphs break automatically.'); ?></li>
				</ul>
			</li>
			<li>
				<b><?php echo __('Markdown'); ?>:</b>
				<ul>
					<li><?php echo __('<a href="%s" target="_blank">Markdown</a> text format allowed only.', 'http://wikipedia.org/wiki/Markdown'); ?></li>
				</ul>
			</li>
		</ul>
	</blockquote>
</div>

<div class="form-group max-len">
	<?php
		echo $this->Form->input('settings.max_len',
			array(
				'type' => 'text',
				'label' => __('Max length')
			)
		);
	?>
	<p class="help-block"><?php echo __('This is only used if your type of content is "Short text [text box]". This will limit the subscriber to typing X number of characters in your textbox.'); ?></p>
</div>

<div class="form-group">
	<?php
		echo $this->Form->input('settings.validation_rule',
			array(
				'type' => 'text',
				'label' => __('Validation rule'),
				'onchange' => 'toggleValMsg()',
				'class' => 'reg-input',
			)
		);
	?>
	<p class="help-block"><?php echo __('Enter your custom regular expression. e.g.: "/^[a-z0-9]{3,}$/i" (Only letters and integers, min 3 characters)'); ?></p>
</div>

<div class="form-group validation-message">
	<?php
		echo $this->Form->input('settings.validation_message',
			array(
				'type' => 'text',
				'label' => __('Validation message')
			)
		);
	?>
	<p class="help-block"><?php echo __('This is only used if "Validation rule" has been set.'); ?></p>
</div>

<script>
	function toggleMaxLen() {
		if ($('.type-select').val() === 'text') {
			$('div.max-len').show();
		} else {
			$('div.max-len').hide();
		}
	}

	function toggleValMsg() {
		if ($('.reg-input').val().length > 0) {
			$('div.validation-message').show();
		} else {
			$('div.validation-message').hide();
		}
	}

	$(document).ready(function () {
		toggleMaxLen();
		toggleValMsg();
	});
</script>