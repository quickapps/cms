<?php echo $this->Html->useTag('fieldsetstart', __t('Comment Details')); ?>
	<form>
		<label><?php echo __t('Published in content'); ?>: </label><?php echo $this->Html->link($this->data['Node']['title'], '/admin/node/contents/edit/' . $this->data['Node']['slug']); ?>
		<label><?php echo __t('Published by'); ?>: </label>
		<?php
			if (isset($this->data['User'])) {
				echo '<p>' . $this->User->avatar($this->data) . '</p>';
				echo $this->Html->link($this->data['User']['username'], '/admin/user/list/edit/' . $this->data['User']['id']);
			} else {
				echo $this->User->avatar(array('User' => array('email' => $this->data['Comment']['mail'])));
				echo $this->data['Comment']['name'];
			}
		?>
		<?php echo __t('on %s', $this->Time->format(__t('M d, Y H:i'), $this->data['Comment']['created'])); ?>
		<label><?php echo __t('Hostname'); ?>: </label><?php echo $this->data['Comment']['hostname']; ?>
		<label><?php echo __t('Web page'); ?>: </label><?php echo $this->data['Comment']['homepage']; ?>
		<label><?php echo __t('Subject'); ?>: </label><?php echo $this->data['Comment']['subject']; ?>
		<label><?php echo __t('Message'); ?>: </label><?php echo $this->data['Comment']['body']; ?>
	</form>
<?php echo $this->Html->useTag('fieldsetend'); ?>
