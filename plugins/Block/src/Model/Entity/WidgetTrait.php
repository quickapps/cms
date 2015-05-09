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
namespace Block\Model\Entity;

use Cake\Validation\Validator;
use Cake\View\View;

/**
 * Provides method for dealing with Widget API.
 */
trait WidgetTrait
{

    /**
     * Renders this block/widget.
     *
     * @return string
     */
    public function render(View $view)
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->render($this, $view);
        }
        return '';
    }

    /**
     * Renders block/widget's settings.
     *
     * @return string
     */
    public function settings(View $view)
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->settings($this, $view);
        }
        return '';
    }

    /**
     * Validates block/widget's settings.
     *
     * @return bool|null
     */
    public function validateSettings(array $settings, Validator $validator)
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->validateSettings($this, $settings, $validator);
        }
    }

    /**
     * Gets block/widget's default settings.
     *
     * @return array
     */
    public function defaultSettings()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->defaultSettings($this);
        }
        return [];
    }

    /**
     * Trigger block/widget's callback.
     *
     * @return bool|null
     */
    public function beforeSave()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->beforeSave($this);
        }
    }

    /**
     * Trigger block/widget's callback.
     *
     * @return void
     */
    public function afterSave()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->afterSave($this);
        }
    }

    /**
     * Trigger block/widget's callback.
     *
     * @return bool|null
     */
    public function beforeDelete()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->beforeDelete($this);
        }
    }

    /**
     * Trigger block/widget's callback.
     *
     * @return void
     */
    public function afterDelete()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->afterDelete($this);
        }
    }
}
