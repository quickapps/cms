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
namespace Block;

use Block\Model\Entity\Block;
use Cake\Validation\Validator;
use Cake\View\View;

/**
 * Base class for widget blocks.
 *
 * Every widget class must extend this class to properly work.
 */
class Widget
{

    /**
     * This method should return the rendered widget to be presented to end-users.
     *
     * @param \Block\Model\Entity\Block $block Widget information
     * @param \Cake\View\View $view Instance of view class, useful for rendering
     *  template elements
     * @return string
     */
    public function render(Block $block, View $view)
    {
    }

    /**
     * This method should return all the Form input elements that user will be able
     * to tweak in the widget configuration page at Backend.
     *
     * @param \Block\Model\Entity\Block $block Widget information
     * @param \Cake\View\View $view Instance of view class, useful for rendering
     *  template elements
     * @return string
     */
    public function settings(Block $block, View $view)
    {
    }

    /**
     * This method should alter the provided Validator object and add custom
     * validation rules, these rules will be applied when saving the values provided
     * by all the Form input elements rendered by the "settings()" method.
     *
     * @param \Block\Model\Entity\Block $block Widget information
     * @param array $settings The information to be validated
     * @param \Cake\Validation\Validator $validator The validator instance that will
     *  be applied when saving widget's settings.
     * @return string
     */
    public function validateSettings(Block $block, array $settings, Validator $validator)
    {
    }

    /**
     * This method should return an associative array hold default values for the
     * Form input elements provided by the "settings()" method.
     *
     * @param \Block\Model\Entity\Block $block Widget information
     * @return array
     */
    public function defaultSettings(Block $block)
    {
        return [];
    }

    /**
     * This callback is invoked before widget information is persisted in DB.
     * Returning FALSE will halt the save operation. Anything else will be ignored.
     *
     * @param \Block\Model\Entity\Block $block Widget information
     * @return bool|null
     */
    public function beforeSave(Block $block)
    {
        return true;
    }

    /**
     * This callback is invoked after widget information was persisted in DB.
     *
     * @param \Block\Model\Entity\Block $block Widget information
     * @return void
     */
    public function afterSave(Block $block)
    {
    }

    /**
     * This callback is invoked before widget is removed from DB. Returning FALSE
     * will halt the delete operation. Anything else will be ignored.
     *
     * @param \Block\Model\Entity\Block $block Widget information
     * @return bool|null
     */
    public function beforeDelete(Block $block)
    {
        return true;
    }

    /**
     * This callback is invoked after widget was removed from DB.
     *
     * @param \Block\Model\Entity\Block $block Widget information
     * @return void
     */
    public function afterDelete(Block $block)
    {
    }
}
