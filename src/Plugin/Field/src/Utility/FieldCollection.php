<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Field\Utility;

use ArrayObject;
use Cake\Collection\CollectionTrait;

/**
 * Field Collection.
 *
 * Used for manage fields attached to every entity using `Fieldable` behavior.
 *
 * Allows to handle fields as an array list, but it adds a few useful functionalities such as `sortAs()` method,
 * or access a fields by its numeric index or its machine-name (as an associative array).
 */
class FieldCollection extends ArrayObject {

	use CollectionTrait;

/**
 * Class constructor.
 *
 * @param array $fields List of fields
 */
	public function __construct(array $fields = []) {
		parent::__construct($fields, ArrayObject::STD_PROP_LIST);
	}

/**
 * Allows access to fields by its numeric index or by its machine-name.
 *
 * ###Example:
 *
 *     $fields => [
 *         [0] => [
 *             [name] => user-age,
 *             [label] => User Age,
 *             [value] => 22,
 *             [extra] => null,
 *             [metadata] => [ ... ]
 *         ],
 *         [1] => [
 *             [name] => user-phone,
 *             [label] => User Phone,
 *             [value] => null,  // no data stored,
 *             [extra] => null, // no data stored
 *             [metadata] => [ ... ]
 *         ]
 *    ];
 *    $collection = new FieldCollection($fields);
 *
 *    if ($collection[1] === $collection['user-phone']) {
 *        echo "SUCCESS";
 *    }
 *
 *    // OUT: SUCCESS
 *
 * @param integer|string $index Numeric index or machine-name
 * @return mixed Field\Model\Entity\Field on success or NULL on failure
 */
	public function offsetGet($index) {
		if (is_string($index)) {
			foreach ($this as $f) {
				if ($f->name == $index) {
					return $f;
				}
			}
		}

		return parent::offsetGet($index);
	}

/**
 * Gets a list of all machine names.
 *
 * @return array List of machine names
 */
	public function getMachineNames() {
		$mn = [];

		foreach ($this as $f) {
			$mn[] = $f->name;
		}

		return $mn;
	}

/**
 * Sorts the list of fields by a given list of machine-names.
 *
 * @param array $machineNames The desire order given as a machine-names list
 * @return void
 */
	public function sortAs($machineNames) {
		$new = [];
		$fields = $this->getArrayCopy();

		foreach ($machineNames as $slug) {
			foreach ($fields as $k => $v) {
				if ($v->name == $slug) {
					$new[] = $v;
					unset($fields[$k]);
				}
			}
		}

		if (!empty($fields)) {
			$new = array_merge($new, $fields);
		}

		$this->exchangeArray($new);
	}

}
