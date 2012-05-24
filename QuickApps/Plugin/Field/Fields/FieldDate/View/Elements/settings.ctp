<?php
	$toggle_time_options = 'display:none;';

	if (isset($this->data['Field']['settings']['timepicker']) && $this->data['Field']['settings']['timepicker']) {
		$toggle_time_options = '';
	}

	$toggle_date_options = 'display:none;';

	if (isset($this->data['Field']['settings']['datepicker']) && $this->data['Field']['settings']['datepicker']) {
		$toggle_date_options = '';
	}

	echo $this->Form->input("Field.settings.timepicker",
		array(
			'type' => 'checkbox',
			'label' => __t('Add a Timepicker'),
			'onclick' => "$('#TimeOptions').toggle();"
		)
	);

	echo $this->Form->input("Field.settings.datepicker",
		array(
			'type' => 'checkbox',
			'label' => __t('Add a Date Picker'),
			'onclick' => "$('#DateOptions').toggle();"
		)
	);
?>
<div id="TimeOptions" style="<?php echo $toggle_time_options; ?>">
	<?php echo $this->Html->useTag('fieldsetstart', __t('Time Options')); ?>

		<?php
			echo $this->Form->input("Field.settings.time_format",
				array(
					'type' => 'text',
					'label' => __t('Time format'),
					'after' => '&nbsp;' . __t('e.g.: hh:mm:ss:l')
				)
			);

			echo $this->Form->input("Field.settings.time_separator",
				array(
					'type' => 'text',
					'label' => __t('Separator')
				)
			);

			echo $this->Form->input("Field.settings.time_ampm",
				array(
					'type' => 'checkbox',
					'label' => __t('Use AM/PM')
				)
			);

			echo $this->Form->input("Field.settings.time_seconds",
				array(
					'type' => 'checkbox',
					'label' => __t('Show seconds')
				)
			);

			echo $this->Form->input("Field.settings.time_milliseconds",
				array(
					'type' => 'checkbox',
					'label' => __t('Show milliseconds')
				)
			);
		?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
</div>

<div id="DateOptions" style="<?php echo $toggle_date_options; ?>">
	<?php echo $this->Html->useTag('fieldsetstart', __t('Date Options')); ?>
		<?php
			echo $this->Form->input("Field.settings.format",
				array(
					'type' => 'text',
					'label' => __t('Date format'),
					'after' => '&nbsp;' . __t('e.g.: yy-mm-dd')
				)
			);

			echo $this->Form->input("Field.settings.button_bar",
				array(
					'type' => 'checkbox',
					'label' => __t('Display button bar')
				)
			);

			echo $this->Form->input("Field.settings.month_year_menu",
				array(
					'type' => 'checkbox',
					'label' => __t('Display month & year menu')
				)
			);

			echo $this->Form->input("Field.settings.show_weeks",
				array(
					'type' => 'checkbox',
					'label' => __t('Show week of the year')
				)
			);

			echo $this->Form->input("Field.settings.multiple_months",
				array(
					'type' => 'select',
					'options' => array(
						1 => 1,
						2 => 2,
						3 => 3,
						4 => 4,
						5 => 5
					),
					'empty' => false,
					'label' => __t('Display multiple months')
				)
			);	
		?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
</div>

