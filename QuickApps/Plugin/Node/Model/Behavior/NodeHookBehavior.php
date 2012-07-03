<?php
/**
 * Node Model Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Node.Model.Behavior
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class NodeHookBehavior extends ModelBehavior {
	public $fieldData = null;

	// node type: Basic Page
	public function node_content_before_validate(&$Model) {
		return true;
	}

	public function node_content_before_save(&$Model) {
		return true;
	}

	public function node_content_before_delete(&$Model) {
		return true;
	}

	public function node_content_after_find(&$results) {
		return true;
	}

	public function node_content_after_save(&$Model) {
		return true;
	}

	public function node_content_after_delete(&$Model) {
		return true;
	}

	// node type: Custom types
	public function node_before_validate(&$Model) {
		return $this->node_content_before_validate($Model);
	}

	public function node_before_save(&$Model) {
		return $this->node_content_before_save($Model);
	}

	public function node_before_delete(&$Model) {
		return $this->node_content_before_delete($Model);
	}

	public function node_after_find(&$results) {
		return $this->node_content_after_find($results);
	}

	public function node_after_save(&$Model) {
		return $this->node_content_after_save($Model);
	}

	public function node_after_delete($Model) {
		return $this->node_content_after_delete($Model);
	}
}