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
use Cake\Core\Configure;
?>

<span id="dp-container-<?php echo $field->name; ?>">
    <?php
        echo $this->Form->input($field, [
            'name' => ":{$field->name}[date]",
            'value' => $field->value,
            'class' => 'picker',
            'readonly',
        ]);

        // value dynamically set using JS
        echo $this->Form->input(":{$field->name}.format", [
            'type' => 'hidden',
            'class' => 'format',
        ]);
    ?>

    <?php if (!$field->required): ?>
        <em class="help-block"><?php echo $this->Html->link(__d('field', 'Empty date'), '', ['onclick' => "javascript: $('#dp-container-{$field->name} input').val(''); return false;"]); ?></em>
    <?php endif; ?>
</span>

<script>
    <?php
        $this->jQuery->theme(null, ['block' => true]);
        $this->jQuery->ui(['block' => true]);

        $settings = $field->metadata->settings;
        $options = ["showAnim: 'drop'"];
        $pickerWidget = $settings['timepicker'] ? 'datetimepicker' : 'datepicker';

        if (!empty($settings['locale'])) {
            $this->Html->script("/jquery/js/ui/i18n/datepicker-{$settings['locale']}.js", ['block' => true]);

            if ($settings['timepicker']) {
                $this->Html->script("/field/js/timepicker/i18n/jquery-ui-timepicker-{$settings['locale']}.js", ['block' => true]);
            }
        } else {
            $options[] = empty($settings['format']) ? "dateFormat: 'yy-mm-dd'" : 'dateFormat: "' . $settings['format'] . '"';

            if ($settings['timepicker']) {
                if (empty($settings['time_format'])) {
                    $format = 'H:mm';
                    $format .= empty($settings['time_seconds']) ?: ':ss';
                    $options[] = "timeFormat: '{$format}'";
                } else {
                    $options[] = "timeFormat: '{$settings['time_format']}'";
                }
            }
        }

        if ($settings['timepicker']) {
            if (Configure::read('debug')) {
                $this->Html->script('/field/js/timepicker/jquery-ui-timepicker-addon.js', ['block' => true]);
                $this->Html->css('/field/css/timepicker/jquery-ui-timepicker-addon.css', ['block' => true]);
            } else {
                $this->Html->script('/field/js/timepicker/jquery-ui-timepicker-addon.min.js', ['block' => true]);
                $this->Html->css('/field/css/timepicker/jquery-ui-timepicker-addon.min.css', ['block' => true]);
            }

            $options[] = "timeOnlyTitle: '" . __d('field', 'Choose Time') . "'";
            $options[] = "timeText: '" . __d('field', 'Time') . "'";
            $options[] = "hourText: '" . __d('field', 'Hour') . "'";
            $options[] = "minuteText: '" . __d('field', 'Minute') . "'";
            $options[] = "secondText: '" . __d('field', 'Second') . "'";
            $options[] = "millisecText: '" . __d('field', 'Milliseconds') . "'";
            $options[] = empty($settings['time_seconds']) ?: "showSecond: true";
            $options[] = empty($settings['time_ampm']) ?: "ampm: true";
        }

        $options[] = empty($settings['button_bar']) ?: "showButtonPanel: true";
        $options[] = empty($settings['month_year_menu']) ?: "changeMonth: true";
        $options[] = empty($settings['month_year_menu']) ?: "changeYear: true";
        $options[] = empty($settings['show_weeks']) ?: "showWeek: true";
        $options[] = empty($settings['show_weeks']) ?: "firstDay: 1";
        $options[] = empty($settings['multiple_months']) ?: "numberOfMonths: {$settings['multiple_months']}";

        $options = array_filter($options, function ($opt) {
            return is_string($opt);
        });
    ?>

    $(document).ready(function() {
        $('#dp-container-<?php echo $field->name; ?> .picker').<?php echo $pickerWidget; ?>({
            <?php echo implode(",\n", $options); ?>
        });

        <?php if (!empty($settings['locale'])): ?>
            $('#dp-container-<?php echo $field->name; ?> .picker').<?php echo $pickerWidget; ?>(
                'option',
                $.datepicker.regional['<?php echo $settings['locale']; ?>']
            );

            <?php if ($settings['timepicker']): ?>
                $('#dp-container-<?php echo $field->name; ?> .picker').<?php echo $pickerWidget; ?>(
                    'option',
                    $.timepicker.regional['<?php echo $settings['locale']; ?>']
                );
            <?php endif; ?>
        <?php endif; ?>

        var dateFormat = $('#dp-container-<?php echo $field->name; ?> .picker').<?php echo $pickerWidget; ?>('option', 'dateFormat');
        $('#dp-container-<?php echo $field->name; ?> .format').val(dateFormat);

        <?php if ($settings['timepicker']): ?>
            var timeFormat = $('#dp-container-<?php echo $field->name; ?> .picker').<?php echo $pickerWidget; ?>('option', 'timeFormat'); 
            var currentVal = $('#dp-container-<?php echo $field->name; ?> .format').val();
            $('#dp-container-<?php echo $field->name; ?> .format').val(currentVal + ' ' + timeFormat);
        <?php endif; ?>
    });
</script>