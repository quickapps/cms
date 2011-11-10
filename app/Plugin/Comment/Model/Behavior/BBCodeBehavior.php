<?php
/**
 * BBCode Behavior
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Comment.Model.Behavior
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
App::import('Comment.Lib', 'Nbbc');
class BBCodeBehavior extends ModelBehavior {

    public $settings = array(
        'fields' => array('body')
    );

/**
 * Initiate Serialized behavior
 *
 * @param object $Model instance of model
 * @param array $config array of configuration settings.
 * @return void
 * @access public
 */
    public function setup($Model, $config = array()) {
        if (isset($config['fields']) && is_string($config['fields'])) {
            $config['fields'] = array($config['fields']);
        }

        if (is_string($config)) {
            $config['fields'] = array($config);
        }

        $this->settings = Set::merge($this->settings, $config);
        $this->settings['fields'] = array_unique($this->settings['fields']);
    }

    public function afterFind(&$Model, $results, $primary) {
        $_results = $results;
        if (isset($_results[0][$Model->alias])) {
            foreach ($_results as $rkey => &$record) {
                foreach ($this->settings['fields'] as $field) {
                    if (isset($record[$Model->alias][$field]) && !empty($record[$Model->alias][$field]) && is_string($record[$Model->alias][$field])) {
                        $record[$Model->alias]["raw_{$field}"] = $record[$Model->alias][$field];
                        $record[$Model->alias][$field] = $this->bb_parse($record[$Model->alias][$field]);
                    }
                }
            }
        } else {
            foreach ($this->settings['fields'] as $field) {
                if (isset($_results[$Model->alias][$field]) && !empty($_results[$Model->alias][$field]) && is_string($_results[$Model->alias][$field])) {
                    $_results[$Model->alias]["raw_{$field}"] = $_results[$Model->alias][$field];
                    $_results[$Model->alias][$field] = $this->bb_parse($_results[$Model->alias][$field]);
                }
            }
        }

        return $_results;
    }

    public function bb_parse($string) {
        $bbcode = new BBCode;
        $bbcode->SetDetectURLs(true);
        $bbcode->SetTagMarker('[');

        $bbcode->AddRule('quote',
            array(
                'mode' => BBCODE_MODE_ENHANCED,
                'template' => '<blockquote><cite>{$username} ' . __d('comment', 'wrote') . ':</cite>{$_content}</blockquote>',
                'allow_in' => Array('listitem', 'block', 'columns')
            )
        );

        $bbcode->AddRule('video',
            array(
                'mode' => BBCODE_MODE_CALLBACK,
                'method' => 'videoTag',
                //'class' => 'BBCodeBehavior',
                'allow_in' => Array('listitem', 'block', 'columns')
            )
        );

        $string = $bbcode->Parse($string);

        return $string;
    }
}

function videoTag($bbcode, $action, $name, $default, $params, $content) {
    if ($action == BBCODE_CHECK) {
        return true;
    }

    if ($action == BBCODE_OUTPUT) {
        preg_match('/<a href\=\"(.*)\">(.*)<\/a>/', $content, $matches);

        $videourl = parse_url($matches[1]);

        parse_str($videourl['query'], $videoquery);

        if (strpos($videourl['host'], 'youtube.com') !== false) {
            $replacement = '<embed src="http://www.youtube.com/v/' . $videoquery['v'] . '" type="application/x-shockwave-flash" width="425" height="344"></embed>';
        }

        if (strpos($videourl['host'], 'google.com') !== false) {
            $replacement = '<embed src="http://video.google.com/googleplayer.swf?docid=' . $videoquery['docid'] . '" width="400" height="326" type="application/x-shockwave-flash"></embed>';
        }
        return $replacement;
    }
}