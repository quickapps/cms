<?php
Configure::write('debug', 0);

class CommentController extends AppController {
	public $uses = array('Comment.Comment');

	public function beforeFilter() {
		$this->Auth->allow('reference');
	}

	public function reference($node_id, $comment_id) {
		$comment = $this->Comment->find('first', array(
			'conditions' => array(
				'Comment.node_id' => $node_id,
				'Comment.id' => $comment_id
			),
			'recursive' => -1
		));

		if ($comment) {
			die($comment['Comment']['body']);
		}

		die('');
	}
}