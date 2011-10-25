<?php
/**
 * Hook Component
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class HookComponent extends Component {
    public $Controller;
    public $listeners = array();
    public $events = array();
    public $eventMap = array();
    public $Options = array(
        'break' => false,
        'breakOn' => false,
        'collectReturn' => false
    );
    private $__Options = array(
        'break' => false,
        'breakOn' => false,
        'collectReturn' => false
    );

    public function startup() { }
    public function beforeRender() { }
    public function shutdown() { }
    public function beforeRedirect() {}

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
    public function initialize(&$Controller) {
        $this->Controller =& $Controller;
        $eventMap = array();

        foreach (Configure::read('Hook.components') as $component) {
            $component = strpos($component, '.') !== false ? substr($component, strpos($component, '.')+1) : $component;

            if (strpos($component, 'Hook')) {
                $methods = array();
                $_methods = get_this_class_methods($this->Controller->{$component});

                foreach ($_methods as $method) {
                    $methods[] = $method;
                    $eventMap[$method] = (string)$component;
                }

                $this->listeners[$component] = $methods;
                $this->events = array_merge($this->events, $methods);
                $this->eventMap = array_merge($this->eventMap, $eventMap);
            }
        }

        $this->events = array_unique($this->events);

        return true;
    }

/**
 * Parse string for special placeholders
 * placeholder example: [hook_function param1=text param=2 param3=0 ... /]
 *                      [other_hook_function]only content & no params[/other_hook_function]
 *
 * @return string HTML
 */
    public function hookTags($text) {
        $text = $this->specialTags($text);
        $tags = implode('|', $this->events);

        return preg_replace_callback('/(.?)\[(' . $tags . ')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', array($this, '__doHookTag'), $text);
    }

/**
 * Replace some core useful tags:
 *  `[date=FORMAT]` Return current date(FORMAT).
 *  `[language.OPTION]` Current language option (code, name, native, direction).
 *  `[language]` Shortcut to [language.code] which return current language code.
 *  `[url]YourURL[/url]` or `[url=YourURL]` Formatted url.
 *  `[url=LINK]LABEL[/url]` Returns link tag <href="LINK">LABEL</a>
 *  `[t=stringToTranslate]` or `[t]stringToTranslate[/t]` text translation: __t(stringToTranslate)
 *  `[t=domain@@stringToTranslate]` Translation by domain __d(domain, stringToTranslate)
 *
 * @param string $text original text where to replace tags
 * @return string
 */
    public function specialTags($text) {
        // [locale]
        $text = str_replace('[language]', Configure::read('Variable.language.code'), $text);

        //[locale.OPTION]
        preg_match_all('/\[language.(.+)\]/iUs', $text, $localeMatches);
        foreach ($localeMatches[1] as $attr) {
            $text = str_replace("[language.{$attr}]", Configure::read('Variable.language.' .$attr), $text);
        }

        //[url]URL[/url]
        preg_match_all('/\[url\](.+)\[\/url\]/iUs', $text, $urlMatches);
        foreach ($urlMatches[1] as $url) {
            $text = str_replace("[url]{$url}[/url]", $this->_View->Html->url($url, true), $text);
        }

        //[url=URL]
        preg_match_all('/\[url\=(.+)\]/iUs', $text, $urlMatches);
        foreach ($urlMatches[1] as $url) {
            $text = str_replace("[url={$url}]", $this->_View->Html->url($url, true), $text );
        }

        //[t=text to translate]
        preg_match_all('/\[t\=(.+)\]/iUs', $text, $tMatches);
        foreach ($tMatches[1] as $string) {
            $text = str_replace("[t={$string}]", __t($string), $text);
        }

        //[t]text to translate[/t]
        preg_match_all('/\[t\](.+)\[\/t\]/iUs', $text, $tMatches);
        foreach ($tMatches[1] as $string) {
            $text = str_replace("[t]{$string}[/t]", __t($string), $text);
        }

        //[t=domain@@text to translate]
        preg_match_all('/\[t\=(.+)\@\@(.+)\]/iUs', $text, $dMatches);
        foreach ($dMatches[1] as $key => $domain) {
            $text = str_replace("[d={$domain}@@{$dMatches[2][$key]}]", __d($domain, $dMatches[2][$key]), $text );
        }

        //[date=FORMAT@@TIME_STAMP]
        preg_match_all('/\[date\=(.+)\@\@(.+)\]/iUs', $text, $dateMatches);
        foreach ($dateMatches[1] as $key => $format) {
            $stamp = $dateMatches[2][$key];
            $replace = is_numeric($stamp) ? date($format, $stamp) : date($format, strtotime($stamp));
            $text = str_replace("[date={$format}@@{$stamp}]", $replace, $text);
        }

        //[date=FORMAT]
        preg_match_all('/\[date\=(.+)\]/iUs', $text, $dateMatches);
        foreach ($dateMatches[1] as $format) {
            $text = str_replace("[date={$format}]", date($format), $text);
        }

        # pass text to modules so they can apply their own special tags
        $this->hook('specialTags_alter', $text);

        return $text;
    }

