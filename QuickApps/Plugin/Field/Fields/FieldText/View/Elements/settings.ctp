<!-- Text Settings Form -->
<?php
	echo $this->Form->input("Field.settings.type",
		array(
			'type' => 'select',
			'options' => array(
				'text' => __t('Text field'),
				'textarea' => __t('Long text')
			),
			'label' => __t('Type of content')
		)
	);
?>

<?php
	echo $this->Form->input("Field.settings.text_processing",
		array(
			'type' => 'select',
			'options' => array(
				'plain' => __t('Plain text'),
				'full' => __t('Full HTML'),
				'filtered' => __t('Filtered HTML'),
				'markdown' => __t('Markdown')
			),
			'label' => __t('Text processing')
		)
	);
?>
<blockquote class="text-processing-desc">
	<ul>
		<li>
			<b><?php echo __t('Plain text'); ?>:</b>
			<ul>
				<li><?php echo __t('No HTML tags allowed.'); ?></li>
				<li><?php echo __t('Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
				<li><?php echo __t('Lines and paragraphs break automatically.'); ?></li>
			</ul>
		</li>

		<li>
			<b><?php echo __t('Full HTML'); ?>:</b>
			<ul>
				<li><?php echo __t('Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
			</ul>
		</li>

		<li>
			<b><?php echo __t('Filtered HTML'); ?>:</b>
			<ul>
				<li><?php echo __t('Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
				<li><?php echo __t('Allowed HTML tags: &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;cite&gt; &lt;blockquote&gt; &lt;code&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt;'); ?></li>
				<li><?php echo __t('Lines and paragraphs break automatically.'); ?></li>
			</ul>
		</li>

		<li>
			<b><?php echo __t('Markdown'); ?>:</b>
			<ul>
				<li><?php echo __t('<a href="%s" target="_blank">Markdown</a> text format allowed only.', 'http://wikipedia.org/wiki/Markdown'); ?></li>
			</ul>
		</li>
	</ul>
</blockquote>

<style>
	blockquote.text-processing-desc { -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; }
	blockquote.text-processing-desc ul {list-style:circle; margin-top:5px;}
	blockquote.text-processing-desc li { margin: 10px 0 0 15px;}
</style>

<?php
	echo $this->Form->input("Field.settings.max_len",
		array(
			'type' => 'text',
			'label' => __t('Max length'),
			'helpBlock' =>  __t("This is only used if your Type of content is a `Text field`. This will limit the subscriber to typing X number of characters in your textbox.")
		)
	);

	echo $this->Form->input("Field.settings.validation_rule",
		array(
			'type' => 'text',
			'label' => __t('Validation rule'),
			'helpBlock' => __t('Enter your custom regular expression. e.g.: "/^[a-z0-9]{3,}$/i" (Only letters and integers, min 3 characters)')
		)
	);

	echo $this->Form->input("Field.settings.validation_message",
		array(
			'type' => 'text',
			'label' => __t('Validation message'),
			'helpBlock' => __t('This is only used if `Validation rule` has been set.')
		)
	);