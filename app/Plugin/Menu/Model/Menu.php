<?php
/**
 * Menu Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Menu.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class Menu extends MenuAppModel {
    public $name = 'Menu';
    public $useTable = "menus";
    public $primaryKey = 'id';
    public $validate = array(
        'title' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Menu title can not be empty'),
    );

    public $hasMany = array(
        'MenuLink' => array(
            'className' => 'Menu.MenuLink',
            'foreignKey' => 'menu_id',
            'order' => 'MenuLink.lft ASC',
            'conditions' => array('MenuLink.status' => 1),
            'dependent' => false # must BE DELETED MANUALY
        )
    );

    public $belongsTo = array(
        'Block' => array(
            'className' => 'Blocks.Block',
            'foreignKey' => 'id',
            'associationForeignKey' => 'delta',
            'conditions' => array('Block.module' => 'menu')
        )
    );

    public function beforeDelete($cascade) {
        // delete block
        $this->Block->deleteAll(
            array(
                'Block.delta' => $this->id,
                'Block.module' => 'menu'
            )
        );

        # links delete
        $this->MenuLink->Behaviors->detach('Tree');
        $this->MenuLink->Behaviors->attach('Tree',
            array(
                'parent' => 'parent_id',
                'left'   => 'lft',
                'right'  => 'rght',
                'scope'  => "MenuLink.menu_id = {$this->id}"
            )
        );
        $this->MenuLink->deleteAll(
            array(
                'MenuLink.menu_id' => $this->id
            )
        );

        return true;
    }

    public function beforeSave() {
        if (!isset($this->data['Menu']['id'])) {
            /* menu slug */
            $id = Inflector::slug($this->data['Menu']['title'], '-');
            $i = 1;
            $_id = $id;
            $c = '';

            while ( $this->find('count', array('conditions' => array('Menu.id' => $_id))) > 0) {
                $c = '-' . $i;
                $_id = $id . $c;
                $i++;
            }

            $this->data['Menu']['id'] = strtolower($id . $c);
            /* end menu slug */

            // Create block
            $bdata['Block'] = array(
                'module' => 'menu',
                'delta' => $this->data['Menu']['id'],
                'themes_cache' => '',
                'ordering' => 0,
                'status' => 0,
                'visibility' => 0,
                'pages' => '',
                'title' => $this->data['Menu']['title'],
                'locale' => $this->data['Menu']['locale']
            );
            $this->Block->save($bdata, false);
        }

        return true;
    }
}