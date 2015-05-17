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
namespace CMS\Shortcode;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Routing\Router;
use CMS\Core\StaticCacheTrait;
use CMS\Event\EventDispatcher;
use CMS\View\View;

/**
 * Provides methods for shortcode parsing.
 *
 * Shortcodes looks as follow:
 *
 * 1. Self-closing form:
 *
 *     {my_shortcode attr1=val1 attr2=val2 ... /}
 *
 * 2. Enclosed form:
 *
 *     {my_shortcode attr1=val1 attr2=val2 ... } content {/my_shortcode}
 *
 * Shortcodes can be escaped by using an additional `{` symbol, for instance:
 *
 *     {{ something }}
 *     // this will actually prints `{ something }`
 *
 *     {{something} dummy {/something}}
 *     // this will actually prints `{something} dummy {/something}`
 *
 */
class ShortcodeParser
{

    use StaticCacheTrait;

    /**
     * Default context to use.
     *
     * @var object
     */
    protected static $_defaultContext = null;

    /**
     * Holds a list of all registered shortcodes.
     *
     * @var array
     */
    protected static $_listeners = [];

    /**
     * Parser status.
     *
     * The `parse()` method will not work when set to false.
     *
     * @var boolean
     */
    protected static $_enabled = true;

    /**
     * Look for shortcodes in the given $text.
     *
     * @param string $text The content to parse
     * @param object $context The context for \Cake\Event\Event::$subject, if not
     *  given an instance of this class will be used
     * @return string
     */
    public static function parse($text, $context = null)
    {
        if (!static::$_enabled || strpos($text, '{') === false) {
            return $text;
        }

        if ($context === null) {
            $context = static::_getDefaultContext();
        }

        static::cache('context', $context);
        $pattern = static::_regex();
        return preg_replace_callback("/{$pattern}/s", 'static::_doShortcode', $text);
    }

    /**
     * Removes all shortcodes from the given content. Useful when converting a
     * string to plain text.
     *
     * @param string $text Text from which to remove shortcodes
     * @return string Content without shortcodes markers
     */
    public static function strip($text)
    {
        $tagregexp = implode('|', array_map('preg_quote', static::_list()));
        return preg_replace('/(.?){(' . $tagregexp . ')\b(.*?)(?:(\/))?}(?:(.+?){\/\2})?(.?)/s', '$1$6', $text);
    }

    /**
     * Escapes all shortcodes from the given content.
     *
     * @param string $text Text from which to escape shortcodes
     * @return string Content with all shortcodes escaped
     */
    public static function escape($text)
    {
        $tagregexp = implode('|', array_map('preg_quote', static::_list()));
        preg_match_all('/(.?){(' . $tagregexp . ')\b(.*?)(?:(\/))?}(?:(.+?){\/\2})?(.?)/s', $text, $matches);

        foreach ($matches[0] as $ht) {
            $replace = str_replace_once('{', '{{', $ht);
            $replace = str_replace_last('}', '}}', $replace);
            $text = str_replace($ht, $replace, $text);
        }

        return $text;
    }

    /**
     * Enables shortcode parser.
     *
     * @return void
     */
    public static function enable()
    {
        static::$_enabled = true;
    }

    /**
     * Globally disables shortcode parser.
     *
     * The `parser()` method will not work when disabled.
     *
     * @return void
     */
    public static function disable()
    {
        static::$_enabled = false;
    }

    /**
     * Returns a list of all registered shortcodes.
     *
     * @return array
     */
    protected static function _list()
    {
        if (empty(static::$_listeners)) {
            $manager = EventDispatcher::instance('Shortcode')->eventManager();
            static::$_listeners = listeners($manager);
        }
        return static::$_listeners;
    }

    /**
     * Gets default context to use.
     *
     * @return \CMS\View\View
     */
    protected static function _getDefaultContext()
    {
        if (!static::$_defaultContext) {
            static::$_defaultContext = new View(Router::getRequest(), null, EventManager::instance(), []);
        }
        return static::$_defaultContext;
    }

    /**
     * Retrieve the shortcode regular expression for searching.
     *
     * The regular expression combines the shortcode tags in the regular expression
     * in a regex class.
     *
     * The regular expression contains 6 different sub matches to help with parsing.
     *
     * 1 - An extra { to allow for escaping shortcodes: {{ something }}
     * 2 - The shortcode name
     * 3 - The shortcode argument list
     * 4 - The self closing /
     * 5 - The content of a shortcode when it wraps some content.
     * 6 - An extra } to allow for escaping shortcode
     *
     * @author WordPress
     * @return string The shortcode search regular expression
     */
    protected static function _regex()
    {
        $tagregexp = implode('|', array_map('preg_quote', static::_list()));
        // @codingStandardsIgnoreStart
        return
            '\\{'                                // Opening bracket
            . '(\\{?)'                           // 1: Optional second opening bracket for escaping shortcodes: {{tag}}
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\}\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\})'               // A forward slash not followed by a closing bracket
            .         '[^\\}\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\}'                          // ... and closing bracket
            . '|'
            .     '\\}'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\{]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\{(?!\\/\\2\\})' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\{]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\{\\/\\2\\}'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\}?)';                          // 6: Optional second closing brocket for escaping shortcodes: {{tag}}
        // @codingStandardsIgnoreEnd
    }

    /**
     * Invokes shortcode lister method for the given shortcode.
     *
     * @param array $m Shortcode as preg array
     * @return string
     * @author WordPress
     */
    protected static function _doShortcode($m)
    {
        // allow {{foo}} syntax for escaping a tag
        if ($m[1] == '{' && $m[6] == '}') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $atts = static::_parseAttributes($m[3]);
        $listeners = EventDispatcher::instance('Shortcode')
            ->eventManager()
            ->listeners($tag);

        if (!empty($listeners)) {
            $options = [
                'atts' => (array)$atts,
                'content' => null,
                'tag' => $tag
            ];

            if (isset($m[5])) {
                $options['content'] = $m[5];
            }

            $result = EventDispatcher::instance('Shortcode')
                ->triggerArray([$tag, static::cache('context')], $options)
                ->result;
            return $m[1] . $result . $m[6];
        }

        return '';
    }

    /**
     * Looks for shortcode's attributes.
     *
     * Attribute names are always converted to lowercase. Values are untouched.
     *
     * ## Example:
     *
     *     {shortcode_name attr1="value1" aTTr2=value2 CamelAttr=Val1 /}
     *
     * Produces:
     *
     * ```php
     * [
     *     'attr1' => 'value1',
     *     'attr2' => 'value2',
     *     'camelattr' => 'Val1',
     * ]
     * ```
     *
     * @param string $text The text where to look for shortcodes
     * @return array Associative array of attributes as `tag_name` => `value`
     * @author WordPress
     */
    protected static function _parseAttributes($text)
    {
        $atts = [];
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", ' ', $text);

        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                } elseif (isset($m[7]) && strlen($m[7])) {
                    $atts[] = stripcslashes($m[7]);
                } elseif (isset($m[8])) {
                    $atts[] = stripcslashes($m[8]);
                }
            }
        } else {
            $atts = ltrim($text);
        }

        return $atts;
    }
}
