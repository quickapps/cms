<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<div class="form-group">
	<?php
		echo $this->Form->input('type',
			array(
				'type' => 'select',
				'options' => array(
					'text' => __d('field', 'Short field [text box]'),
					'textarea' => __d('field', 'Long text [text area]')
				),
				'label' => __d('field', 'Type of content'),
				'onchange' => 'toggleMaxLen()',
				'class' => 'type-select',
			)
		);
	?>
</div>

<div class="form-group">
	<?php
		echo $this->Form->input('text_processing',
			array(
				'type' => 'select',
				'options' => array(
					'plain' => __d('field', 'Plain text'),
					'full' => __d('field', 'Full HTML'),
					'filtered' => __d('field', 'Filtered HTML'),
					'markdown' => __d('field', 'Markdown')
				),
				'label' => __d('field', 'Text processing')
			)
		);
	?>
</div>

<div class="form-group">
	<ul>
		<li>
			<b><?php echo __d('field', 'Plain text'); ?>:</b>
			<ul>
				<li><?php echo __d('field', 'No HTML tags allowed.'); ?></li>
				<li><?php echo __d('field', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
				<li><?php echo __d('field', 'Lines and paragraphs break automatically.'); ?></li>
			</ul>
		</li>
		<li>
			<b><?php echo __d('field', 'Full HTML'); ?>:</b>
			<ul>
				<li><?php echo __d('field', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
			</ul>
		</li>
		<li>
			<b><?php echo __d('field', 'Filtered HTML'); ?>:</b>
			<ul>
				<li><?php echo __d('field', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
				<li><?php echo __d('field', 'Allowed HTML tags: &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;cite&gt; &lt;blockquote&gt; &lt;code&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt;'); ?></li>
				<li><?php echo __d('field', 'Lines and paragraphs break automatically.'); ?></li>
			</ul>
		</li>
		<li>
			<b><?php echo __d('field', 'Markdown'); ?>:</b>
			<ul>
				<li><?php echo __d('field', '<a href="{0}" target="_blank">Markdown</a> text format allowed only.', 'http://wikipedia.org/wiki/Markdown'); ?></li>
			</ul>
		</li>
	</ul>
</div>

<div class="form-group max-len">
	<?php
		echo $this->Form->input('max_len',
			array(
				'type' => 'text',
				'label' => __d('field', 'Max length')
			)
		);
	?>
	<p class="help-block"><?php echo __d('field', 'This is only used if your type of content is "Short text [text box]". This will limit the subscriber to typing X number of characters in your textbox.'); ?></p>
</div>

<div class="form-group">
	<?php
		echo $this->Form->input('validation_rule',
			array(
				'type' => 'text',
				'label' => __d('field', 'Validation rule'),
				'onchange' => 'toggleValMsg()',
				'class' => 'reg-input',
			)
		);
	?>
	<p class="help-block"><?php echo __d('field', 'Enter your custom regular expression. e.g.: <strong>/^[a-z0-9]{3,}$/i</strong> (Only letters and integers, min 3 characters)'); ?></p>
</div>

<div class="form-group validation-message">
	<?php
		echo $this->Form->input('validation_message',
			array(
				'type' => 'text',
				'label' => __d('field', 'Validation message')
			)
		);
	?>
	<p class="help-block"><?php echo __d('field', 'This is only used if "Validation rule" has been set.'); ?></p>
</div>

<script>
	function toggleMaxLen() {
		if ($('.type-select').val() === 'text') {
			$('div.max-len').show();
		} else {
			$('div.max-len').hide();
		}
	}

	$(document).ready(function () {
		toggleMaxLen();
	});
</script>