<?php
	echo $this->Form->input("Field.settings.locale",
		array(
			'type' => 'select',
			'options' => array(
				'af' => 'Afrikaans',
				'sq' => 'Albanian (Gjuha shqipe)',
				'ar-DZ' => 'Algerian Arabic',
				'ar' => 'Arabic (&#8235;(&#1604;&#1593;&#1585;&#1576;&#1610;',
				'hy' => 'Armenian (&#1344;&#1377;&#1397;&#1381;&#1408;&#1381;&#1398;)',
				'az' => 'Azerbaijani (Az&#601;rbaycan dili)',
				'eu' => 'Basque (Euskara)',
				'bs' => 'Bosnian (Bosanski)',
				'bg' => 'Bulgarian (&#1073;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080; &#1077;&#1079;&#1080;&#1082;)',
				'ca' => 'Catalan (Catal&agrave;)',
				'zh-HK' => 'Chinese Hong Kong (&#32321;&#39636;&#20013;&#25991;)',
				'zh-CN' => 'Chinese Simplified (&#31616;&#20307;&#20013;&#25991;)',
				'zh-TW' => 'Chinese Traditional (&#32321;&#39636;&#20013;&#25991;)',
				'hr' => 'Croatian (Hrvatski jezik)',
				'cs' => 'Czech (&#269;e&#353;tina)',
				'da' => 'Danish (Dansk)',
				'nl-BE' => 'Dutch (Belgian)',
				'nl' => 'Dutch (Nederlands)',
				'en-AU' => 'English/Australia',
				'en-NZ' => 'English/New Zealand',
				'en-GB' => 'English/UK',
				'eo' => 'Esperanto',
				'et' => 'Estonian (eesti keel)',
				'fo' => 'Faroese (f&oslash;royskt)',
				'fa' => 'Farsi/Persian (&#8235;(&#1601;&#1575;&#1585;&#1587;&#1740;',
				'fi' => 'Finnish (suomi)',
				'fr' => 'French (Fran&ccedil;ais)',
				'fr-CH' => 'French/Swiss (Fran&ccedil;ais de Suisse)',
				'gl' => 'Galician',
				'de' => 'German (Deutsch)',
				'de-CH' => 'German (Swiss)',
				'el' => 'Greek (&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;)',
				'he' => 'Hebrew (&#8235;(&#1506;&#1489;&#1512;&#1497;&#1514;',
				'hu' => 'Hungarian (Magyar)',
				'is' => 'Icelandic (&Otilde;slenska)',
				'id' => 'Indonesian (Bahasa Indonesia)',
				'it' => 'Italian (Italiano)',
				'ja' => 'Japanese (&#26085;&#26412;&#35486;)',
				'ko' => 'Korean (&#54620;&#44397;&#50612;)',
				'kz' => 'Kazakhstan (Kazakh)',
				'lv' => 'Latvian (Latvie&ouml;u Valoda)',
				'lt' => 'Lithuanian (lietuviu kalba)',
				'ml' => 'Malayalam',
				'ms' => 'Malaysian (Bahasa Malaysia)',
				'no' => 'Norwegian (Norsk)',
				'pl' => 'Polish (Polski)',
				'pt' => 'Portuguese (Portugu&ecirc;s)',
				'pt-BR' => 'Portuguese/Brazilian (Portugu&ecirc;s)',
				'rm' => 'Rhaeto-Romanic (Romansh)',
				'ro' => 'Romanian (Rom&acirc;n&#259;)',
				'ru' => 'Russian (&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;)',
				'sr' => 'Serbian (&#1089;&#1088;&#1087;&#1089;&#1082;&#1080; &#1112;&#1077;&#1079;&#1080;&#1082;)',
				'sr-SR' => 'Serbian (srpski jezik)',
				'sk' => 'Slovak (Slovencina)',
				'sl' => 'Slovenian (Slovenski Jezik)',
				'es' => 'Spanish (Espa&ntilde;ol)',
				'sv' => 'Swedish (Svenska)',
				'ta' => 'Tamil (&#2980;&#2990;&#3007;&#2996;&#3021;)',
				'th' => 'Thai (&#3616;&#3634;&#3625;&#3634;&#3652;&#3607;&#3618;)',
				'tj' => 'Tajikistan',
				'tr' => 'Turkish (T&uuml;rk&ccedil;e)',
				'uk' => 'Ukranian (&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;)',
				'vi' => 'Vietnamese (Ti&#7871;ng Vi&#7879;t)'
			),
			'empty' => '---',
			'escape' => false,
			'label' => __t('Localize')
		)
	);