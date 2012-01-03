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
    private $__tmp = array();
    public $name = 'Node';
    public $useTable = "nodes";
    public $order = array('Node.modified' => 'DESC');
    public $actsAs = array(
        'Sluggable',
        'Field.Fieldable' => array('belongsTo' => 'NodeType-{Node.node_type_id}'),
        'Serialized' => array('params')
    );
    public $validate = array(
        'title' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Node title can not be empty.')
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
        if (empty($results) || !$primary) {
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
        } else {
            if (isset($this->data['Node']['id'])) {
                $this->id = $this->data['Node']['id'];
                $this->__tmp['slugRegenerated'] = $this->field('slug');
            }
        }

        $r = isset($this->data['Node']['node_type_base']) ? $this->hook("{$this->data['Node']['node_type_base']}_before_validate", $this) : null;

        return ($r !== false);
    }

    public function beforeSave($options) {
        $roles = implode("|", Set::extract('/Role/Role', $this->data));
        $this->data['Node']['roles_cache'] = !empty($roles) ? "|" . $roles . "|" : '';;

        if (isset($this->data['Node']['node_type_base'])) {
            $this->data['Node']['node_type_base'] = Inflector::underscore($this->data['Node']['node_type_base']);
            $this->__tmp['node_type_base'] = $this->data['Node']['node_type_base'];
        }

        $this->data['Node']['comment'] = !isset($this->data['Node']['comment']) || empty($this->data['Node']['comment']) ? 0 : $this->data['Node']['comment'];

        $r = isset($this->data['Node']['node_type_base']) ? $this->hook("{$this->data['Node']['node_type_base']}_before_save", $this) : null;
        $data = $this->data;
        $this->__tmp['data'] = $data;

        unset($data['MenuLink']);

        $this->data = $data;

        return ($r !== false);
    }

    public function afterSave($created) {
        if (isset($this->data['Node']['slug'])) {
            Cache::delete("node_{$this->data['Node']['slug']}");
        }

        if (isset($this->__tmp['node_type_base'])) {
            $this->hook("{$this->__tmp['node_type_base']}_after_save", $this);
        }

        $node = array();
        $nId = $created ? $this->id : $this->__tmp['data']['Node']['id'];
        $MenuLink = ClassRegistry::init('Menu.MenuLink');

        if (isset($this->__tmp['slugRegenerated'])) {
            $node = $this->findById($nId);
            $path = "/{$node['Node']['node_type_id']}/{$this->__tmp['slugRegenerated']}.html";
            $link_exists = $MenuLink->find('first',
                array(
                    'conditions' => array(
                        'MenuLink.router_path' => $path
                    ),
                    'recursive' => -1
                )
            );

            if ($link_exists) {
                $update = array(
                    'MenuLink' => array(
                        'id' => $link_exists['MenuLink']['id'],
                        'link_title' => $link_exists['MenuLink']['link_title'],
                        'description' => $link_exists['MenuLink']['description'],
                        'router_path' => "/{$node['Node']['node_type_id']}/{$node['Node']['slug']}.html"
                    )
                );

                $MenuLink->Behaviors->detach('Tree');
                $MenuLink->save($update);
            }

            return;
        }

        if (isset($this->__tmp['data']['Node']['menu_link']) &&
            $this->__tmp['data']['Node']['menu_link'] &&
            isset($this->__tmp['data']['MenuLink']) &&
            !empty($this->__tmp['data']['MenuLink'])
        ) { # add to menu
            $this->recursive = -1;
            $node = $this->findById($nId);
            $path = "/{$node['Node']['node_type_id']}/{$node['Node']['slug']}.html";
            $link_exists = $MenuLink->find('first',
                array(
                    'conditions' => array(
                        'MenuLink.router_path' => $path
                    ),
                    'recursive' => -1
                )
            );

            if (is_numeric($this->__tmp['data']['MenuLink']['parent_id'])) {
                $link = $MenuLink->findById($this->__tmp['data']['MenuLink']['parent_id']);
                $menu_id = $link['MenuLink']['menu_id'];
                $parent_id = $this->__tmp['data']['MenuLink']['parent_id'];
            } else {
                $menu_id = $this->__tmp['data']['MenuLink']['parent_id'];
                $parent_id = 0;
            }

            $MenuLink->Behaviors->detach('Tree');
            $MenuLink->Behaviors->attach('Tree',
                array(
                    'parent' => 'parent_id',
                    'left' => 'lft',
                    'right' => 'rght',
                    'scope' => "MenuLink.menu_id = '{$menu_id}'"
                )
            );

            if ($link_exists) {
                if ($link_exists['MenuLink']['id'] == $parent_id) {
                    return;
                }

                if ($link_exists['MenuLink']['menu_id'] != $menu_id ||
                    $link_exists['MenuLink']['parent_id'] != $parent_id
                ) {
                    $MenuLink->removeFromTree($link_exists['MenuLink']['id'], true);
                } elseif (
                    $link_exists['MenuLink']['link_title'] != $this->__tmp['data']['MenuLink']['link_title'] ||
                    $link_exists['MenuLink']['description'] != $this->__tmp['data']['MenuLink']['description']
                ) {
                    $MenuLink->Behaviors->detach('Tree');

                    $update = array(
                        'MenuLink' => array(
                            'id' => $link_exists['MenuLink']['id'],
                            'link_title' => $this->__tmp['data']['MenuLink']['link_title'],
                            'description' => $this->__tmp['data']['MenuLink']['description'],
                            'router_path' => $path
                        )
                    );

                    $MenuLink->save($update);

                    return;
                } else {
                    return;
                }
            }

            $data = array(
                'MenuLink' => array(
                    'menu_id' => $menu_id,
                    'parent_id' => $parent_id,
                    'router_path' => $path,
                    'description' => $this->__tmp['data']['MenuLink']['description'],
                    'link_title' => $this->__tmp['data']['MenuLink']['link_title'],
                    'module' => 'Menu',
                    'target' => '_self',
                    'expanded' => 0,
                    'status' => 1
                )
            );

            $MenuLink->save($data);
        } else { # remove from menu
            if (!$created) {
                $node = $this->findById($nId);
                $link = $MenuLink->find('first',
                    array(
                        'conditions' => array(
                            'MenuLink.router_path' => "/{$node['Node']['node_type_id']}/{$node['Node']['slug']}.html"
                        ),
                        'recursive' => -1
                    )
                );

                if ($link) {
                    $MenuLink->Behaviors->detach('Tree');
                    $MenuLink->Behaviors->attach('Tree',
                        array(
                            'parent' => 'parent_id',
                            'left' => 'lft',
                            'right' => 'rght',
                            'scope' => "MenuLink.menu_id = '{$link['MenuLink']['menu_id']}'"
                        )
                    );

                    $MenuLink->removeFromTree($link['MenuLink']['id'], true);
                }
            }
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