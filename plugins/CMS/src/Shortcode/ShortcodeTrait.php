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

use CMS\Shortcode\ShortcodeParser;

/**
 * Adds shortcode parsing functionality to any class.
 *
 * A Shortcode is a QuickApps-specific code that lets you do nifty things with
 * very little effort. Shortcodes can for example print current language code or
 * call specifics plugins/themes functions.
 *
 * @see CMS\Event\ShortcodeParser
 */
trait ShortcodeTrait
{

    /**
     * Look for shortcodes in the given text.
     *
     * If any is found an event is triggered asking for its Event Lister method.
     * For example:
     *
     *     {nice_button color=green}Click Me!{/nice_button}
     *
     * You must define an Event Lister `nice_button`:
     *
     * ```php
     * class YourListener implements EventListenerInterface {
     *     public function implementedEvents() {
     *         return ['nice_button' => 'shortcodeNiceButton'];
     *     }
     *
     *     public function shortcodeNiceButton(Event $event, $atts, $content, $tag) {
     *         // return some text
     *     }
     * }
     * ```
     *
     * As you can see shortcodes methods will receive three arguments:
     *
     * ### $atts
     *
     * Array which may include any arbitrary attributes that are specified by the
     * user. Attribute names are always converted to lowercase before they are
     * passed into the handler function. Values remains untouched.
     *
     *     {some_shortcode Foo="bAr" /}
     *
     * Produces:
     *
     * ```php
     * $atts = ['foo' => 'bAr'];
     * ```
     *
     * **TIP:** Don't use camelCase or UPPER-CASE for your $atts attribute names
     *
     * ### $content
     *
     *  Holds the enclosed content (if the shortcode is used in its enclosing form).
     *  For self-closing shortcodes $content will be null:
     *
     *  {self_close some=thing /}
     *
     *
     * ### $tag
     *
     * The shortcode name. i.e.: `some_shortcode`
     *
     * @param string $content The the text to parse
     * @param object|null $context Context to use when triggering events
     * @return string Orginal string modified with no shortcodes [..]
     */
    public function shortcodes($content, $context = null)
    {
        return ShortcodeParser::parse($content, $context);
    }

    /**
     * Removes all shortcodes from the given content.
     *
     * @param string $content Text from which to remove shortcodes
     * @return string Content without shortcodes
     */
    public function stripShortcodes($content)
    {
        return ShortcodeParser::strip($content);
    }

    /**
     * Escapes all shortcodes from the given content.
     *
     * @param string $content Text from which to escape shortcodes
     * @return string Content with all shortcodes escaped
     */
    public function escapeShortcodes($content)
    {
        return ShortcodeParser::escape($content);
    }

    /**
     * Enables shortcode parser.
     *
     * @return void
     */
    public function enableShortcodes()
    {
        return ShortcodeParser::enable();
    }

    /**
     * Globally disables shortcode parser.
     *
     * The `shortcodes()` method will not work when disabled.
     *
     * @return void
     */
    public function disableShortcodes()
    {
        return ShortcodeParser::disable();
    }
}
