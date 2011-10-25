<?php
/**
 * Node Model Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.Model.Behavior
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class NodeHookBehavior extends ModelBehavior {
    public $fieldData = null;

    // node type: Basic Page
    public function node_content_beforeValidate(&$Model) {
        return true;
    }

    public function node_content_beforeSave(&$Model) {
        return true;
    }

    public function node_content_beforeDelete(&$Model) {
        return true;
    }

    public function node_content_afterFind(&$results) {
        return true;
    }

    public function node_content_afterSave(&$Model) {
        return true;
    }

    public function node_content_afterDelete(&$Model) {
        return true;
    }

    // node type: Custom types
    public function node_beforeValidate(&$Model) {
        return $this->node_content_beforeValidate($Model);
    }

    public function node_beforeSave(&$Model) {
        return $this->node_content_beforeSave($Model);
    }

    public function node_beforeDelete(&$Model) {
        return $this->node_content_beforeDelete($Model);
    }

    public function node_afterFind(&$results) {
        return $this->node_content_afterFind($results);
    }

    public function node_afterSave(&$Model) {
        return $this->node_content_afterSave($Model);
    }

    public function node_afterDelete($Model) {
        return $this->node_content_afterDelete($Model);
    }
}