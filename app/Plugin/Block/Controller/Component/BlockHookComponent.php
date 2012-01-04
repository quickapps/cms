<?php
/**
 * Block Controller Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Block.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class BlockHookComponent extends Component {
    public $Controller = null;

    public function initialize(&$Controller) {
        $this->Controller = $Controller;
    }

    public function blocks_list($params = array()) {
        $params = array_merge($params, array('recursive' => 2));
        $Block = (isset($this->Controller->Block) &&  is_object($this->Controller->Block)) ? $this->Controller->Block : ClassRegistry::init('Block.Block');

        $Block->Menu->unbindModel(
            array('hasMany' => array('Block'))
        );

        $blocks = $Block->find('all', $params);

        return $blocks;
    }
}