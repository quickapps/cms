<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Virgil-Adrian Teaca <virgil@giulianaeassociati.com>
 * @link     http://www.giulianaeassociati.com
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Captcha\Adapter;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Network\Request;
use Cake\View\View;
use Captcha\Adapter\BaseAdapter;

/**
 * Provides Google ReCAPTCHA.
 *
 * # Usage
 *
 * Method chaining style:
 *
 * ```php
 * $valid = CaptchaManager::adapter('recaptcha')
 *     ->siteKey('6LfydwwTAAAAABPxuvVpS3w70OlV2JDwTnCPqiD1')
 *     ->secretKey('6LfydwwTAAAAAB2z1xzjRbGKkboqXRbodtIU3BLE')
 *     ->validate(($this->request);
 * ```
 *
 * Or passing as arguments:
 *
 * ```php
 * $recaptcha = CaptchaManager::adapter('recaptcha', [
 *     'siteKey' => '6LfydwwTAAAAABPxuvVpS3w70OlV2JDwTnCPqiD1',
 *     'secretKey' => '6LfydwwTAAAAAB2z1xzjRbGKkboqXRbodtIU3BLE'
 * ]);
 *
 * $valid = $recaptcha->validate($this->request);
 * ```
 */
class RecaptchaAdapter extends BaseAdapter
{

    /**
     * {@inheritDoc}
     *
     * Valid options are:
     *
     * - name: Name of this adapter.
     * - siteKey: reCaptcha "Site Key".
     * - secretKey: reCaptcha "Secret Key".
     *
     * @var array
     */
    protected $_defaultConfig = [
        'name' => 'Google ReCAPTCHA',
        'siteKey' => '',
        'secretKey' => '',
    ];

    /**
     * {@inheritDoc}
     */
    public function settings(View $view)
    {
        return $view->element('Captcha.recaptcha_settings');
    }

    /**
     * {@inheritDoc}
     */
    public function render(View $view)
    {
        return $view->element('Captcha.recaptcha_render', ['siteKey' => $this->config('siteKey')]);
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Request $request)
    {
        if ($request->is('post')) {
            // The (User's) Remote Address
            $whatRemoteIP = env('REMOTE_ADDR') ? '&remoteip=' . env('REMOTE_ADDR') : '';
            // The reCAPTCHA data is extracted from Request
            $gRecaptchaResponse = $request->data('g-recaptcha-response');
            // Verify reCAPTCHA data
            $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $this->config('secretKey') . '&response=' . $gRecaptchaResponse . $whatRemoteIP);
            $response = json_decode($response, true);
            // We return the Google server's response 'success' value
            return (bool)$response['success'];
        }

        return false;
    }

    /**
     * Sets reCaptcha Site Key.
     *
     * @param string $key The key
     * @return $this
     */
    public function siteKey($key)
    {
        $this->config('siteKey', $key);
        return $this;
    }

    /**
     * Sets reCaptcha Secret Key.
     *
     * @param string $key The key
     * @return $this
     */
    public function secretKey($key)
    {
        $this->config('secretKey', $key);
        return $this;
    }
}
