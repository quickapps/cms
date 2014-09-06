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
namespace Field\Utility;

use Field\Lib\Parsedown;
use Field\Model\Entity\Field;
use QuickApps\Event\HooktagAwareTrait;

/**
 * Text utility class.
 *
 * Utility methods used by TextField Handler.
 */
class TextToolbox {

	use HooktagAwareTrait;

/**
 * Holds an instance of this class.
 *
 * @var \Field\Utility\TextToolbox
 */
	protected static $_instance = null;

/**
 * Instance of markdown parser class.
 *
 * @var \Field\Lib\Parsedown
 */
	protected static $_MarkdownParser;

/**
 * Returns an instance of this class.
 *
 * Useful when we need to use some of the trait methods.
 * 
 * @return \Field\Utility\TextToolbox
 */
	public static function getInstance() {
		if (!static::$_instance) {
			static::$_instance = new TextToolBox();
		}

		return static::$_instance;
	}

/**
 * Formats the given field.
 *
 * @param \Field\Model\Entity\Field $field The field being rendered
 * @return string
 */
	public static function formatter(Field $field) {
		$viewModeSettings = $field->view_mode_settings;
		$content = $viewModeSettings['hooktags'] ? static::getInstance()->hooktags($field->value) : static::getInstance()->stripHooktags($field->value);
		$processing = $field->metadata->settings['text_processing'];
		$formatter = $viewModeSettings['formatter'];
		$content = static::process($content, $processing);

		switch ($formatter) {
			case 'plain':
				$content = static::filterText($content);
			break;

			case 'trimmed':
				$len = $viewModeSettings['trim_length'];
				$content = static::trimmer($content, $len);
			break;
		}

		return $content;
	}

/**
 * Process the given text to its corresponding format.
 *
 * @param string $content Content to process
 * @param string $processor "plain", "filtered", "markdown" or "full"
 * @return string
 */
	public static function process($content, $processor) {
		switch ($processor) {
			case 'plain':
				$content = static::plainProcessor($content);
			break;

			case 'filtered':
				$content = static::filteredProcessor($content);
			break;

			case 'markdown':
				$content = static::markdownProcessor($content);
			break;
		}

		return $content;
	}

/**
 * Process text in plain mode.
 *
 * - No HTML tags allowed.
 * - Web page addresses and e-mail addresses turn into links automatically.
 * - Lines and paragraphs break automatically.
 *
 * @param string $text The text to process
 * @return string
 */
	public static function plainProcessor($text) {
		$text = static::emailToLink($text);
		$text = static::urlToLink($text);
		$text = nl2br($text);
		return $text;
	}

/**
 * Process text in full HTML mode.
 *
 * - Web page addresses turn into links automatically.
 * - E-mail addresses turn into links automatically.
 *
 * @param string $text The text to process
 * @return string
 */
	public static function fullProcessor($text) {
		$text = static::emailToLink($text);
		$text = static::urlToLink($text);
		return $text;
	}

/**
 * Process text in filtered HTML mode.
 *
 * - Web page addresses turn into links automatically.
 * - E-mail addresses turn into links automatically.
 * - Allowed HTML tags: `<a> <em> <strong> <cite> <blockquote> <code> <ul> <ol> <li> <dl> <dt> <dd>`
 * - Lines and paragraphs break automatically.
 *
 * @param string $text The text to process
 * @return string
 */
	public static function filteredProcessor($text) {
		$text = static::emailToLink($text);
		$text = static::urlToLink($text);
		$text = strip_tags($text, '<a><em><strong><cite><blockquote><code><ul><ol><li><dl><dt><dd>');
		return $text;
	}

/**
 * Process text in markdown mode.
 *
 * - [Markdown](http://en.wikipedia.org/wiki/Markdown) text format allowed only.
 *
 * @param string $text The text to process
 * @return string
 */
	public static function markdownProcessor($text) {
		$MarkdownParser = static::getMarkdownParser();
		$text = $MarkdownParser->parse($text);
		$text = static::emailToLink($text);
		$text = str_replace('<p>h', '<p> h', $text);
		$text = static::urlToLink($text);
		return $text;
	}

/**
 * Attempts to close any unclosed HTML tag.
 *
 * @param string $html HTML content to fix
 * @return string
 */
	public static function closeOpenTags($html) {
		preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
		$openedtags = $result[1];
		preg_match_all("#</([a-z]+)>#iU", $html, $result);
		$closedtags = $result[1];
		$len_opened = count($openedtags);

		if (count($closedtags) == $len_opened) {
			return $html;
		}

		$openedtags = array_reverse($openedtags);

		for ($i = 0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</' . $openedtags[$i] . '>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}

		return $html;
	}

/**
 * Protects email address so bots can not read it.
 *
 * Replaces emails address with an encoded JS script, so there is no way bots
 * can read an email address from the generated HTML source code.
 *
 * @param string $email The email to obfuscate
 * @return string
 */
	public static function emailObfuscator($email) {
		$link = str_rot13('<a href="mailto:' . $email . '" rel="nofollow">' . $email . '</a>');
		$out = '
			<script type="text/javascript">
				document.write(\'' . $link . '\'.replace(/[a-zA-Z]/g,
				function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);}));
			</script>
		';
		$out .= "<noscript>[" . __d('field', 'Turn on JavaScript to see the email address.') . "]</noscript>";

		return $out;
	}

