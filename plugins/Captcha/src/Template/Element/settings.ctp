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

foreach (CaptchaManager::adapters() as $name => $config) {
    $options[$name] = CaptchaManager::adapter($name)->name();
}
?>

<?=
    $this->Form->input('default_adapter', [
        'id' => 'default-adapter',
        'type' => 'select',
        'options' => $options,
        'label' => 'Default CAPTCHA method',
        'onchange' => 'toggleSettings()'
    ]);
?>
<em class="help-block"><?= __d('captcha', 'Select which CAPTCHA component to use by default. Additional configuration may be required depending on the selected component.'); ?></em>

<hr />

<?php foreach (CaptchaManager::adapters() as $name => $config): ?>
    <fieldset class="<?= $name; ?>-adapter adapter-info">
        <?php
            $adapter = CaptchaManager::adapter($name);
            $prefix = $this->Form->prefix();
        ?>
        <legend><?= $adapter->name(); ?></legend>
        <?php $this->Form->prefix("{$prefix}{$name}:"); ?>
        <?= $adapter->settings($this); ?>
        <?php $this->Form->prefix($prefix); ?>
    </fieldset>
<?php endforeach; ?>


<script type="text/javascript">
    $(document).ready(function () {
        toggleSettings();
    });

    function toggleSettings()
    {
        $('fieldset.adapter-info').hide();
        var $fieldset = $('fieldset.' + $('#default-adapter').val() + '-adapter');
        $fieldset.show();
    }
</script>