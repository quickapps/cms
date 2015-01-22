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
 * Main Hook Listener for System plugin.
 *
 */
class SystemHooktag implements EventListenerInterface
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
            'Hooktag.random' => 'hooktagRandom',
            'Hooktag.t' => 'hooktagTranslate',
            'Hooktag.url' => 'hooktagURL',
            'Hooktag.date' => 'hooktagDate',
            'Hooktag.locale' => 'hooktagLocale',
        ];
    }

    /**
     * Implements the "random" hooktag.
     *
     *     [random]1,2,3[/random]
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the hooktag is used in its
     *  enclosing form)
     * @param string $tag The hooktag tag
     * @return string
     */
    public function hooktagRandom(Event $event, array $atts, $content, $tag)
    {
        $elements = explode(',', trim($content));

        if (is_array($elements)) {
            return $elements[array_rand($elements)];
        }

        return '';
    }

    /**
     * Implements the "t" hooktag.
     *
     *     [t]Text for translate[/t]
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the hooktag is used in its
     *  enclosing form)
     * @param string $tag The hooktag tag
     * @return string
     */
    public function hooktagTranslate(Event $event, array $atts, $content, $tag)
    {
        if (!empty($atts['domain'])) {
            return __d($atts['domain'], $content);
        } else {
            return __($content);
        }
    }

    /**
     * Implements the "url" hooktag.
     *
     *     [url]/some/url/on/my/site[/url]
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the hooktag is used in its
     *  enclosing form)
     * @param string $tag The hooktag tag
     * @return string
     */
    public function hooktagURL(Event $event, array $atts, $content, $tag)
    {
        try {
            $url = Router::url($content, true);
        } catch (\Exception $e) {
            $url = '';
        }
        return $url;
    }

    /**
     * Implements the "date" hooktag.
     *
     *     [date format=d-m-Y]2014-05-06[/date]
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the hooktag is used in its
     *  enclosing form)
     * @param string $tag The hooktag tag
     * @return string
     */
    public function hooktagDate(Event $event, array $atts, $content, $tag)
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
     * Implements the "locale" hooktag.
     *
     *     [locale code /]
     *     [locale name /]
     *     [locale direction /]
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the hooktag is used in its
     *  enclosing form)
     * @param string $tag The hooktag tag
     * @return string
     */
    public function hooktagLocale(Event $event, array $atts, $content, $tag)
    {
        $option = array_keys((array)$atts);
        $locale = I18n::defaultLocale();
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
}
