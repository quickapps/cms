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
 * Allows to handle fields as an array list, but it adds a few useful
 * functionalities such as `sortAs()` method, or access a fields by its numeric
 * index or its machine-name (like an associative array).
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
 * Allows access fields by numeric index or by machine-name.
 *
 * ### Example:
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
 *             [value] => null,
 *             [extra] => null,
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
 * @return mixed \Field\Model\Entity\Field on success or NULL on failure
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
	public function machineNames() {
		$mn = [];
		foreach ($this as $f) {
			$mn[] = $f->name;
		}
		return $mn;
	}

/**
 * Sorts the list of fields by view mode ordering.
 *
 * Fields might have different orderings for each view mode.
 *
 * @param string $viewMode View mode slug to use for sorting
 * @param int $dir either SORT_DESC or SORT_ASC
 * @return \Field\Utility\FieldCollection
 */
	public function sortByViewMode($viewMode, $dir = SORT_ASC) {
		$items = [];
		$sorted = $this->sortBy(function ($field) use($viewMode) {
			if (isset($field->metadata->view_modes[$viewMode])) {
				return $field->metadata->view_modes[$viewMode]['ordering'];
			}
			return 0;
		}, $dir);

		foreach ($sorted as $item) {
			$items[] = $item;
		}

		return new FieldCollection($items);
	}

/**
 * Returns an array that can be used to describe the internal state of this
 * object.
 *
 * @return array
 */
	public function __debugInfo() {
		$out = [];
		foreach ($this as $f) {
			$out[] = $f;
		}
		return $out;
	}

}
