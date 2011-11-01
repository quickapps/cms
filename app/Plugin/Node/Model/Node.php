<?php
/**
 * Node Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class Node extends NodeAppModel {
    public $name = 'Node';
    public $useTable = "nodes";
    public $order = array('Node.modified' => 'DESC');
    public $actsAs = array(
        'Sluggable', 
        'Field.Fieldable' => array('belongsTo' => 'NodeType-{Node.node_type_id}'),
        'Serialized' => array('params')
    );
    public $validate = array(
        'title' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Node title can not be empty')
    );

    public $belongsTo = array(
        'NodeType' => array(
            'className' => 'Node.NodeType'
        )
    );

    public $hasAndBelongsToMany = array(
        'Role' => array(
            'joinTable' => 'nodes_roles',
            'className' => 'User.Role',
            'foreignKey' => 'node_id',
            'associationForeignKey' => 'role_id',
            'unique' => true,
            'dependent' => false
        )
    );

    public function afterFind($results, $primary) {
        if (empty($results) || !$primary ) {
            return $results;
        }

        foreach ($results as &$result) {
            if (empty($result['Node']['node_type_base'])) {
                continue;
            }

            $this->hook("{$result['Node']['node_type_base']}_after_find", $result, array('collectReturn' => false));
        }

        return $results;
    }

    public function beforeValidate() {
        if (!isset($this->data['Node']['regenerate_slug']) || !$this->data['Node']['regenerate_slug']) {
            $this->Behaviors->detach('Sluggable');
            $this->Behaviors->attach('Sluggable', array('overwrite' => false));
        }

        $r = isset($this->data['Node']['node_type_base']) ? $this->hook("{$this->data['Node']['node_type_base']}_before_validate", $this) : null;

        return ($r !== false);
    }

    public function beforeSave($options) {
        $roles = implode("|", Set::extract('/Role/Role', $this->data));
        $this->data['Node']['roles_cache'] = !empty($roles) ? "|" . $roles . "|" : '';;

        if (isset($this->data['Node']['node_type_base'])) {
            $this->node_type_base = $this->data['Node']['node_type_base'];
        }

        $this->data['Node']['comment'] = !isset($this->data['Node']['comment']) || empty($this->data['Node']['comment']) ? 0 : $this->data['Node']['comment'];

        $r = isset($this->data['Node']['node_type_base']) ? $this->hook("{$this->data['Node']['node_type_base']}_before_save", $this) : null;

        return ($r !== false);
    }

    public function afterSave($created) {
        if (isset($this->data['Node']['slug'])) {
            Cache::delete("node_{$this->data['Node']['slug']}");
        }

        if ($this->node_type_base) {
            $this->hook("{$this->node_type_base}_after_save", $this);
        }
    }

    public function beforeDelete($cascade) {
        # bind comments and delete them
        $this->bindComments();

        $this->recursive = -1;
        $n = $this->data = $this->read();
        $r = isset($n['Node']['node_type_base']) ? $this->hook("{$n['Node']['node_type_base']}_before_delete", $this) : null;

        return ($r !== false);
    }

    public function afterDelete() {
        if (isset($this->data['Node']['slug'])) {
            Cache::delete("node_{$this->data['Node']['slug']}");
        }

        $r = isset($this->data['Node']['node_type_base']) ? $this->hook("{$this->data['Node']['node_type_base']}_after_delete", $this) : null;

        $this->unbindComments();

        return (is_array($r) ? (!in_array(false, $r, true)) : ($r !== false));
    }

    public function bindComments() {
        return $this->bindModel(
            array(
                'hasMany' => array(
                    'Comment' => array(
                        'className' => 'Comment.Comment',
                        'dependent' => true
                    )
                )
            )
        );
    }

    public function unbindComments() {
        return $this->unbindModel(array('hasMany' => array('Comment')));
    }
}