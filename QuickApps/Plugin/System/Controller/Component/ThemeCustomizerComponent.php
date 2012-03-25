<?php
class ThemeCustomizerComponent extends Component {
    public $Controller;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
    public function initialize(Controller $Controller) {
        $this->Controller = $Controller;
    }

    public function savePost() {
        if (!isset($this->Controller->data['ThemeCustomizer'])) {
            return false;
        }

        foreach ($this->Controller->data['ThemeCustomizer'] as $theme_name => $files) {
            $theme_name = Inflector::camelize($theme_name);
            $themePath = App::themePath($theme_name);
            $cssPath = $themePath . 'webroot' . DS . 'css' . DS;
            $tags = 'color|size|font|miscellaneous';
            $map = array();

            if ($css = $this->Controller->data['ThemeCustomizer'][$theme_name]['__reset']) {
                Cache::delete("theme_{$theme_name}_{$css}", '__theme_css__');

                return true;
            }

            if (file_exists($cssPath)) {
                foreach ($files as $css => $values) {
                    $css = base64_decode($css);

                    if (file_exists($cssPath . $css)) {
                        $cssContent = file_get_contents($cssPath . $css);

                        if (preg_match_all('/\/\*([\s\t\n\r]*?)\[(' . $tags . ')\b(.*?)(?:(\/))?\]([\s\t\n\r]*?)\*\/(?:(.+?)\/\*([\s\t\n\r]*?)\[\/\2\]([\s\t\n\r]*?)\*\/)?/s', $cssContent, $matches)) {
                            foreach ($matches[0] as $i => $match) {
                                if (isset($values[$i]) && isset($matches[6][$i])) {
                                    $map[$match] = $values[$i];
                                    $attrs = $this->__parseAtts($matches[3][$i]);
                                    $new = str_replace($matches[6][$i], $values[$i], $match);
                                    $cssContent = str_replace($match, $new, $cssContent);

                                    if (isset($attrs['id'])) {
                                        $cssContent = preg_replace('/\/\*([\s\t\n\r]*?)\[(' . $attrs['id'] . ')\]([\s\t\n\r]*?)\*\/(?:(.+?)\/\*([\s\t\n\r]*?)\[\/\2\]([\s\t\n\r]*?)\*\/)?/s', $values[$i], $cssContent);
                                    }
                                }
                            }
                        }

                        $cssCache = array(
                            'content' => $cssContent,
                            'map' => $map
                        );

                        Cache::write("theme_{$theme_name}_{$css}", $cssCache, '__theme_css__');
                    }
                }
            }
        }
    }

    private function __parseAtts($text) {
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