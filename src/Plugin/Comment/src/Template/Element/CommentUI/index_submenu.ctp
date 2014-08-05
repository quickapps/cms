<?php
	$menuItems = [
		[
			'title' => __d('comment', 'All'),
			'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action],
		],
		[
			'title' => __d('comment', 'Pending') . ' <span class="badge">' . $pendingCounter  . '</span>',
			'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action, 'pending'],
		],
		[
			'title' => __d('comment', 'Approved') . ' <span class="badge">' . $approvedCounter  . '</span>',
			'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action, 'approved'],
		],
		[
			'title' => __d('comment', 'Spam') . ' <span class="badge">' . $spamCounter  . '</span>',
			'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action, 'spam'],
		],
		[
			'title' => __d('comment', 'Trash') . ' <span class="badge">' . $trashCounter  . '</span>',
			'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action, 'trash'],
		],
	];

	echo $this->Menu->render($menuItems, ['class' => 'nav nav-pills']);