<?php
class FieldDateHookHelper extends AppHelper {
	private $__fieldDateLocale = array();
	private $__instancesCount = 0;

	public function field_date_js_init($data) {
		extract($data);

		$out = '';

		if (!$this->__instancesCount) {
			$this->_View->jQueryUI->add('all');
			$this->_View->jQueryUI->theme();
		}

		if (isset($settings['locale']) &&
			!empty($settings['locale']) &&
			!isset($this->__fieldDateLocale[$settings['locale']])
		) {
			$locales = $this->__fieldDateLocale;
			$locales[$settings['locale']] = true;
			$this->__fieldDateLocale = $locales;
			$this->_View->Layout->script("/field_date/js/i18n/jquery.ui.datepicker-{$settings['locale']}.js");
		}

		$time_picker = isset($settings['timepicker']) && !empty($settings['timepicker']);
		$date_picker = isset($settings['datepicker']) && !empty($settings['datepicker']);

		if ($time_picker && $date_picker) {
			$picker = 'datetimepicker';
		} elseif ($time_picker) {
			$picker = 'timepicker';
		} else {
			$picker = 'datepicker';
		}

		if ($time_picker) {
			$this->_View->Layout->script("/field_date/js/timepicker.js");
			$this->_View->Layout->css("/field_date/css/timepicker.css");
		}

		$out .= "<script>";
		$out .= "$(document).ready(function() {";
		$out .= "$(function() { $('#FieldDataFieldDate{$id}Data').{$picker}({";
		$opts = array("showAnim: 'drop'");

		if ($time_picker) {
			$opts[] = "timeOnlyTitle: '" . __t('Choose Time') . "'";
			$opts[] = "timeText: '" . __t('Time') . "'";
			$opts[] = "hourText: '" . __t('Hour') . "'";
			$opts[] = "minuteText: '" . __t('Minute') . "'";
			$opts[] = "secondText: '" . __t('Second') . "'";
			$opts[] = "millisecText: '" . __t('Milliecond') . "'";

			if (isset($settings['time_format']) && !empty($settings['time_format'])) {
				$opts[] = "timeFormat: '{$settings['time_format']}'";
			}

			if (isset($settings['time_milliseconds']) && $settings['time_milliseconds']) {
				$opts[] = "showMillisec: true";
			}

			if (isset($settings['time_seconds']) && $settings['time_seconds']) {
				$opts[] = "showSecond: true";
			}

			if (isset($settings['time_ampm']) && $settings['time_ampm']) {
				$opts[] = "ampm: true";
			}

			if (isset($settings['time_separator']) && !empty($settings['time_separator'])) {
				$opts[] = "separator: '{$settings['time_separator']}'";
			}
		}

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