<?php
class FieldTextHookHelper extends AppHelper {
    function field_text_view($data) {
        return $this->_View->element('view', array('data' => $data), array('plugin' => 'FieldText'));
    }

    function field_text_edit($data) {
        return $this->_View->element('edit', array('data' => $data), array('plugin' => 'FieldText'));
    }

    function field_text_formatter($data) {
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
        #put all opened tags into an array
        preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);

        $openedtags = $result[1];

        #put all closed tags into an array
        preg_match_all("#</([a-z]+)>#iU", $html, $result);

        $closedtags = $result[1];
        $len_opened = count($openedtags);

        # all tags are closed
        if(count($closedtags) == $len_opened){
            return $html;
        }

        $openedtags = array_reverse($openedtags);
        # close tags
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
        return $this->_View->Layout->removeHookTags(strip_tags($text));
    }

    // Convert url to <a> HTML tag, also ignore URLs in existing <a> tags        
    public static function __url2Link($text) {
        $pattern = '@(?<![.*">])\b(?:(?:https?|ftp|file)://|[a-z]\.)[-A-Z0-9+&#/%=~_|$?!:,.]*[A-Z0-9+&#/%=~_|$]@i';
        $replacement = '<a href="\0" target="_blank">\0</a>';

        return preg_replace($pattern, $replacement, $text);
    }

    private function __email2Link($text) {
        $regex = "([a-z0-9_\-\.]+)@([a-z0-9-]{1,64})\.([a-z]{2,10})";
        return preg_replace("/{$regex}/i", '<a href="mailto:\\1@\\2.\\3">\\1@\\2.\\3</a>', $text);
    }

    private function __trimmer($text, $len = false) {
        if (!preg_match('/[0-9]+/i', $len)) { # not numeric, readmore line (<!-- readmore -->)
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
}