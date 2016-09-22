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
namespace Field\Utility;

use Cake\Datasource\EntityInterface;

/**
 * DateToolbox class for handling date related tasks.
 *
 */
class DateToolbox
{

    /**
     * Maps jQuery's datepicker format tokens to PHP's
     *
     * @var array
     */
    protected static $_map = [
        'date' => [
            'dd' => 'd',
            'd' => 'j',
            'oo' => 'z',
            'DD' => 'l',
            'D' => 'D',
            'mm' => 'm',
            'm' => 'n',
            'MM' => 'F',
            'M' => 'M',
            'yy' => 'Y',
            'y' => 'y',
            '@' => 'U',
        ],
        'time' => [
            'HH' => 'G',
            'H' => 'H',
            'hh' => 'h',
            'h' => 'g',
            'mm' => 'i',
            'ss' => 's',
            'tt' => 'a',
            'TT' => 'A',
        ]
    ];

    /**
     * Converts the given $date to a valid PHP's DateTime object using a jQuery's
     * date/time $format.
     *
     * @param string $format A jQuery's date/time format. e.g. `'today is:' yy-mm-dd`
     * @param string $date A date formatted using $format. e.g. `today is: 2015-01-30`
     * @return \DateTime|false Date object on success, false on error
     */
    public static function createFromFormat($format, $date)
    {
        if (preg_match_all("/'([^']+)'/", $format, $matches)) {
            foreach ($matches[1] as $literal) {
                $date = str_replace($literal, '', $date);
            }
            $date = preg_replace('/\s{2,}/', ' ', $date); // remove double spaces
        }

        $date = trim($date);
        $format = DateToolbox::normalizeFormat($format);

        return date_create_from_format($format, $date);
    }

    /**
     * Formats then given UNIX timestamp using the given jQuery format.
     *
     * @param string $format jQuery format. e.g. `'today is:' yy-mm-dd`
     * @param int $timestamp Date as UNIX timestamp
     * @return string Formated date. e.g. `today is: 2018-09-09`
     */
    public static function formatDate($format, $timestamp)
    {
        static $datesPatterns = null;
        static $timesPatterns = null;

        if ($datesPatterns === null || $timesPatterns === null) {
            $datesPatterns = "/\b(" . implode('|', array_keys(static::$_map['date'])) . ")\b(?![^']*'(?:(?:[^']*'){2})*[^']*$)/i";
            $timesPatterns = "/\b(" . implode('|', array_keys(static::$_map['time'])) . ")\b(?![^']*'(?:(?:[^']*'){2})*[^']*$)/i";
        }

        // normalize formats
        $result = preg_replace_callback($datesPatterns, function ($matches) use ($timestamp) {
            return date(static::$_map['date'][$matches[1]], $timestamp);
        }, trim($format));

        $result = preg_replace_callback($timesPatterns, function ($matches) use ($timestamp) {
            return date(static::$_map['time'][$matches[1]], $timestamp);
        }, $result);

        return str_replace('\'', '', $result);
    }

    /**
     * Converts jQuery's date/time format to PHP's.
     *
     * @param string $format Date format coming from jQuery's datepicker widget.
     *  e.g. yy-mm-dd hh:mm
     * @return string A valid date/time format to use with PHP
     */
    public static function normalizeFormat($format)
    {
        static $datesPatterns = null;
        static $timesPatterns = null;

        if ($datesPatterns === null || $timesPatterns === null) {
            $datesPatterns = '/(' . implode('|', array_keys(static::$_map['date'])) . ')/';
            $timesPatterns = '/(' . implode('|', array_keys(static::$_map['time'])) . ')/';
        }

        $format = trim($format);
        $format = preg_replace("/'([^']+)'/", '', $format); // remove quotes
        $format = preg_replace('/\s{2,}/', ' ', $format); // remove double spaces
        $format = trim($format);
        list($dateFormat, $timeFormat) = explode(' ', "{$format} ");

        // normalize formats
        $dateFormat = preg_replace_callback($datesPatterns, function ($matches) {
            return static::$_map['date'][$matches[1]];
        }, $dateFormat);

        $timeFormat = preg_replace_callback($timesPatterns, function ($matches) {
            return static::$_map['time'][$matches[1]];
        }, $timeFormat);

        $format = trim($dateFormat . ' ' . $timeFormat);

        return $format;
    }

    /**
     * Validates a date format for jQuery's datepicker widget.
     *
     * @param string $format Format to validate. e.g. yy:mm:ddQ (invalid)
     * @return bool
     */
    public static function validateDateFormat($format)
    {
        $format = str_replace(array_keys(static::$_map['date']), '', $format); // remove placeholders
        $format = preg_replace("/'(.*)'/", '', $format); // remove literals
        $format = preg_replace('/[^a-z]/i', '', $format);
        $format = trim($format);

        return empty($format);
    }

    /**
     * Validates a time format for jQuery's datepicker widget.
     *
     * @param string $format Format to validate. e.g. hh:mm:ssA (invalid)
     * @return bool
     */
    public static function validateTimeFormat($format)
    {
        $format = str_replace(array_keys(static::$_map['time']), '', $format); // remove placeholders
        $format = preg_replace("/'(.*)'/", '', $format); // remove literals
        $format = preg_replace('/[^a-z]/i', '', $format);
        $format = trim($format);

        return empty($format);
    }

    /**
     * Given a DateField instance, gets its PHP's date-format.
     *
     * @param \Cake\Datasource\EntityInterface $field DateField instance
     * @return string PHP date-format for later use with date() function
     */
    public static function getPHPFormat(EntityInterface $field)
    {
        $settings = $field->metadata->settings;
        $format = empty($settings['format']) ? 'yy-mm-dd' : $settings['format'];
        if ($settings['timepicker']) {
            $format .= ' ';
            if (empty($settings['time_format'])) {
                $format .= 'H:mm';
                $format .= empty($settings['time_seconds']) ?: ':ss';
            } else {
                $format .= $settings['time_format'];
            }
        }

        return static::normalizeFormat($format);
    }
}
