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
trait InstanceTrait
{

    /**
     * Gets handler information.
     *
     * @return array
     */
    public function info()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->info();
        }
        return [];
    }

    /**
     * Renders settings form elements.
     *
     * @param \CMS\View\View $view View instance being used
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
     * Gets default configuration settings values.
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
     * Triggers settings validation callback.
     *
     * @param array $settings The settings to be validated
     * @param \Cake\Validation\Validator $validator The validator object that will
     *  be used to validate $settings array
     * @return void
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
     * Renders view-mode's settings form elements.
     *
     * @param \CMS\View\View $view View instance being used
     * @param string $viewMode View-mode name for which render its settings
     * @return void
     */
    public function viewModeSettings(View $view, $viewMode)
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->viewModeSettings($this, $view, $viewMode);
        }

        return '';
    }

    /**
     * Gets default settings for the given view-mode.
     *
     * @param string $viewMode Name of the view mode for which get its default
     *  settings values
     * @return array
     */
    public function defaultViewModeSettings($viewMode)
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->defaultViewModeSettings($this, $viewMode);
        }
        return [];
    }

    /**
     * Triggers view-mode's settings validation callback.
     *
     * @param array $settings The settings to be validated
     * @param \Cake\Validation\Validator $validator The validator object that will
     *  be used to validate $settings array
     * @param string $viewMode The view mode being validated
     * @return void
     */
    public function validateViewModeSettings(array $settings, Validator $validator, $viewMode)
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->validateSettings($this, $settings, $validator, $viewMode);
        }
    }

    /**
     * Triggers callback.
     *
     * @return bool|null
     */
    public function beforeAttach()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->beforeAttach($this);
        }
    }

    /**
     * Triggers callback.
     *
     * @return void
     */
    public function afterAttach()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->afterAttach($this);
        }
    }

    /**
     * Triggers callback.
     *
     * @return bool|null
     */
    public function beforeDetach()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->beforeDetach($this);
        }
    }

    /**
     * Triggers callback.
     *
     * @return void
     */
    public function afterDetach()
    {
        $handler = $this->get('handler');
        if (class_exists($handler)) {
            $handler = new $handler();
            return $handler->afterDetach($this);
        }
    }
}
