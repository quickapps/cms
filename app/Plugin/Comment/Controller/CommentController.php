<?php
/**
 * Comment Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Comment.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class CommentController extends CommentAppController {
    public $name = 'Comment';
    public $uses = array();

    public function admin_index() {
        $this->redirect('/admin/comment/published');
    }
}