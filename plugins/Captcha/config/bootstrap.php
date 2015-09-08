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

use Captcha\CaptchaManager;

/**
 * Registers "Are You A Human" CAPTCHA adapter
 */
CaptchaManager::addAdapter('ayah', 'Captcha\\Adapter\\AyahAdapter');

/**
 * Registers "reCaptcha" CAPTCHA adapter
 */
CaptchaManager::addAdapter('reCaptcha', 'Captcha\\Adapter\\RecaptchaAdapter');
