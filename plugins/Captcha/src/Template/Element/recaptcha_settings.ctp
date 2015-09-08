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
?>

<?php echo $this->Form->input('siteKey', ['type' => 'text', 'label' => __d('captcha', 'Site Key *')]); ?>
<em class="help-block"><?php echo __d('captcha', 'e.g. 6LfydwwTAAAAABPxuvVpS3w70OlV2JDwTnCPqiD1'); ?></em>

<?php echo $this->Form->input('secretKey', ['type' => 'text', 'label' => __d('captcha', 'Secret Key *')]); ?>
<em class="help-block"><?php echo __d('captcha', 'e.g. 6LfydwwTAAAAAB2z1xzjRbGKkboqXRbodtIU3BLE'); ?></em>
