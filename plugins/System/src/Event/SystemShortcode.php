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
namespace System\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\I18n\I18n;
use Cake\Routing\Router;

/**
 * Main Shortcode Listener for System plugin.
 *
 */
class SystemShortcode implements EventListenerInterface
{

    /**
     * Returns a list of events this Event Listener is implementing. When the class
     * is registered in an event manager, each individual method will be associated
     * with the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'random' => 'shortcodeRandom',
            't' => 'shortcodeTranslate',
            'url' => 'shortcodeUrl',
            'date' => 'shortcodeDate',
            'locale' => 'shortcodeLocale',
            'no_shortcode' => 'noShortcode',
        ];
    }

    /**
     * Implements the "random" shortcode.
     *
     *     {random}1,2,3{/random}
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the shortcode is used in its
     *  enclosing form)
     * @param string $tag The shortcode tag
     * @return string
     */
    public function shortcodeRandom(Event $event, array $atts, $content, $tag)
    {
        if (strpos($content, ',') === false) {
            return '';
        }

        $elements = explode(',', trim($content));
        $elements = array_map('trim', $elements);
        $c = count($elements);

        if ($c == 2 && is_numeric($elements[0]) && is_numeric($elements[1])) {
            return rand($elements[0], $elements[1]);
        }

        return $elements[array_rand($elements)];
    }

    /**
     * Implements the "t" shortcode.
     *
     *     {t}Text for translate{/t}
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the shortcode is used in its
     *  enclosing form)
     * @param string $tag The shortcode tag
     * @return string
     */
    public function shortcodeTranslate(Event $event, array $atts, $content, $tag)
    {
        if (!empty($atts['domain'])) {
            return __d($atts['domain'], $content);
        } else {
            return __($content);
        }
    }

    /**
     * Implements the "url" shortcode.
     *
     *     {url}/some/url/on/my/site{/url}
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the shortcode is used in its
     *  enclosing form)
     * @param string $tag The shortcode tag
     * @return string
     */
    public function shortcodeUrl(Event $event, array $atts, $content, $tag)
    {
        try {
            $url = Router::url($content, true);
        } catch (\Exception $e) {
            $url = '';
        }
        return $url;
    }

    /**
     * Implements the "date" shortcode.
     *
     *     {date format=d-m-Y}2014-05-06{/date}
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the shortcode is used in its
     *  enclosing form)
     * @param string $tag The shortcode tag
     * @return string
     */
    public function shortcodeDate(Event $event, array $atts, $content, $tag)
    {
        if (!empty($atts['format']) && !empty($content)) {
            if (is_numeric($content)) {
                return date($atts['format'], $content);
            } else {
                return date($atts['format'], strtotime($content));
            }
        }

        return '';
    }

    /**
     * Implements the "locale" shortcode.
     *
     *     {locale code /}
     *     {locale name /}
     *     {locale direction /}
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the shortcode is used in its
     *  enclosing form)
     * @param string $tag The shortcode tag
     * @return string
     */
    public function shortcodeLocale(Event $event, array $atts, $content, $tag)
    {
        $option = array_keys((array)$atts);
        $locale = I18n::locale();
        $languages = quickapps('languages');
        $out = '';

        if (!isset($languages[$locale])) {
            return $out;
        }

        if (empty($option)) {
            $option = 'code';
        } else {
            $option = $option[0];
        }

        if ($info = $languages[$locale]) {
            switch ($option) {
                case 'code':
                    $out = $info['code'];
                    break;

                case 'name':
                    $out = $info['name'];
                    break;

                case 'direction':
                    $out = $info['direction'];
                    break;
            }
        }

        return $out;
    }

    /**
     * Used to remove shortcodes. Any shortcode within this shortcode's content will
     * not be converted.
     *
     * ### Usage:
     *
     *     {no_shortcode}
     *         This shortcode will not work {some_shortcode /}
     *     {/no_shortcode}
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the shortcode is used in its
     *  enclosing form)
     * @param string $tag The shortcode tag
     * @return string
     */
    public function noShortcode(Event $event, array $atts, $content, $tag)
    {
        return $content;
    }
}
