<?php
/**
 * Comment Application Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Comment.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class CommentAppController extends AppController {
	public $uses = array('Comment.Comment');

	public function countUnpublished() {
		$count = $this->Comment->find('count',
			array(
				'conditions' => array(
					'Comment.status' => 0
				)
			)
		);

		$this->set('countUnpublished', $count);

		return $count;
	}
}