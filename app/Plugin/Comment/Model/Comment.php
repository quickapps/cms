<?php
/**
 * Comment Model
 *
 * PHP version 5
 *
 * @category
 * @package  QuickApps.Plugin.Comment.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class Comment extends CommentAppModel {
    private $__nodeData = null; # tmp holder
    public $name = 'Comment';
    public $useTable = "comments";
    public $primaryKey = 'id';
    public $order = array('Comment.created' => 'DESC');
    public $actsAs = array('Comment.BBCode' => array('fields' => array('body')));

    public $belongsTo  = array(
        'Node' => array(
            'className' => 'Node.Node',
            'fields' => array('id', 'slug', 'title'),
            'counterCache' => true,
            'counterScope' => array('Comment.status' => 1)
        ),
        'User' => array(
            'className' => 'User.User',
            'fields' => array('id', 'username', 'name', 'email', 'avatar')
        )
    );

    public $validate = array(
        'name' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => 'notEmpty',
            'message' => 'Commenter name required'
        ),
        'email' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => 'email',
            'message' => 'Invalid email'
        ),
        'homepage' => array(
            'required' => false,
            'allowEmpty' => true,
            'rule' => array('url', true), # strict url
            'message' => 'Invalid homepage URL'
        ),
        'body' => array(
            'required' => true,
            'allowEmpty' => false,
            'rule' => 'notEmpty',
            'message' => 'Comment body can not be empty'
        )
    );

    public function beforeValidate() {
        if (!isset($this->data['Comment']['node_id'])) {
            return false;
        }

        $this->Node->recursive = 1;
        $this->__nodeData = $this->Node->findById($this->data['Comment']['node_id']);

        if (!$this->__nodeData) {
            return false;
        }

        App::uses('CakeSession', 'Model/Datasource');

        $userId = CakeSession::read('Auth.User.id');

        if (!$userId) { # anonymous
            switch ($this->__nodeData['NodeType']['comments_anonymous']) {
                #name
                case 0: #mail not sended, not requierd | name sended but not required
                    unset($this->validate['name'], $this->validate['email']);

                    if (empty($this->data['Comment']['name'])) {
                        $this->data['Comment']['name'] = __t('Anonymous');
                    }
                break;

                #name
                #email
                #host
                case 1: #mail optional, can be empty, if it is not -> must be validated | name optional
                   $this->validate['email']['allowEmpty'] = true;

                    if (empty($this->data['Comment']['name'])) {
                        $this->data['Comment']['name'] = __t('Anonymous');
                    }
                break;

                #name*
                #email*
                #host
                case 2: #mail/name required
                    # already set as class attribute
                break;
            }

            $this->data['Comment']['status'] = 0; # anonymous comments must always be approved by administrators
            $this->data['Comment']['user_id'] = 0; # belongs to no one
        } else {
            unset($this->validate['name'], $this->validate['email'], $this->validate['homepage']);

            $this->data['Comment']['status'] = intval($this->__nodeData['NodeType']['comments_approve']);
            $this->data['Comment']['user_id'] = $userId;
        }

        $r = $this->hook('comment_before_validate', $this, array('collectReturn' => true, 'break' => true, 'breakOn' => false));

        return !in_array(false, (array)$r, true);
    }

    public function beforeSave() {
        if (isset($this->data['Comment']['node_id'])) {
            /* clear related node cache */
            $this->Node->id = $this->data['Comment']['node_id'];
            $nSlug = $this->Node->field('slug');

            Cache::delete("node_{$nSlug}");
        }

        /* new comment */
        if (!isset($this->data['Comment']['id'])) {
            #prepare body
            $this->data['Comment']['body'] = html_entity_decode(strip_tags($this->data['Comment']['body'])); #filter

            # prepare subject
            if (!isset($this->data['Comment']['subject']) || empty($this->data['Comment']['subject'])) {
                $this->data['Comment']['subject'] = $this->__defaultSubject($this->data['Comment']['body']);
            }

            # prepare hostname
            $this->data['Comment']['hostname'] = env('REMOTE_ADDR');
        }

        $r = $this->hook('comment_before_save', $this, array('collectReturn' => true, 'break' => true, 'breakOn' => false));

        return !in_array(false, (array)$r, true);
    }

    private function __defaultSubject($string, $len = 30) {
        # ignore quotes
        $__string = $string;
        $string = preg_replace('#\[quote(.*?)\](.*)\[/quote\]#U', '', $string);
        $string = $this->Behaviors->BBCode->bb_parse($string);
        $string = html_entity_decode(strip_tags($string));

        if (strlen($string) <= $len) {
            $string = trim($string);

            return $string;
        }

        $string = substr($string, 0, $len + 1);

        if ($last_space = strrpos($string, ' ')) {
            $string = substr($string, 0, $last_space);
        } else {
            $string = substr($string, 0, $len);
        }

        $string = trim($string);
        $string = empty($string) && strpos($__string, '[quote') ? __d('comment', 'Quoting') : $string;
        $string = empty($string) ? __d('comment', 'No subject') : $string;

        return $string;
    }
}