/**
 * Safely strip HTML tags.
 *
 * @param string $html HTML content
 * @return string
 */
	public static function stripHtmlTags($html) {
		$html = preg_replace([
			'@<head[^>]*?>.*?</head>@siu',
			'@<style[^>]*?>.*?</style>@siu',
			'@<object[^>]*?.*?</object>@siu',
			'@<embed[^>]*?.*?</embed>@siu',
			'@<applet[^>]*?.*?</applet>@siu',
			'@<noframes[^>]*?.*?</noframes>@siu',
			'@<noscript[^>]*?.*?</noscript>@siu',
			'@<noembed[^>]*?.*?</noembed>@siu',
			'@</?((address)|(blockquote)|(center)|(del))@iu',
			'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
			'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
			'@</?((table)|(th)|(td)|(caption))@iu',
			'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
			'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
			'@</?((frameset)|(frame)|(iframe))@iu',
		],
		[' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',"$0", "$0", "$0", "$0", "$0", "$0","$0", "$0"],
		$html);

		return strip_tags($html, '<script>');
	}

/**
 * Convert any URL to a "<a>" HTML tag.
 *
 * It will ignores URLs in existing `<a>` tags.
 *
 * @param string $text The text where to look for links
 * @return string
 */
	public static function urlToLink($text) {
		$pattern = [
			'/[^\\\](?<!http:\/\/|https:\/\/|\"|=|\'|\'>|\">)(www\..*?)(\s|\Z|\.\Z|\.\s|\<|\>|,)/i',
			'/[^\\\](?<!\"|=|\'|\'>|\">|site:)(https?:\/\/(www){0,1}.*?)(\s|\Z|\.\Z|\.\s|\<|\>|,)/i',
			'/[\\\\](?<!\"|=|\'|\'>|\">|site:)(https?:\/\/(www){0,1}.*?)(\s|\Z|\.\Z|\.\s|\<|\>|,)/i',
		];

		$replacement = [
			"<a href=\"http://$1\">$1</a>$2",
			"<a href=\"$1\" target=\"_blank\">$1</a>$3",
			"$1$3"
		];

		return preg_replace($pattern, $replacement, $text);
	}

/**
 * Convert any email to a "mailto" link.
 *
 * Escape character is "\".
 * For example, "\demo@email.com" won't be converted to link.
 *
 * @param string $text The text where to look for emails addresses
 * @return string
 */
	public static function emailToLink($text) {
		preg_match_all("/([\\\a-z0-9_\-\.]+)@([a-z0-9-]{1,64})\.([a-z]{2,10})/i", $text, $emails);

		foreach ($emails[0] as $email) {
			$email = trim($email);

			if ($email[0] == '\\') {
				$text = str_replace($email, substr($email, 1), $text);
			} else {
				$text = str_replace($email, static::emailObfuscator($email), $text);
			}
		}

		return $text;
	}

/**
 * Strips HTML tags and any hooktag.
 *
 * @param string $text The text to process
 * @return string
 */
	public static function filterText($text) {
		return static::getInstance()->stripHooktags(static::stripHtmlTags($text));
	}

/**
 * Safely trim a text.
 *
 * This method is HTML aware, it will not "destroy" any HTML tag. You can trim
 * the text to a given number of characters, or you can give a string as second
 * argument which will be used to cut the given text and return the first part.
 *
 * ## Examples:
 *
 *     $text = '
 *     Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 *     Fusce augue nulla, iaculis adipiscing risus sed, pharetra tempor risus.
 *     <!-- readmore -->
 *     Ut volutpat nisl enim, quic sit amet quam ut lacus condimentum volutpat in eu magna.
 *     Phasellus a dolor cursus, aliquam felis sit amet, feugiat orci. Donec vel consec.';
 *
 *     echo $this->trimmer($text, '<!-- readmore -->');
 *
 *     // outputs:
 *     Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 *     Fusce augue nulla, iaculis adipiscing risus sed, pharetra tempor risus.
 *
 *     echo $this->trimmer('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 10);
 *     // out: "Lorem ipsu ..."
 *
 * @param string $text The text to trim
 * @param string|integer|false $len Either a string indicating where to cut the
 * text, or a integer to trim text to that number of characters. If not given
 * (false by default) text will be trimmed to 600 characters length.
 * @param string $ellipsis Will be used as ending and appended to the trimmed string
 * @return string
 */
	public static function trimmer($text, $len = false, $ellipsis = ' ...') {
		if (!preg_match('/[0-9]+/i', $len)) {
			$parts = explode($len, $text);
			return static::closeOpenTags($parts[0]);
		}

		$len = $len === false || !is_numeric($len) || $len <= 0 ? 600 : $len;
		$text = static::filterText($text);
		$textLen = strlen($text);

		if ($textLen > $len) {
			return substr($text, 0, $len) . $ellipsis;
		}

		return $text;
	}

/**
 * Gets a markdown parser instance.
 *
 * @return \Field\Lib\Parsedown
 */
	public static function getMarkdownParser() {
		if (empty(static::$_MarkdownParser)) {
			static::$_MarkdownParser = new Parsedown();
		}

		return static::$_MarkdownParser;
	}

/**
 * Debug friendly object properties.
 *
 * @return array
 */
	public function __debugInfo() {
		$properties = get_object_vars($this);
		$properties['_instance'] = '(object) TextToolbox';
		return $properties;
	}

}
