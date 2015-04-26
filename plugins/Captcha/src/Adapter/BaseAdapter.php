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
namespace Captcha\Adapter;

use Cake\Core\InstanceConfigTrait;
use Cake\Network\Request;
use Cake\View\View;

/**
 * Base CAPTCHA adapter class.
 *
 * All CAPTCHA adapters (reCAPTCHA, AYAH, etc) should extends this class.
 */
class BaseAdapter
{

    use InstanceConfigTrait;

    /**
     * Default configuration for this adapter.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'name' => 'CAPTCHA Adapter',
    ];

    /**
     * Constructor.
     *
     * @param array $config Configurable options
     */
    public function __construct(array $config = [])
    {
        $this->config($config);
    }

    /**
     * Gets or set Adapter's name.
     *
     * @param string|null $name The name to set, or null to get current name.
     * @return string Adapter name, e.g. `reCAPTCHA`
     */
    public function name($name = null)
    {
        if ($name !== null) {
            $this->config('name', $name);
        }
        return $this->config('name');
    }

    /**
     * Renders Form input elements for holding configurable parameters that users
     * can tweak (private key, public key, etc).
     *
     * @param \Cake\View\View $view The view instance for which form elements should
     *  be rendered
     * @return string HTML containing form inputs (text boxes, check boxes, etc)
     */
    public function settings(View $view)
    {
        return '';
    }

    /**
     * Renders the CAPTCHA element for later use in web form.
     *
     * @param \Cake\View\View $view The view instance for which the CAPTCHA should
     *  be rendered
     * @return string HTML
     */
    public function render(View $view)
    {
        return '';
    }

    /**
     * Validates the given POST information.
     *
     * @param \Cake\Network\Request $request Current request object, commonly used
     *  to extract POST or Session information.
     * @return bool True if valid, false otherwise
     */
    public function validate(Request $request)
    {
        return false;
    }
}
