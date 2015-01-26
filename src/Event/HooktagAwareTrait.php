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
namespace QuickApps\Event;

use QuickApps\Event\HooktagManager;

/**
 * Adds hooktags parsing functionality to any object.
 *
 * A Hooktag is a QuickApps-specific code that lets you do nifty things with
 * very little effort. Hooktags can for example print current language code or
 * call specifics plugins/themes functions.
 *
 * @see QuickApps\Event\HooktagManager
 */
trait HooktagAwareTrait
{

    /**
     * Look for hooktags in the given text.
     *
     * If any is found a hook invocation is fired asking for its Hooktag Lister method.
     * For example:
     *
     *     [nice_button color=green]Click Me![/nice_button]
     *
     * You must define a Hooktag Lister `Hooktag.nice_button`:
     *
     *     class YourListener implements EventListenerInterface {
     *         public function implementedEvents() {
     *             return ['Hooktag.nice_button' => 'hooktagNiceButton'];
     *         }
     *
     *         public function hooktagNiceButton(Event $event, $atts, $content, $tag) {
     *             // return some text
     *         }
     *     }
     *
     * (Note the `Hooktag.` prefix).
     *
     * As you can see hooktags methods will receive three arguments:
     *
     * ### $atts
     *
     * Array which may include any arbitrary attributes that are specified by the user.
     * Attribute names are always converted to lowercase before they are passed into
     * the handler function. Values remains untouched.
     *
     *     [some_hooktag Foo="bAr"]
     *
     * Produces:
     *
     *     $atts = ['foo' => 'bAr'];
     *
     * **TIP:** Don't use camelCase or UPPER-CASE for your $atts attribute names
     *
     * ### $content
     *
     *  Holds the enclosed content (if the hooktag is used in its enclosing form).
     *  For self-closing hooktags $content will be null:
     *
     *  [self_close some=thing /]
     *
     *
     * ### $tag
     *
     * The hooktag name. i.e.: `some_hooktag`
     *
     * @param string $content The the text to parse
     * @param object|null $context Context to use when triggering events
     * @return string Orginal string modified with no hooktags [..]
     */
    public function hooktags($content, $context = null)
    {
        return HooktagManager::hooktags($content, $context);
    }

    /**
     * Removes all hooktags from the given content.
     *
     * @param string $content Text from which to remove hooktags
     * @return string Content without hooktags
     */
    public function stripHooktags($content)
    {
        return HooktagManager::stripHooktags($content);
    }
}
