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
use Field\Utility\DateToolbox;

$format = DateToolbox::getPHPFormat($field);
?>

<?php if ($field->viewModeSettings['label_visibility'] == 'above'): ?>
    <h3 class="field-label"><?php echo $field->label; ?></h3>
    <p>
        <?php echo __d('field', 'Start date: {0}', date($format, $field->raw['from']['timestamp'])); ?>
        <br />
        <?php echo __d('field', 'Finish date: {0}', date($format, $field->raw['to']['timestamp'])); ?>
    </p>
<?php elseif ($field->viewModeSettings['label_visibility'] == 'inline'): ?>
    <p>
        <strong class="field-label"><?php echo $field->label; ?>:</strong>
        <?php echo __d('field', 'Start date: {0}', date($format, $field->raw['from']['timestamp'])); ?>
        <br />
        <?php echo __d('field', 'Finish date: {0}', date($format, $field->raw['to']['timestamp'])); ?>
    </p>
<?php else: ?>
    <p>
        <?php echo __d('field', 'Start date: {0}', date($format, $field->raw['from']['timestamp'])); ?>
        <br />
        <?php echo __d('field', 'Finish date: {0}', date($format, $field->raw['to']['timestamp'])); ?>
    </p>
<?php endif; ?>
