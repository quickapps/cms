<?php
/**
 * Unpublished Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Comment.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class ListController extends CommentAppController {
	public $name = 'List';
	public $uses = array('Comment.Comment');

	public function admin_show($status = 'published') {
		if (trim(strtolower($status)) != 'published') {
			$filter = 0;
			$status = 'unpublished';
			$title = __t('Unpublished comments');
		} else {
			$filter = 1;
			$status = 'published';
			$title = __t('Published comments');
		}

		if (isset($this->data['Comment']['update'])) {
			if (isset($this->data['Items']['id'])) {
				$update = (!in_array($this->data['Comment']['update'], array('delete')));

				foreach ($this->data['Items']['id'] as $key => $id) {
					if ($update) {
						// approve | unapprove
						if (!$this->QuickApps->is('user.admin') &&
							!in_array("admin_{$this->data['Comment']['update']}", Configure::read('allowedActions'))
						) {
							continue;
						}

						if ($this->data['Comment']['update'] == 'approve') {
							$this->admin_approve($id);
						} else {
							$this->admin_unapprove($id);
						}
					} else {
						if (!$this->QuickApps->is('user.admin') &&
							!in_array('admin_delete', Configure::read('allowedActions'))
						) {
							continue;
						}

						$this->admin_delete($id);
					}
				}
			}

			$this->redirect($this->referer());
		}

		$paginationScope = array('Comment.status' => $filter);

		if (isset($this->data['Comment']['filter']) || $this->Session->check('Comment.filter')) {
			if (isset($this->data['Comment']['filter']) && empty($this->data['Comment']['filter'])) {
				$this->Session->delete('Comment.filter');
			} else {
				$filter = isset($this->data['Comment']['filter']) ? $this->data['Comment']['filter'] : $this->Session->read('Comment.filter');

				foreach ($filter as $field => $value) {
					if ($value !== '') {
						$field = str_replace('|', '.', $field);
						list($model, $attr) = pluginSplit($field);

						if ($attr === 'name') {
							$paginationScope['OR']['Comment.name LIKE'] = "%{$value}%";
							$paginationScope['OR']['User.name LIKE'] = "%{$value}%";
						} else {
							$doLike = in_array($attr, array('body', 'subject', 'hostname', 'name', 'homepage', 'mail', 'title', 'slug'));
							$field = $doLike ? "{$field} LIKE" : $field;
							$value = str_replace('*', '%', $value);
							$paginationScope[$field] = $doLike ? "%{$value}%" : $value;
						}
					}
				}

				$this->Session->write('Comment.filter', $filter);
			}
		}

		$results = $this->paginate('Comment', $paginationScope);

		$this->countUnpublished();
		$this->set('status', $status);
		$this->set('results', $results);
		$this->setCrumb(
			'/admin/node/contents',
			array(__t('Comments'))
		);
		$this->title($title);
	}

	public function admin_view($id) {
		$comment = $this->Comment->findById($id) or $this->redirect('/admin/comment');
		$this->data = $comment;

		$this->setCrumb(
			'/admin/node/contents',
			array(__t('Comments'), '/admin/comment/'),
			array(__t('Comment details'))
		);
		$this->title(__t('Comment Details'));
	}

	public function admin_approve($id) {
		return $this->Comment->approve($id);
	}

	public function admin_unapprove($id) {
		return $this->Comment->unapprove($id);
	}

	public function admin_delete($id) {
		$result = $this->Comment->delete($id);

		if ($this->request->params['action'] == 'admin_delete') {
			if ($result) {
				$this->flashMsg(__t('Comment has been deleted.'));
			} else {
				$this->flashMsg(__t('Comment could not be deleted.'), 'error');
			}

			$this->redirect($this->referer());
		} else {
			return $result;
		}
	}
}