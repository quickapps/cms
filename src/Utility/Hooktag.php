<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Utility;

use Cake\Event\Event;
use Cake\Event\EventManager;
use QuickApps\Utility\HookTrait;

/**
 * Provides methods for hooktag parsing.
 *
 * Hooktags are WordPress's shorcodes equivalent for QuickAppsCMS.
 */
class Hooktag {

	use HookTrait;

/**
 * Temporally holds the context for hooktags().
 *
 * As we can not pass arguments to `preg_replace_callback()`
 * we have to hold this argument until hooktag cycle is done.
 *
 * @var object
 */
	protected static $_context = null;

/**
 * List of available hooktags.
 *
 * @var array
 */
	protected static $_hooktags = [];

/**
 * Look for hooktags in the given text.
 *
 * @param string $content The content to parse
 * @param object $context The context for \Cake\Event\Event::$subject
 * @return string Original string modified with no hooktags [..]
 */
	public static function hooktags($content, $context) {
		if (strpos($content, '[') === false) {
			return $content;
		}

		static::$_context = $context;
		$pattern = static::_hooktagRegex();
		return preg_replace_callback("/{$pattern}/s", 'static::_doHooktag', $content);
	}

/**
 * Removes all hooktags from the given content.
 * Useful for plain text converting.
 *
 * @param string $text Text from which to remove hooktags
 * @return string Content without hooktags
 */
	public function stripHooktags($text) {
		$tagregexp = implode('|', static::_hooktags());

		return preg_replace('/(.?)\[(' . $tagregexp . ')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', '$1$6', $text);
	}

/**
 * Retrieve the hooktag regular expression for searching.
 *
 * The regular expression combines the hooktag tags in the regular expression
 * in a regex class.
 *
 * The regular expression contains 6 different sub matches to help with parsing.
 *
 * 1 - An extra [ to allow for escaping hooktag with double [[]]
 * 2 - The hooktag name
 * 3 - The hooktag argument list
 * 4 - The self closing /
 * 5 - The content of a hooktag when it wraps some content.
 * 6 - An extra ] to allow for escaping hooktag with double [[]]
 *
 * @author WordPress
 * @return string The hooktag search regular expression
 */
	protected static function _hooktagRegex() {
		$tagregexp = implode('|', static::_hooktags());

		return
			'\\['                                // Opening bracket
			. '(\\[?)'                           // 1: Optional second opening bracket for escaping hooktags: [[tag]]
			. "({$tagregexp})"                   // 2: Hooktag name
			. '(?![\\w-])'                       // Not followed by word character or hyphen
			. '('                                // 3: Unroll the loop: Inside the opening hooktag tag
			.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
			.     '(?:'
			.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
			.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
			.     ')*?'
			. ')'
			. '(?:'
			.     '(\\/)'                        // 4: Self closing tag ...
			.     '\\]'                          // ... and closing bracket
			. '|'
			.     '\\]'                          // Closing bracket
			.     '(?:'
			.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing hooktag tags
			.             '[^\\[]*+'             // Not an opening bracket
			.             '(?:'
			.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing hooktag tag
			.                 '[^\\[]*+'         // Not an opening bracket
			.             ')*+'
			.         ')'
			.         '\\[\\/\\2\\]'             // Closing hooktag tag
			.     ')?'
			. ')'
			. '(\\]?)';                          // 6: Optional second closing brocket for escaping hooktags: [[tag]]
	}

/**
 * Returns a list of all registered hooktags in the system.
 *
 * @return array
 */
	protected static function _hooktags() {
		if (empty(static::$_hooktags)) {
			$class = new \ReflectionClass(EventManager::instance());
			$property = $class->getProperty('_listeners');
			$property->setAccessible(true);
			$listeners = array_keys($property->getValue(EventManager::instance()));

			foreach ($listeners as $listener) {
				if (strpos($listener, 'Hooktag.') === 0) {
					static::$_hooktags[] = str_replace('Hooktag.', '', $listener);
				}
			}
		}

		return static::$_hooktags;
	}

/**
 * Invokes hooktag lister for the given hooktag.
 *
 * @param array $m Hooktag as preg array
 * @return string
 */
	protected static function _doHooktag($m) {
		$EventManager = EventManager::instance();

		// allow [[foo]] syntax for escaping a tag
		if ($m[1] == '[' && $m[6] == ']') {
			return substr($m[0], 1, -1);
		}

		$tag = $m[2];
		$atts = static::_parseHooktagAttributes($m[3]);
		$hook = !empty($EventManager->listeners("Hooktag.{$tag}"));

		if ($hook) {
			$options = [
				'atts' => $atts,
				'content' => null,
				'tag' => $tag
			];

			if (isset($m[5])) {
				$options['content'] = $m[5];
			}

			$event = new Event("Hooktag.{$tag}", static::$_context, $options);
			$EventManager->dispatch($event);

			return $m[1] . $event->result . $m[6];
		}

		return '';
	}

/**
 * Looks for attributes.
 *
 * @author WordPress
 * @param string $text
 * @return array Associative array of `tag_name` => `value`
 */
	protected static function _parseHooktagAttributes($text) {
		$atts = array();
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

		if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
			foreach ($match as $m) {
				if (!empty($m[1])) {
					$atts[strtolower($m[1])] = stripcslashes($m[2]);
				} elseif (!empty($m[3])) {
					$atts[strtolower($m[3])] = stripcslashes($m[4]);
				} elseif (!empty($m[5])) {
					$atts[strtolower($m[5])] = stripcslashes($m[6]);
				} elseif (isset($m[7]) and strlen($m[7])) {
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
