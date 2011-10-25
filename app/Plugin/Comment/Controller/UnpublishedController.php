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
class UnpublishedController extends CommentAppController {
    public $name = 'Unpublished';
    public $uses = array('Comment.Comment');

    public function admin_index() {
        if (isset($this->data['Comment']['update'])) {
            if (isset($this->data['Items']['id'])) {
                $update = ( !in_array($this->data['Comment']['update'], array('delete')));

                switch ($this->data['Comment']['update']) {
                    case 'approve':
                        default:
                            $data = array( 'field' => 'status', 'value' => 1);
                        break;
                    case 'unapprove':
                        $data = array('field' => 'status', 'value' => 0);
                    break;
                }

                foreach ($this->data['Items']['id'] as $key => $id) {
                    if ($update) { # update node
                        $this->Comment->id = $id;
                        $this->Comment->saveField($data['field'], $data['value'], false);
                    } else { # delete node
                        switch ($this->data['Comment']['update']) {
                            case 'delete':
                                $this->Comment->delete($id);
                            break;
                        }
                    }
                }
            }

            $this->redirect($this->referer());
        }

        $results = $this->paginate('Comment', array('Comment.status' => 0));

        $this->countUnpublished();
        $this->set('results', $results);
        $this->setCrumb('/admin/node/contents');
        $this->setCrumb(array(__t('Comments')));
        $this->title(__t('Unpublished comments'));
    }
}