<?php
/**
 * Menu Link Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Menu.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class MenuLink extends MenuAppModel {
	public $name = 'MenuLink';
	public $useTable = "menu_links";
	public $primaryKey = 'id';
	public $displayField = 'link_title';
	public $order = array('MenuLink.lft' => 'ASC');
	public $actsAs = array(
		'Serialized' => array('options'),
		'Tree'
	);
	public $validate = array(
		'link_title' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Menu link title can not be empty.'),
		'router_path' => array('required' => true, 'allowEmpty' => false, 'rule' => 'validatePath', 'message' => 'Invalid link path.')
	);

	public function beforeSave($options = array()) {
		if (isset($this->data['MenuLink']['router_path']) && $this->data['MenuLink']['router_path'] !== '/') {
			// fix: paths must never end with '/'
			$this->data['MenuLink']['router_path'] = preg_replace('/\/{2,}/', '',  "{$this->data['MenuLink']['router_path']}//");
		}

		if (isset($this->data['MenuLink']['id']) &&
			isset($this->data['MenuLink']['status']) &&
			$this->data['MenuLink']['status'] == 0
		) {
			$this->Behaviors->detach('Tree');

			$root = $this->findById($this->data['MenuLink']['id']);

			$this->Behaviors->attach('Tree',
				array(
					'parent' => 'parent_id',
					'left' => 'lft',
					'right' => 'rght',
					'scope' => "MenuLink.menu_id = '{$root['MenuLink']['menu_id']}'"
				)
			);

			$children = $this->children($this->data['MenuLink']['id']);
			$children = Hash::extract($children, '{n}.MenuLink.id');

			$this->Behaviors->detach('Tree');
			$this->updateAll(
				array('MenuLink.status' => 0),
				array('MenuLink.id' => $children)
			);
		}

		return true;
	}

	public function afterSave($created) {
		ClassRegistry::init('Block.Block')->clearCache();
	}

	public function afterDelete() {
		ClassRegistry::init('Block.Block')->clearCache();
	}

	public function validatePath($check) {
		$value = array_values($check);
		$value = $value[0];

		if (empty($value)) {
			return false;
		}

		if (!in_array($value[0], array('/', '#'))) {
			$this->data['MenuLink']['link_path'] = $value;
			$this->data['MenuLink']['router_path'] = null;

			return Validation::url($value) || preg_match('/^mailto\:(.*)$/iu', $value);
		} else {
			return true;
		}

		$validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=~') . '\/0-9a-z\p{L}\p{N}]*)';

		return preg_match('/^' . $validChars . '$/iu', $value);
	}
}