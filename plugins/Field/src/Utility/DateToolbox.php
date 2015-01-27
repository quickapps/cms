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

/**
 * DateToolbox class for handling date related tasks.
 *
 */
class DateToolbox
{

    /**
     * Maps jQuery's datepicker format tokens to PHP's
     *
     * @var array]
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
     * @return \DateTime
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
        $result = date_create_from_format($format, $date);
        return $result;
    }

    /**
     * Converts jQuery's date/time format to PHP's.
     *
     * @param string $format Date format coming from jQuery's datepicker widget.
     *  e.g. yy-mm-dd hh:mm
     * @return array An array result of using date_parse_from_format()
     */
    public static function normalizeFormat($format)
    {
        $format = preg_replace("/'([^']+)'/", '', $format); // remove quotes
        $format = preg_replace('/\s{2,}/', ' ', $format); // remove double spaces
        $format = trim($format);
        list($dateFormat, $timeFormat) = explode(' ', "{$format} ");

        // normalize formats
        $dateFormat = str_replace(array_keys(static::$_map['date']), array_values(static::$_map['date']), $dateFormat);
        $timeFormat = str_replace(array_keys(static::$_map['time']), array_values(static::$_map['time']), $timeFormat);
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
        $format = str_replace(array_keys(static::$_map['date']), '', $format);
        $format = preg_replace("/'(.*)'/", '', $format); // remove quotes
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
        $format = str_replace(array_keys(static::$_map['time']), '', $format);
        $format = preg_replace('/[^a-z]/i', '', $format);
        $format = trim($format);

        return empty($format);
    }
}
