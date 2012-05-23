<?php
	$limit = isset($block['Block']['settings']['limit']) ? intval($block['Block']['settings']['limit']) : 5;
	$limit = $limit > 0 ? $limit : 5;
	$users = ClassRegistry::init('User.User')->find('all',
		array(
			'limit' => $limit,
			'order' => array('User.created' => 'DESC')
		)
	);
?>

<ul id="whos_new">
	<?php foreach ($users as $user): ?>
	<li><a href="<?php echo $this->Html->url('/admin/user/list/edit/' . $user['User']['id']); ?>"><span><?php echo $user['User']['username']; ?></span></a></li>
	<?php endforeach; ?>
</ul>