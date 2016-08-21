<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Field\Collection;

use ArrayObject;
use Cake\Collection\CollectionTrait;

/**
 * Field Collection.
 *
 * Used for manage fields attached to table entities using `Fieldable` behavior.
 *
 * Allows to handle fields as an array list, it also adds a few useful
 * functionalities such as `sortAs()` method, or accessing a fields by its
 * numeric index or its machine-name (associative array), see `offsetGet()`.
 */
class FieldCollection extends ArrayObject
{

    use CollectionTrait;

    /**
     * Holds a map of machine-names to index keys.
     *
     * ### Example:
     *
     * ```php
     * [
     *     'user-name' => 0,
     *     'user-phone' => 1,
     *     'user-age' => 3,
     *      // ...
     * ]
     * ```
     *
     * @var array
     */
    protected $_keysMap = [];

    /**
     * Class constructor.
     *
     * @param array $fields List of fields
     */
    public function __construct(array $fields = [])
    {
        parent::__construct($fields, ArrayObject::STD_PROP_LIST);
        foreach ($fields as $key => $field) {
            $this->_keysMap[$field->name] = $key;
        }
    }

    /**
     * Allows access fields by numeric index or by machine-name.
     *
     * ### Example:
     *
     * ```php
     * $fields => [
     *     [0] => [
     *         [name] => user-age,
     *         [label] => User Age,
     *         [value] => 22,
     *         [extra] => null,
     *         [metadata] => [ ... ]
     *     ],
     *     [1] => [
     *         [name] => user-phone,
     *         [label] => User Phone,
     *         [value] => null,
     *         [extra] => null,
     *         [metadata] => [ ... ]
     *     ]
     * ];
     *
     * $collection = new FieldCollection($fields);
     * if ($collection[1] === $collection['user-phone']) {
     *    echo "SUCCESS";
     * }
     *
     * // outputs: SUCCESS
     * ```
     *
     * @param int|string $index Numeric index or machine-name
     * @return mixed \Field\Model\Entity\Field on success or NULL on failure
     */
    public function offsetGet($index)
    {
        if (is_string($index) && isset($this->_keysMap[$index])) {
            $index = $this->_keysMap[$index];
        }

        return parent::offsetGet($index);
    }

    /**
     * Gets a list of all machine names.
     *
     * @return array List of machine names
     */
    public function machineNames()
    {
        return array_keys($this->_keysMap);
    }

    /**
     * Sorts the list of fields by view mode ordering.
     *
     * Fields might have different orderings for each view mode.
     *
     * @param string $viewMode View mode slug to use for sorting
     * @param int $dir either SORT_DESC or SORT_ASC
     * @return \Field\Collection\FieldCollection
     */
    public function sortByViewMode($viewMode, $dir = SORT_ASC)
    {
        $items = [];
        $sorted = $this->sortBy(function ($field) use ($viewMode) {
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
    public function __debugInfo()
    {
        $out = [];
        foreach ($this as $f) {
            $out[] = $f;
        }

        return $out;
    }
}
