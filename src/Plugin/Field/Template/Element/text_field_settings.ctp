<div class="form-group">
	<?php
		echo $this->Form->input('settings.type',
			array(
				'type' => 'select',
				'options' => array(
					'text' => __('Short field [textbox]'),
					'textarea' => __('Long text [textarea]')
				),
				'label' => __('Type of content')
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
<div class="form-group">
	<?php
		echo $this->Form->input('settings.max_len',
			array(
				'type' => 'text',
				'label' => __('Max length')
			)
		);
	?>
	<p class="help-block"><?php echo __("This is only used if your Type of content is a `Text field`. This will limit the subscriber to typing X number of characters in your textbox."); ?></p>
</div>
<div class="form-group">
	<?php
		echo $this->Form->input('settings.validation_rule',
			array(
				'type' => 'text',
				'label' => __('Validation rule')
			)
		);
	?>
	<p class="help-block"><?php echo __('Enter your custom regular expression. e.g.: "/^[a-z0-9]{3,}$/i" (Only letters and integers, min 3 characters)'); ?></p>
</div>
<div class="form-group">
	<?php
		echo $this->Form->input('settings.validation_message',
			array(
				'type' => 'text',
				'label' => __('Validation message')
			)
		);
	?>
	<p class="help-block"><?php echo __('This is only used if `Validation rule` has been set.'); ?></p>
</div>