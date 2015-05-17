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
namespace Field\Model\Entity;

use Cake\Validation\Validator;
use CMS\View\View;

/**
 * Provides methods for dealing with Field API.
 */
trait FieldTrait
{

    /**
     * Renders this field.
     *
     * @return string
     */
    public function render(View $view)
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->render($this, $view);
        }
        return '';
    }

    /**
     * Renders field in edit mode.
     *
     * @return string
     */
    public function edit(View $view)
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->edit($this, $view);
        }
        return '';
    }

    /**
     * Triggers entity's fieldAttached callback.
     *
     * @return void
     */
    public function fieldAttached()
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->fieldAttached($this);
        }
        return true;
    }

    /**
     * Triggers entity's beforeFind callback. Returning NULL will remove the entity
     * from resulting collection, returning FALSE will halt the entire find
     * operation.
     *
     * @param array $options Options given to Query::find()
     * @param bool $primary Whether this find comes from a primary find query or not
     * @return bool|null
     */
    public function beforeFind(array $options, $primary)
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->beforeFind($this, $options, $primary);
        }
        return true;
    }

    /**
     * Triggers entity's validate callback.
     *
     * @param \Cake\Validation\Validator $validator The validator object to be
     * altered.
     * @return bool|null
     */
    public function validate(Validator $validator)
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->validate($this, $validator);
        }
    }

    /**
     * Triggers entity's beforeSave callback. Returning FALSE will halt the save
     * operation.
     *
     * @param mixed $post Post data received for this particular Field
     * @return bool
     */
    public function beforeSave($post)
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->beforeSave($this, $post);
        }
        return true;
    }

    /**
     * Triggers entity's afterSave callback.
     *
     * @return void
     */
    public function afterSave()
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->afterSave($this);
        }
    }

    /**
     * Triggers entity's beforeDelete callback. Returning FALSE will halt the delete
     * operation.
     *
     * @return bool
     */
    public function beforeDelete()
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->beforeDelete($this);
        }

        return true;
    }

    /**
     * Triggers entity's afterDelete callback.
     *
     * @return void
     */
    public function afterDelete()
    {
        $handler = $this->get('metadata')->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->afterDelete($this);
        }

        return true;
    }
}
