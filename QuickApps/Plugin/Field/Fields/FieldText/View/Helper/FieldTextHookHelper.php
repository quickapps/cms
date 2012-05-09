<?php
class FieldTextHookHelper extends AppHelper {
	public function field_text_formatter($data) {
		if (isset($data['settings']['text_processing']) && !empty($data['settings']['text_processing'])) {
			$this->_View->Layout->hook('text_processing_' . $data['settings']['text_processing'], $data['content']);
		}

		switch($data['format']['type']) {
			case 'plain':
				$data['content'] = $this->__filterText($data['content']);
			break;

			case 'trimmed':
				$len = @$data['format']['trim_length'];
				$data['content'] = $this->__trimmer($data['content'], $len);
			break;
		}

		return $data['content'];
	}

	// already saved in plain, nothing to do
	public function text_processing_plain(&$text) {
		$text = $this->__email2Link($text);
		$text = $this->__url2Link($text);
		$text = nl2br($text);
	}

	// already saved in plain, nothing to do
	public function text_processing_filtered(&$text) {
		$text = $this->__email2Link($text);
		$text = $this->__url2Link($text);
		$text = nl2br($text);
	}

	// convert from plain text markdown to html
	public function text_processing_markdown(&$text) {
		if (!isset($this->MarkdownParser) || !is_object($this->MarkdownParser)) {
			App::import('Lib', 'FieldText.Markdown');

			$this->MarkdownParser = new Markdown_Parser;
		}

		$text = $this->MarkdownParser->transform($text);
		$text = $this->__email2Link($text);
		$text = str_replace('<p>h', '<p> h', $text);
		$text = $this->__url2Link($text);
	}

	public function text_processing_full(&$text) {
		$text = $this->__email2Link($text);
		$text = $this->__url2Link($text);
	}

	public function close_open_tags($html) {
		// put all opened tags into an array
		preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);

		$openedtags = $result[1];

		// put all closed tags into an array
		preg_match_all("#</([a-z]+)>#iU", $html, $result);

		$closedtags = $result[1];
		$len_opened = count($openedtags);

		// all tags are closed
		if (count($closedtags) == $len_opened) {
			return $html;
		}

		$openedtags = array_reverse($openedtags);

		// close tags
		for($i=0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</' . $openedtags[$i] . '>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}

		return $html;
	}

	private function __filterText($text) {
		return $this->_View->Layout->stripHooktags($this->__strip_html_tags($text));
	}

	private function __strip_html_tags($text) {
		$text = preg_replace(
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
			$text
		);

		return strip_tags($text, '<script>');
	}

	// Convert url to <a> HTML tag, also ignore URLs in existing <a> tags
	public static function __url2Link($text) {
		$pattern = array(
			'/(?<!http:\/\/|https:\/\/|\"|=|\'|\'>|\">)(www\..*?)(\s|\Z|\.\Z|\.\s|\<|\>|,)/i',
			'/(?<!\"|=|\'|\'>|\">|site:)(https?:\/\/(www){0,1}.*?)(\s|\Z|\.\Z|\.\s|\<|\>|,)/i'
		);

		$replacement = array(
			"<a href=\"http://$1\">$1</a>$2",
			"<a href=\"$1\" target=\"_blank\">$1</a>$3"
		);

		return preg_replace($pattern, $replacement, $text);
	}

	private function __email2Link($text) {
		preg_match_all("/([\\\a-z0-9_\-\.]+)@([a-z0-9-]{1,64})\.([a-z]{2,10})/i", $text, $emails);

		foreach ($emails[0] as $email) {
			$email = trim($email);

			if ($email[0] == '\\') {
				$text = str_replace($email, substr($email, 1), $text);
			} else {
				$text = str_replace($email, $this->obfuscate_email($email), $text);
			}
		}

		return $text;
	}

	private function __trimmer($text, $len = false) {
		if (!preg_match('/[0-9]+/i', $len)) {
			//not numeric, readmore line (<!-- readmore -->)
			$read_more = explode($len, $text);

			return $this->close_open_tags($read_more[0]);
		}

		$len = !$len || !is_numeric($len) || $len === 0 ? 600 : $len;
		$text = $this->__filterText($text);
		$textLen = strlen($text);

		if ($textLen > $len) {
			return substr($text, 0, $len) . ' ...';
		}

		return $text;
	}

	public function obfuscate_email($email) {
		$link = str_rot13('<a href="mailto:' . $email . '" rel="nofollow">' . $email . '</a>');
		$out = '
			<script type="text/javascript">
				document.write(\'' . $link . '\'.replace(/[a-zA-Z]/g,
				function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);}));
			</script>
		';
		$out .= "<noscript>[" . __t('Turn on JavaScript to see the email address.') . "]</noscript>";

		return $out;
	}
}