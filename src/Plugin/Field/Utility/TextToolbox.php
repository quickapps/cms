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
use QuickApps\Utility\Hooktag;

/**
 * Toolbox utility.
 *
 */
class TextToolbox {

/**
 * Instance of markdown parser class.
 *
 * @var \Field\Lib\Parsedown
 */
	protected static $_MarkdownParser;

/**
 * Process the given text to its corresponding format.
 *
 * @param string $content
 * @param string $processor "plain", "filtered", "markdown" or "full"
 * @return string
 */
	public static function process($content, $processor) {
		switch ($processor) {
			case 'plain':
				return static::plainProcessor($content);
			break;

			case 'filtered':
				return static::filteredProcessor($content);
			break;

			case 'markdown':
				return static::markdownProcessor($content);
			break;

			case 'full':
				return static::fullProcessor($content);
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
 * @param string $text
 * @return string
 */
	public static function plainProcessor($text) {
		$text = static::_emailToLink($text);
		$text = static::_urlToLink($text);
		$text = nl2br($text);
		return $text;
	}

/**
 * Process text in full HTML mode.
 *
 * - Web page addresses and e-mail addresses turn into links automatically.
 *
 * @param string $text
 * @return string
 */
	public static function fullProcessor($text) {
		$text = static::_emailToLink($text);
		$text = static::_urlToLink($text);
		return $text;
	}

/**
 * Process text in filtered HTML mode.
 *
 * - Web page addresses and e-mail addresses turn into links automatically.
 * - Allowed HTML tags: `<a> <em> <strong> <cite> <blockquote> <code> <ul> <ol> <li> <dl> <dt> <dd>`
 * - Lines and paragraphs break automatically.
 *
 * @param string $text
 * @return string
 */
	public static function filteredProcessor($text) {
		$text = static::_emailToLink($text);
		$text = static::_urlToLink($text);
		$text = strip_tags($text, '<a><em><strong><cite><blockquote><code><ul><ol><li><dl><dt><dd>');
		return $text;
	}

/**
 * Process text in markdown mode.
 *
 * - [Markdown](http://en.wikipedia.org/wiki/Markdown) text format allowed only.
 *
 * @param string $text
 * @return string
 */
	public static function markdownProcessor($text) {
		$MarkdownParser = static::_getMarkdownParser();
		$text = $MarkdownParser->parse($text);
		$text = static::_emailToLink($text);
		$text = str_replace('<p>h', '<p> h', $text);
		$text = static::_urlToLink($text);
		return $text;
	}

/**
 * Attempts to close any unclosed HTML tag.
 *
 * @param string $html
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
 * @param string $html
 * @return string
 */
	protected static function _stripHtmlTags($html) {
		$html = preg_replace(
			array(
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
			),
			array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',"$0", "$0", "$0", "$0", "$0", "$0","$0", "$0"),
			$html
		);

		return strip_tags($html, '<script>');
	}

/**
 * Convert any URL to a "<a>" HTML tag.
 *
 * It will ignores URLs in existing `<a>` tags.
 *
 * @param string $text
 * @return string
 */
	protected static function _urlToLink($text) {
		$pattern = array(
			'/[^\\\](?<!http:\/\/|https:\/\/|\"|=|\'|\'>|\">)(www\..*?)(\s|\Z|\.\Z|\.\s|\<|\>|,)/i',
			'/[^\\\](?<!\"|=|\'|\'>|\">|site:)(https?:\/\/(www){0,1}.*?)(\s|\Z|\.\Z|\.\s|\<|\>|,)/i',
			'/[\\\\](?<!\"|=|\'|\'>|\">|site:)(https?:\/\/(www){0,1}.*?)(\s|\Z|\.\Z|\.\s|\<|\>|,)/i',
		);

		$replacement = array(
			"<a href=\"http://$1\">$1</a>$2",
			"<a href=\"$1\" target=\"_blank\">$1</a>$3",
			"$1$3"
		);

		return preg_replace($pattern, $replacement, $text);
	}

/**
 * Convert any email to a "mailto" link.
 *
 * Escape character is "\".
 * For example, "\demo@email.com" won't be converted to link.
 *
 * @param string $text
 * @return string
 */
	protected static function _emailToLink($text) {
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
 * @param string $text
 * @return string
 */
	protected static function _filterText($text) {
		return Hooktag::stripHooktags(static::_stripHtmlTags($text));
	}

/**
 * Safely trim a text.
 *
 * This method is HTML aware, it will not "destroy" any HTML tag.
 * You can trim the text to a given number of characters, or you can give a string
 * as second argument which will be used to cut the given text and return the first part.
 *
 * Example:
 *
 *     $text = '
 *     Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 *     Fusce augue nulla, iaculis adipiscing risus sed, pharetra tempor risus.
 *     <!-- readmore -->
 *     Ut volutpat nisl enim, quic sit amet quam ut lacus condimentum volutpat in eu magna.
 *     Phasellus a dolor cursus, aliquam felis sit amet, feugiat orci. Donec vel consec.';
 *
 *
 * Using `_trimmer($text, '<!-- readmore -->');` will returns:
 *
 *     Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 *     Fusce augue nulla, iaculis adipiscing risus sed, pharetra tempor risus.
 *
 * @param string $text
 * @param string|integer|false $len Either a string indicating where to cut the text,
 *    or a integer to trim text to that number of characters. If not given (false by default)
 *    text will be trimmed to 600 characters length.
 * @return string
 */
	protected static function _trimmer($text, $len = false) {
		if (!preg_match('/[0-9]+/i', $len)) {
			$read_more = explode($len, $text);
			return static::closeOpenTags($read_more[0]);
		}

		$len = !$len || !is_numeric($len) || $len === 0 ? 600 : $len;
		$text = static::_filterText($text);
		$textLen = strlen($text);

		if ($textLen > $len) {
			return substr($text, 0, $len) . ' ...';
		}

		return $text;
	}

/**
 * Gets a markdown parser instance.
 *
 * @return \Field\Lib\Parsedown
 */
	protected static function _getMarkdownParser() {
		if (empty(static::$_MarkdownParser)) {
			static::$_MarkdownParser = new Parsedown();
		}

		return static::$_MarkdownParser;
	}

}
