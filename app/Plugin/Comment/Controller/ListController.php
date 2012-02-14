<?php
/**
 * Unpublished Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Comment.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
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
                    if ($update) { # approve | unapprove
                        if (!$this->Quickapps->isAdmin() &&
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
                        if (!$this->Quickapps->isAdmin() &&
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

        $results = $this->paginate('Comment', array('Comment.status' => $filter));

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
        return $this->Comment->updateAll(array('Comment.status' => 1), array('Comment.id' => $id));
    }

    public function admin_unapprove($id) {
        return $this->Comment->updateAll(array('Comment.status' => 0), array('Comment.id' => $id));
    }

    public function admin_delete($id) {
        return $this->Comment->delete($id);
    }
}