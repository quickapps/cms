<?php echo $this->Form->create('Translation'); ?>
	<!-- Settings -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Editing translation entry')); ?>
		<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
		<label><?php echo __t('Original text'); ?></label>
		<em>
			<?php echo $this->data['Translation']['original']; ?>
		</em>

		<p>
			<?php
				$i = 0;

				foreach (Configure::read('Variable.languages') as $lang) {
					$t = (array)Hash::extract($this->data, "I18n.{n}[locale={$lang['Language']['code']}]");
					$t = isset($t[0]) ? $t[0] : array();
					$t = Hash::merge(array('content' => '', 'id' => null), $t);

					echo $this->Form->input("I18n.{$i}.content",
						array(
							'type' => 'textarea',
							'value' => $t['content'],
							'label' => $lang['Language']['native']
						)
					);

					echo $this->Form->input("I18n.{$i}.id",
						array(
							'type' => 'hidden',
							'value' => $t['id']
						)
					);

					echo $this->Form->input("I18n.{$i}.locale",
						array(
							'type' => 'hidden',
							'value' => $lang['Language']['code']
						)
					);

					$i++;
				}
			?>
		</p>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Usage')); ?>
			<code>
				<p><b>[t]</b><?php echo $this->data['Translation']['original']; ?><b>[/t]</b></p>
				<p><b>[t=</b><?php echo $this->data['Translation']['original']; ?><b>]</b></p>
			</code>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->submit(__t('Save')); ?>
<?php echo $this->Form->end(); ?>