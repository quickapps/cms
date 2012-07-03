<?php
/**
 * Node Controller Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Node.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class NodeHookComponent extends Component {
	public $Controller = null;

	public function initialize(Controller $Controller) {
		$this->Controller = $Controller;
	}

	public function get_node_types() {
		$NodeType = ClassRegistry::init('Node.NodeType');
		$types = $NodeType->find('all', array('recursive' => -1, 'fields' => array('type', 'name')));

		return $types['NodeType'];
	}

	public function search_results($query) {
		return $this->Controller->requestAction("/s/{$query}");
	}
}