<?php
/**
 * FieldFile Controller Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Field.Fields.FieldFile.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class FieldFileHookComponent extends Component {
	public $Controller = null;

	public function initialize(Controller $Controller) {
		$Controller->Security->unlockedFields[] = 'FieldData.FieldFile';
	}
}