/**
 * Chech if hook exists
 *
 * @param string $hook Name of the hook to check
 * @return boolean
 */
    public function hook_defined($hook) {
        return (in_array($hook, $this->events) == true);
    }

/**
 * Trigger a callback method on every HookComponent.
 *
 * ### Options
 *
 * - `breakOn` Set to the value or values you want the callback propagation to stop on.
 *    Can either be a scalar value, or an array of values to break on.
 *    Defaults to `false`.
 *
 * - `break` Set to true to enabled breaking. When a trigger is broken, the last returned value
 *    will be returned.  If used in combination with `collectReturn` the collected results will be returned.
 *    Defaults to `false`.
 *
 * - `collectReturn` Set to true to collect the return of each object into an array.
 *    This array of return values will be returned from the hook() call. Defaults to `false`.
 *
 * - `alter` Allows each callback gets called on to modify the parameters to the next object.
 *    Defaults to true.
 *
 * @param string $event name of the hook to call
 * @param mixed $data data for the triggered callback
 * @param array $option Array of options
 * @return mixed Either the last result or all results if collectReturn is on. Or null in case of no response
 */
    public function hook($event, &$data = array(), $options = array()) {
        $event = Inflector::underscore($event);
        return $this->__dispatchEvent($event, $data, $options);
    }

/**
 * Overwrite default options for Hook dispatcher.
 * Useful when calling a hook with non-parameter and custom options.
 *
 * Watch out!: Hook dispatcher automatic reset its default options to
 * the original ones after `hook()` is invoked.
 * Means that if you need to call more than one hook (consecutive) with no parameters and
 * same options ** you must call `setHookOptions()` after each hook() **
 *
 * ### Usage
 * For example in any controller action:
 * {{{
 *  $this->setHookOptions(array('collectReturn' => false));
 *  $response = $this->hook('collect_hook_with_no_parameters');
 *
 *  $this->setHookOptions(array('collectReturn' => false, 'break' => true, 'breakOn' => false));
 *  $response2 = $this->hook('OTHER_collect_hook_with_no_parameters');
 * }}}
 *
 * @param array $options Array of options to overwrite
 * @return void
 */
    public function setHookOptions($options) {
        $this->Options = Set::merge($this->Options, $options);
    }

/**
 * Parse hook tags attributes
 *
 * @param string $text Tag string to parse
 * @return Array array of attributes
 */
    private function __hookTagParseAtts($text) {
        $atts       = array();
        $pattern    = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text       = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

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

/**
 * Callback function
 *
 * @see hookTags()
 * @return mixed Hook response or false in case of no response.
 */
    private function __doHookTag($m) {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->__hookTagParseAtts( $m[3] );
        $hook = isset($this->eventMap[$tag]) ? $this->eventMap[$tag] : false;

        if ($hook) {
            $hook =& $this->Controller->{$hook};

            if (isset( $m[5] )) {
                // enclosing tag - extra parameter
                return $m[1] . call_user_func(array($hook, $tag), $attr, $m[5], $tag) . $m[6];
            } else {
                // self-closing tag
                return $m[1] . call_user_func(array($hook, $tag), $attr, null, $tag) . $m[6];
            }
        }

        return false;
    }

/**
 * Dispatch Component-hooks from all the plugins and core
 *
 * @see HookComponent::hook()
 * @return mixed Either the last result or all results if collectReturn is on. Or NULL in case of no response
 */
    private function __dispatchEvent($event, &$data = array(), $options = array()) {
        $options = array_merge($this->Options, (array)$options);
        $collected = array();

        if (!$this->hook_defined($event)) {
            $this->__resetOptions();

            return null;
        }

        foreach ($this->listeners as $component => $methods) {
            foreach ($methods as $method) {
                if ($method == $event && is_callable(array($this->Controller->{$component}, $method))) {
                    $result = call_user_func(array($this->Controller->{$component}, $event), $data);

                    if ($options['collectReturn'] === true) {
                        $collected[] = $result;
                    }

                    if ($options['break'] &&
                        ($result === $options['breakOn'] || (is_array($options['breakOn']) && in_array($result, $options['breakOn'], true)))
                    ) {
                        $this->__resetOptions();

                        return $result;
                    }
                }
            }
        }

        if (empty($collected) && in_array($result, array('', null), true)) {
            $this->__resetOptions();

            return null;
        }

        $this->__resetOptions();

        return $options['collectReturn'] ? $collected : $result;
    }

    private function __resetOptions() {
        if ($this->Options !== $this->__Options) {
            $this->Options = $this->__Options;
        }
    }
}