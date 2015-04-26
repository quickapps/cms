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
namespace Captcha;

use Captcha\Adapter\BaseAdapter;
use Captcha\Error\AdapterNotFoundException;
use QuickApps\Core\Plugin;

/**
 * Class for dealing with CAPTCHA adapters.
 *
 * # Usage:
 *
 * ## Rendering the CAPTCHA form element in any template
 *
 * ```php
 * CaptchaManager::adapter()->render($this->Form);
 * ```
 *
 * ## Validating CAPTCHA in controller
 *
 * ```php
 * CaptchaManager::adapter()->validate($this->request->data());
 * ```
 *
 * ## Registering CAPTCHA adapters
 *
 * ```php
 * CaptchaManager::addAdapter('reCaptcha', 'MyPlugin\Adapter\RecaptchaAdapter');
 * ```
 *
 * Once registered it can be used as follows:
 *
 * ```php
 * $adapter = CaptchaManager::adapter('reCaptcha');
 * ```
 */
class CaptchaManager
{

    /**
     * List of registered adapters.
     *
     * @var array
     */
    protected static $_adapters = [];

    /**
     * Gets an instance of the given adapter name (or default adapter if not given).
     *
     * @param string|null $name Name of the adapter, or null to use default adapter
     *  selected in Captcha plugin's setting page. The latest registered adapter
     *  will be used if no default adapter has been selected yet
     * @param array $config Options to be passed to Adapter's `config()` method, if
     *  not given it will try to get such parameters from Captcha plugin's settings
     * @return \Captcha\Adapter\BaseAdapter
     * @throws \Captcha\Error\AdapterNotFoundException When no adapter was found
     */
    public static function adapter($name = null, array $config = [])
    {
        $class = null;
        if ($name === null) {
            $default = (string)Plugin::get('Captcha')->settings('default_adapter');
            if (!empty($default)) {
                return static::adapter($default, $config);
            }
            $class = end(static::$_adapters);
        } elseif (isset(static::$_adapters[$name])) {
            $class = static::$_adapters[$name];
        }

        if (empty($config)) {
            $config = (array)Plugin::get('Captcha')->settings($name);
        }

        if (is_string($class) && class_exists($class)) {
            $class = new $class($config);
            $created = true;
        }

        if ($class instanceof BaseAdapter) {
            if (!isset($created)) {
                $class->config($config);
            }
            return $class;
        }

        throw new AdapterNotFoundException(__d('captcha', 'The captcha adapter "{0}" was not found.'), $name);
    }

    /**
     * Gets the full list of all registered adapters.
     *
     * @return array
     */
    public static function adapters()
    {
        return static::$_adapters;
    }

    /**
     * Registers a new CAPTCHA adapter, or overwrites if already registered.
     *
     * ## Usage:
     *
     * ```php
     * CaptchaManager::addAdapter('reCaptcha', 'MyPlugin\Adapter\RecaptchaAdapter');
     * ```
     *
     * @param string $name Name of identify this particular adapter.
     * @param \Captcha\Adapter\BaseAdapter|string $class Either an instanced
     *  adapter class or a fully qualified class name
     */
    public static function addAdapter($name, $class)
    {
        static::$_adapters[$name] = $class;
    }
}
