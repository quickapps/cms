<?php
class FieldDateHookHelper extends AppHelper {
    private $__fieldDateLocale = array();
    private $__instancesCount = 0;

    public function field_date_js_init($data) {
        extract($data);

        $out = '';

        if (!$this->__instancesCount) {
            $out .= $this->_View->Html->script('/system/js/ui/jquery-ui.js');
            $out .= $this->_View->Html->css('/system/js/ui/css/ui-lightness/styles.css');
        }

        if (isset($settings['locale']) &&
            !empty($settings['locale']) &&
            !isset($this->__fieldDateLocale[$settings['locale']])
        ) {
            $locales = $this->__fieldDateLocale;
            $locales[$settings['locale']] = true;
            $this->__fieldDateLocale = $locales;
            $out .= $this->_View->Html->script("/field_date/js/i18n/jquery.ui.datepicker-{$settings['locale']}.js");
        }

        $out .= "<script>";
        $out .= "$(document).ready(function() {";
        $out .= "$(function() { $('#FieldDataFieldDate{$id}Data').datepicker({";
        $opts = array("showAnim: 'drop'");

        if (isset($settings['format']) && !empty($settings['format'])) {
            $opts[] = "dateFormat: '{$settings['format']}'";
        } else {
            $opts[] = "dateFormat: 'yy-mm-dd'";
        }

        if (isset($settings['button_bar']) && $settings['button_bar']) {
            $opts[] = 'showButtonPanel: true';
        }

        if (isset($settings['month_year_menu']) && $settings['month_year_menu']) {
            $opts[] = 'changeMonth: true';
            $opts[] = 'changeYear: true';
        }

        if (isset($settings['show_weeks']) && $settings['show_weeks']) {
            $opts[] = 'showWeek: true';
            $opts[] = 'firstDay: 1';
        }

        if (isset($settings['multiple_months']) && $settings['multiple_months'] > 1) {
            $opts[] = "numberOfMonths: {$settings['multiple_months']}";
        }

        $out .= implode(",\n", $opts);
        $out .= "}); });";

        if (isset($settings['locale']) && !empty($settings['locale'])) {
            $out .= "$('#FieldDataFieldDate{$id}Data').datepicker('option',
                    $.datepicker.regional['{$settings['locale']}']);";
        }

        $out .= "});";
        $out .= "</script>";
        $this->__instancesCount++;

        return $out;
    }
}