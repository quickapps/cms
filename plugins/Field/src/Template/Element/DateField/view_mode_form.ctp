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
?>

<?= $this->Form->input('format', ['type' => 'text', 'label' => __d('field', 'Display format')]); ?>
<?php if ($instance->settings['timepicker']): ?>
    <em class="help-block"><?= __d('field', "Defines how date & time is presented to users when content is rendered. e.g.: <code>'Meeting on:' yy-mm-dd 'at' HH:mm</code>, see below for details"); ?></em>
<?php else: ?>
    <em class="help-block"><?= __d('field', "Defines how date is presented to users when content is rendered. e.g.: <code>'Meeting on:' yy-mm-dd</code>, see below for details"); ?></em>
<?php endif; ?>
<ul>
    <li><code>d</code>: <?= __d('field', 'day of month (no leading zero)'); ?></li>
    <li><code>dd</code>: <?= __d('field', 'day of month (two digit)'); ?></li>
    <li><code>oo</code>: <?= __d('field', 'day of the year (three digit)'); ?></li>
    <li><code>D</code>: <?= __d('field', 'day name short'); ?></li>
    <li><code>DD</code>: <?= __d('field', 'day name long'); ?></li>
    <li><code>m</code>: <?= __d('field', 'month of year (no leading zero)'); ?></li>
    <li><code>mm</code>: <?= __d('field', 'month of year (two digit)'); ?></li>
    <li><code>M</code>: <?= __d('field', 'month name short'); ?></li>
    <li><code>MM</code>: <?= __d('field', 'month name long'); ?></li>
    <li><code>y</code>: <?= __d('field', 'year (two digit)'); ?></li>
    <li><code>yy</code>: <?= __d('field', 'year (four digit)'); ?></li>
    <li><code>'..'</code>: <?= __d('field', 'literal text'); ?></li>
    <li><code>''</code>: <?= __d('field', 'single quote'); ?></li>
    <li><code>@</code>: <?= __d('field', 'Unix timestamp (ms since 01/01/1970)'); ?></li>

    <?php if ($instance->settings['timepicker']): ?>
        <li><code>H</code>: <?= __d('field', 'Hour with no leading 0 (24 hour)'); ?></li>
        <li><code>HH</code>: <?= __d('field', 'Hour with leading 0 (24 hour)'); ?></li>
        <li><code>h</code>: <?= __d('field', 'Hour with no leading 0 (12 hour)'); ?></li>
        <li><code>hh</code>: <?= __d('field', 'Hour with leading 0 (12 hour)'); ?></li>
        <li><code>m</code>: <?= __d('field', 'Minute with no leading 0'); ?></li>
        <li><code>mm</code>: <?= __d('field', 'Minute with leading 0'); ?></li>
        <li><code>ss</code>: <?= __d('field', 'Second with leading 0'); ?></li>
        <li><code>tt</code>: <?= __d('field', 'am or pm for AM/PM'); ?></li>
        <li><code>TT</code>: <?= __d('field', 'AM or PM for AM/PM'); ?></li>
    <?php endif; ?>
</ul>
