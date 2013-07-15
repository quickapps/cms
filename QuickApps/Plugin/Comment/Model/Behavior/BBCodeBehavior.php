<?php
/**
 * BBCode Behavior
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Comment.Model.Behavior
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class BBCodeBehavior extends ModelBehavior {
	public $settings = array();

/**
 * Initiate Serialized behavior
 *
 * @param object $Model instance of model
 * @param array $config array of configuration settings.
 * @return void
 * @access public
 */
	public function setup(Model $Model, $config = array()) {
		$smileyURL = QuickApps::strip_language_prefix(Router::url('/', true)) . 'comment/img/smileys';

		$__settings = array(
			'fields' => array('body'),
			'smileys' => true,
			'smiley_url' => $smileyURL,
			'detect_url' => true
		);

		if (isset($config['fields']) && is_string($config['fields'])) {
			$config['fields'] = array($config['fields']);
		}

		$this->settings[$Model->alias] = Hash::merge($__settings, $config);
		$this->settings[$Model->alias]['fields'] = array_unique($this->settings[$Model->alias]['fields']);
	}

	public function afterFind(Model $Model, $results, $primary) {
		$_results = $results;
		if (isset($_results[0][$Model->alias])) {
			foreach ($_results as $rkey => &$record) {
				foreach ($this->settings[$Model->alias]['fields'] as $field) {
					if (isset($record[$Model->alias][$field]) &&
						!empty($record[$Model->alias][$field]) &&
						is_string($record[$Model->alias][$field])
					) {
						$record[$Model->alias]["raw_{$field}"] = $record[$Model->alias][$field];
						$record[$Model->alias][$field] = $this->bb_parse($Model, $record[$Model->alias][$field]);
					}
				}
			}
		} else {
			foreach ($this->settings[$Model->alias]['fields'] as $field) {
				if (isset($_results[$Model->alias][$field]) &&
					!empty($_results[$Model->alias][$field]) &&
					is_string($_results[$Model->alias][$field])
				) {
					$_results[$Model->alias]["raw_{$field}"] = $_results[$Model->alias][$field];
					$_results[$Model->alias][$field] = $this->bb_parse($Model, $_results[$Model->alias][$field]);
				}
			}
		}

		return $_results;
	}

	public function bb_parse(&$Model, $string) {
		if (!defined('BBCODE_VERSION')) {
			App::import('Comment.Lib', 'Nbbc');
		}

		$bbcode = new BBCode;
		$bbcode->SetDetectURLs($this->settings[$Model->alias]['detect_url']);
		$bbcode->SetTagMarker('[');

		if ($this->settings[$Model->alias]['smileys']) {
			$bbcode->SetEnableSmileys(true);
			$bbcode->SetSmileyURL($this->settings[$Model->alias]['smiley_url']);
		} else {
			$bbcode->SetEnableSmileys(false);
		}

		$bbcode->AddRule('quote',
			array(
				'mode' => BBCODE_MODE_ENHANCED,
				'template' => '<blockquote><cite>{$username} ' . __t('wrote') . ':</cite>{$_content}</blockquote>',
				'allow_in' => Array('listitem', 'block', 'columns')
			)
		);

		$bbcode->AddRule('video',
			array(
				'mode' => BBCODE_MODE_CALLBACK,
				'method' => 'BBCodeBehavior::videoTag',
				'allow_in' => Array('listitem', 'block', 'columns')
			)
		);

		$Model->hook('before_parse_comment_bbcode', $string);

		$string = $bbcode->Parse($string);

		$Model->hook('after_parse_comment_bbcode', $string);

		return $string;
	}

	public function videoTag($bbcode, $action, $name, $default, $params, $content) {
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
}