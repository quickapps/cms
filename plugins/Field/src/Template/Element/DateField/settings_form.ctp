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

<fieldset>
    <legend><?php echo __d('field', 'Date Options'); ?></legend>

    <?php
        echo $this->Form->input('format', [
            'type' => 'text',
            'label' => __d('field', 'Date format'),
        ]);
    ?>
    <em class="help-block"><?php echo __d('field', "This option is not used when localized. e.g.: <code>'Meeting on:' yy-mm-dd</code>, see below for details"); ?></em>

    <ul>
        <li><code>d</code>: <?php echo __d('field', 'day of month (no leading zero)'); ?></li>
        <li><code>dd</code>: <?php echo __d('field', 'day of month (two digit)'); ?></li>
        <li><code>oo</code>: <?php echo __d('field', 'day of the year (three digit)'); ?></li>
        <li><code>D</code>: <?php echo __d('field', 'day name short'); ?></li>
        <li><code>DD</code>: <?php echo __d('field', 'day name long'); ?></li>
        <li><code>m</code>: <?php echo __d('field', 'month of year (no leading zero)'); ?></li>
        <li><code>mm</code>: <?php echo __d('field', 'month of year (two digit)'); ?></li>
        <li><code>M</code>: <?php echo __d('field', 'month name short'); ?></li>
        <li><code>MM</code>: <?php echo __d('field', 'month name long'); ?></li>
        <li><code>y</code>: <?php echo __d('field', 'year (two digit)'); ?></li>
        <li><code>yy</code>: <?php echo __d('field', 'year (four digit)'); ?></li>
        <li><code>'..'</code>: <?php echo __d('field', 'literal text'); ?></li>
        <li><code>''</code>: <?php echo __d('field', 'single quote'); ?></li>
        <li><code>@</code>: <?php echo __d('field', 'Unix timestamp (ms since 01/01/1970)'); ?></li>
    </ul>

    <?php
        echo $this->Form->input('button_bar', [
            'type' => 'checkbox',
            'label' => __d('field', 'Display button bar')
        ]);

        echo $this->Form->input('month_year_menu', [
            'type' => 'checkbox',
            'label' => __d('field', 'Display month & year menu')
        ]);

        echo $this->Form->input('show_weeks', [
            'type' => 'checkbox',
            'label' => __d('field', 'Show week of the year')
        ]);

        echo $this->Form->input('multiple_months', [
            'type' => 'select',
            'options' => [
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5
            ],
            'empty' => false,
            'label' => __d('field', 'Display multiple months')
        ]);  

        echo $this->Form->input('locale', [
            'type' => 'select',
            'options' => [
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
            ],
            'empty' => '---',
            'escape' => false,
            'label' => __d('field', 'Localize')
        ]);
    ?>    
</fieldset>

<?php
    echo $this->Form->input('timepicker', [
        'id' => 'timepicker-checkbox',
        'type' => 'checkbox',
        'label' => __d('field', 'Add a Time picker'),
        'onclick' => "$('#TimeOptions').toggle();",
    ]);
?>

<div id="TimeOptions">
    <fieldset>
        <legend><?php echo __d('field', 'Time Options'); ?></legend>

        <?php
            echo $this->Form->input('time_format', [
                'type' => 'text',
                'label' => __d('field', 'Time format'),
            ]);
        ?>
        <em class="help-block"><?php echo __d('field', 'e.g.: <code>H:mm:ss:c</code>, see below'); ?></em>

        <ul>
            <li><code>H</code>: <?php echo __d('field', 'Hour with no leading 0 (24 hour)'); ?></li>
            <li><code>HH</code>: <?php echo __d('field', 'Hour with leading 0 (24 hour)'); ?></li>
            <li><code>h</code>: <?php echo __d('field', 'Hour with no leading 0 (12 hour)'); ?></li>
            <li><code>hh</code>: <?php echo __d('field', 'Hour with leading 0 (12 hour)'); ?></li>
            <li><code>m</code>: <?php echo __d('field', 'Minute with no leading 0'); ?></li>
            <li><code>mm</code>: <?php echo __d('field', 'Minute with leading 0'); ?></li>
            <li><code>ss</code>: <?php echo __d('field', 'Second with leading 0'); ?></li>
            <li><code>tt</code>: <?php echo __d('field', 'am or pm for AM/PM'); ?></li>
            <li><code>TT</code>: <?php echo __d('field', 'AM or PM for AM/PM'); ?></li>
        </ul>

        <?php
            echo $this->Form->input('time_ampm', [
                'type' => 'checkbox',
                'label' => __d('field', 'Use AM/PM')
            ]);

            echo $this->Form->input('time_seconds', [
                'type' => 'checkbox',
                'label' => __d('field', 'Display seconds')
            ]);
        ?>
    </fieldset>
</div>

<script type="text/javascript">
    $(document).ready(function ()  {
        if ($("#timepicker-checkbox").is(':checked')) {
            $('#TimeOptions').show();
        } else {
            $('#TimeOptions').hide();
        }
    });
</script>