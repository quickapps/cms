<?php
/**
 * Node Controller Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class NodeHookComponent extends Component {
    var $Controller = null;
    var $components = array('Hook');

    function initialize(&$Controller) {
        $this->Controller = $Controller;
    }

    function get_node_types() {
        $NodeType = ClassRegistry::init('Node.NodeType');
        $types = $NodeType->find('all', array('recursive' => -1, 'fields' => array('type', 'name')));

        return $types['NodeType'];
    }
}