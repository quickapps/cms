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

use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\View\View;
use Captcha\Adapter\BaseAdapter;
use QuickApps\Core\Plugin;

/**
 * Provides "Are You A Human" CAPTCHA.
 *
 * # Usage
 *
 * Method chaining style:
 *
 * ```php
 * $valid = CaptchaManager::adapter('ayah')
 *     ->publisherKey('310203ef720d21451c2516f2633c645acadc225a')
 *     ->scoringKey('6233426d2e41a5c37d11c65202fa23c1fca50520')
 *     ->validate(($this->request);
 * ```
 *
 * Or passing as arguments:
 *
 * ```php
 * $ayah = CaptchaManager::adapter('ayah', [
 *     'publisherKey' => '310203ef720d21451c2516f2633c645acadc225a',
 *     'scoringKey' => '6233426d2e41a5c37d11c65202fa23c1fca50520'
 * ]);
 *
 * $valid = $ayah->validate($this->request);
 * ```
 */
class AyahAdapter extends BaseAdapter
{

    /**
     * {@inheritDoc}
     *
     * Valid options are:
     *
     * - name: Name of this adapter.
     * - publisherKey: AYAH "Publisher Key".
     * - scoringKey: AYAH "Scoring Key".
     *
     * @var array
     */
    protected $_defaultConfig = [
        'name' => 'AYAH (Are You A Human)',
        'publisherKey' => '',
        'scoringKey' => '',
    ];

    /**
     * {@inheritDoc}
     */
    public function settings(View $view)
    {
        return $view->element('Captcha.ayah_settings');
    }

    /**
     * {@inheritDoc}
     */
    public function render(View $view)
    {
        return $view->element('Captcha.ayah_render', ['ayah' => $this->_getLib()]);
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Request $request)
    {
        return $this->_getLib()->scoreResult();
    }

    /**
     * Sets AYAH publisher key.
     *
     * @param string $key The key
     * @return $this
     */
    public function publisherKey($key)
    {
        $this->config('publisherKey', $key);
        return $this;
    }

    /**
     * Sets AYAH scoring key.
     *
     * @param string $key The key
     * @return $this
     */
    public function scoringKey($key)
    {
        $this->config('scoringKey', $key);
        return $this;
    }

    /**
     * Gets an instance of AYAH library.
     *
     * @return object
     */
    protected function _getLib()
    {
        require_once Plugin::classPath('Captcha') . 'Lib/ayah.php';
        return new \AYAH([
            'publisher_key' => $this->config('publisherKey'),
            'scoring_key' => $this->config('scoringKey'),
            'web_service_host' => 'ws.areyouahuman.com',
            'use_curl' => true,
            'debug_mode' => Configure::read('debug'),
        ]);
    }
}
