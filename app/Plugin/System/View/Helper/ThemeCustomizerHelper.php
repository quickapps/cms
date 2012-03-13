<?php
class ThemeCustomizerHelper extends AppHelper {
    public function beforeRender() {
        $this->_View->viewVars['Layout']['stylesheets']['all'][] = '/system/js/colorpicker/colorpicker.css';
        $this->_View->viewVars['Layout']['javascripts']['file'][] = '/system/js/colorpicker/colorpicker.js';
        $this->_View->viewVars['Layout']['javascripts']['file'][] = '/system/js/fontpanel/fontpanel.js';
        $this->_View->viewVars['Layout']['stylesheets']['all'][] = '/system/js/fontpanel/fontpanel.css';
    }

    public function generate($theme_name) {
        $out = '';
        $theme_name = Inflector::camelize($theme_name);
        $themePath = App::themePath($theme_name);
        $cssPath = $themePath . 'webroot' . DS . 'css' . DS;
        $tags = 'color|size|font|miscellaneous';

        if (file_exists($cssPath)) {
            $Folder = new Folder($cssPath);
            $cssFiles = $Folder->find('(.*)\.css');

            if ($cssFiles) {
                $color = $font = 0;

                foreach ($cssFiles as $css) {
                    $__out = '';
                    $__noTitleCounters = array(
                        'color' => 0,
                        'font' => 0,
                        'size' => 0,
                        'Unknow' => 0
                    );

                    $cssContent = file_get_contents($cssPath . $css);

                    if (preg_match_all('/\/\*([\s\t\n\r]*?)\[(' . $tags . ')\b(.*?)(?:(\/))?\]([\s\t\n\r]*?)\*\/(?:(.+?)\/\*([\s\t\n\r]*?)\[\/\2\]([\s\t\n\r]*?)\*\/)?/s', $cssContent, $matches)) {
                        foreach ($matches[0] as $i => $match) {
                            $field = base64_encode($css) . ".{$i}";
                            $attrs = $this->__parseAtts($matches[3][$i]);
                            $value = '';

                            if (!isset($attrs['title'])) {
                                if (isset($__noTitleCounters[$matches[2][$i]])){
                                    $__noTitleCounters[$matches[2][$i]]++;
                                }

                                switch ($matches[2][$i]) {
                                    case 'color':
                                        $attrs['title'] = __t('Color %d', $__noTitleCounters['color']);
                                    break;

                                    case 'font':
                                        $attrs['title'] = __t('Font %d', $__noTitleCounters['font']);
                                    break;

                                    case 'size':
                                        $attrs['title'] = __t('Size %d', $__noTitleCounters['size']);
                                    break;

                                    case 'miscellaneous':
                                        $attrs['title'] = __t('Add Your CSS');
                                    break;

                                    default:
                                        $__noTitleCounters['undefined']++;
                                        $attrs['title'] = __t('Unknow property %d', $__noTitleCounters['undefined']);
                                    break;
                                }
                            }

                            if ($cache = Cache::read("theme_{$theme_name}_{$css}", '__theme_css__')) {
                                if (isset($cache['map'][$match])) {
                                    $value = $cache['map'][$match];
                                }
                            } elseif (isset($matches[6][$i]) && !empty($matches[6][$i])) {
                                $value = $matches[6][$i];
                            }

                            switch ($matches[2][$i]) {
                                case 'color':
                                    $color++;
                                    $id = md5("ThemeCustomizer.{$theme_name}.{$field}");
                                    $__out .=
                                        $this->_View->Form->label(__d($theme_name, $attrs['title']))
                                        . '<div class="colorSelector">'
                                        . $this->_View->Form->input(
                                            "ThemeCustomizer.{$theme_name}.{$field}", 
                                            array(
                                                'value' => $value,
                                                'class' => $id,
                                                'style' => 'width:50px;',
                                                'type' => 'text',
                                                'label' => __d($theme_name, $attrs['title'])
                                            )
                                        )
                                        . '<div class="preview" id="' . $id . '"></div>'
                                        . '</div>';
                                break;

                                case 'font':
                                    $font++;
                                    $id = md5("ThemeCustomizer.{$theme_name}.{$field}");
                                    $__out .= $this->_View->Form->input(
                                        "ThemeCustomizer.{$theme_name}.{$field}", 
                                        array(
                                            'id' => $id,
                                            'value' => $value,
                                            'class' => 'fontselector',
                                            'style' => 'width:200px;',
                                            'type' => 'text',
                                            'label' => __d($theme_name, $attrs['title'])
                                        )
                                    );
                                break;

                                case 'miscellaneous':
                                    $__out .= $this->_View->Form->input(
                                        "ThemeCustomizer.{$theme_name}.{$field}", 
                                        array(
                                            'value' => $value,
                                            'style' => 'width:100%;',
                                            'type' => 'textarea',
                                            'label' => __d($theme_name, $attrs['title'])
                                        )
                                    );
                                break;

                                case 'size':
                                    case 'default':
                                        $h = false;

                                        if ($matches[2][$i] != 'size') {
                                            $h = $this->hook('customize_' . $matches[2][$i], 
                                                $__data = array(
                                                    'theme_name' => $theme_name,
                                                    'css' => $css,
                                                    'tag' => $matches[2][$i],
                                                    'value' => $value,
                                                    'attrs' => $attrs
                                                ), array('collectReturn' => false));
                                        }

                                        if (!$h) {
                                            $__out .= $this->_View->Form->input(
                                                "ThemeCustomizer.{$theme_name}.{$field}", 
                                                array(
                                                    'value' => $value,
                                                    'style' => 'width:50px;',
                                                    'type' => 'text',
                                                    'label' => __d($theme_name, $attrs['title'])
                                                )
                                            );
                                        } else {
                                            $__out .= $h;
                                        }
                                break;
                            }
                        }
                    }

                    if (!empty($__out)) {
                        $__out .= $this->_View->Form->hidden("ThemeCustomizer.{$theme_name}.__reset", array('value' => 0));

                        $out .=
                            $this->_View->Html->useTag('fieldsetstart', '<span class="toggle-style-customizer" id="' . md5($css) . '" style="cursor:pointer;"><em>' . $css . '</em></span>')
                            . "<div class=\"styles-container-" . md5($css) . "\" style=\"display:none;\" id=\"{$css}\">"
                            . $this->_View->Form->submit(__t('Reset'), array('style' => 'float:right; display:block;', 'onclick' => 'return reset_styles("' . $theme_name . '", "' . $css . '");'))
                            . $__out
                            . "</div>"
                            . $this->_View->Html->useTag('fieldsetend');
                    }
                }
            }

            $scripts = '
                <script>
                    $("span.toggle-style-customizer").click(function () {
                        $("div.styles-container-" + $(this).attr("id")).toggle("fast", "linear");
                    });

                    function reset_styles(theme_name, css) {
                        var c = confirm("' . __t('Reset selected style sheet ?') . '");

                        if (c) {
                            $("#ThemeCustomizer" + theme_name + "Reset").val(css);

                            return true;
                        }

                        return false;
                    }
                </script>';

            if ($color) {
                $scripts .= "
                    <script>
                        $(document).ready(function() {
                            $('.colorSelector .preview').each(function () {
                                var id = $(this).attr('id');
                                var color = $('input.' + id).val();

                                $(this).css('backgroundColor', color);
                                $(this).ColorPicker({
                                    color: color,
                                    onChange: function (hsb, hex, rgb) {
                                        $('.' + id).val('#' + hex);
                                        $('#' + id).css('backgroundColor', '#' + hex);
                                    }
                               });
                            });
                        });
                    </script>"; 
            }

            if ($font) {
                $scripts .= '
                    <script>
                        $(document).ready(function() {
                            $(function() {
                                $("input.fontselector").FontPanel();
                            });
                        });
                    </script>'; 
            }
        }

        $out = "<div style=\"width:48%; float:left; margin-right:15px;\">{$out}</div>{$scripts}";

        return $out;
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