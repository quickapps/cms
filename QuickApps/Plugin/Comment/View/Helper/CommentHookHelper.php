<?php
/**
 * Comment View Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Comment.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class CommentHookHelper extends AppHelper {
/**
 * Toolbar menu for section: `Content/Comments`.
 *
 * @return void
 */
	public function beforeLayout($layoutFile) {
		$params = Router::getParams();

		if (isset($params['admin']) &&
			$params['plugin'] == 'comment' &&
			$params['controller'] = 'list' &&
			$params['action'] == 'admin_show'
		) {
			$this->_View->Block->push(array('body' => $this->_View->element('toolbar') . '<!-- CommentHookHelper -->'), 'toolbar');
		}

		if (!isset($params['admin']) &&
			$params['plugin'] == 'node' &&
			in_array($params['controller'], array('node')) &&
			$params['action'] == 'details'
		) {
			if ($this->_View->Node->getAttr('comment') == 2) {
				$this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markItUp/locale.js';
				$this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markItUp/jquery.markitup.js';
				$this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markItUp/sets/bbcode/set.js';
				$this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/jquery.scrollTo-min.js';
				$this->_View->viewVars['Layout']['javascripts']['inline'][] = "
					$(document).ready(function()	{
						$('#CommentBody').markItUp(MerkeItUpBbcodeSettings);
					});";

				$this->_View->viewVars['Layout']['stylesheets']['all'][] = '/comment/js/markItUp/sets/bbcode/style.css';
				$this->_View->viewVars['Layout']['stylesheets']['all'][] = '/comment/js/markItUp/skins/simple/style.css';
			}
		}

		return true;
	}

/**
 * Renders ReCaptcha for comments form.
 *
 * @return string HTML
 */
	public function comment_captcha() {
		$out = '';

		if (Configure::read('Modules.Comment.settings.use_recaptcha') &&
			Configure::read('Modules.Comment.settings.recaptcha.private_key') &&
			Configure::read('Modules.Comment.settings.recaptcha.public_key')
		) {
			if (!defined('RECAPTCHA_API_SERVER')) {
				App::import('Lib', 'Comment.Recaptcha');
			}

			$settings = Hash::merge(
				array(
					'custom_translations' => array(
						'instructions_visual' => '',
						'instructions_audio' => '',
						'play_again' => '',
						'cant_hear_this' => '',
						'visual_challenge' => '',
						'audio_challenge' => '',
						'refresh_btn' => '',
						'help_btn' => '',
						'incorrect_try_again' => ''
					),
					'lang' => 'en',
					'theme' => 'red'
				), Configure::read('Modules.Comment.settings.recaptcha')
			);
			$out .= '<div class="input text required ' . (CakeSession::read('invalid_recaptcha') ? 'error' : '') . '">';
			$out .= '<script type="text/javascript">';
			$out .= 'var RecaptchaOptions = {';

			switch ($settings['lang']) {
				case 'auto':
					$L10n = new L10n;
					$langs = $L10n->map();
					$language_code = 'en';

					if (isset($langs[Configure::read('Variable.language.code')]) &&
						in_array($langs[Configure::read('Variable.language.code')], array('en', 'nl', 'fr', 'de', 'pt', 'ru', 'es', 'tr'))
					) {
						$language_code = $langs[Configure::read('Variable.language.code')];
					}

					$out .= "lang: '{$language_code}',";
				break;

				case 'custom':
					$out .= 'custom_translations: {';
					$strings = array();

					foreach ($settings['custom_translations'] as $key => $str) {
						if (empty($str)) {
							continue;
						}

						$strings[] = "{$key}: '" . str_replace("'", "\'", $str) . "'";
					}

					$out .= implode(",\n", $strings);
					$out .= '},';
				break;

				default:
					$out .= "lang: '{$settings['lang']}',";
				break;
			}

			$out .= "theme: '{$settings['theme']}'";
			$out .= '}';
			$out .= '</script>';
			$out .= recaptcha_get_html(Configure::read('Modules.Comment.settings.recaptcha.public_key'));

			if (CakeSession::read('invalid_recaptcha')) {
				$out .= '<div class="error-message">' . __t('Invalid security code.') . '</div>';

				CakeSession::write('invalid_recaptcha' ,false);
			}

			$out .= '</div>';
		}

		return $out;
	}

/**
 * Adds management shortcuts to each comment.
 *
 * @return string HTML
 */
	public function html_nested_list_alter(&$data) {
		if (isset($data['options']['class']) &&
			$data['options']['class'] == 'comment-actions-list' &&
			isset($data['options']['id']) &&
			preg_match('/^comment-actions-[0-9]*$/', $data['options']['id']) &&
			isset($data['list'])
		) {
			$id = explode('comment-actions-', $data['options']['id']);
			$id = $id[1];

			if ($this->is('user.authorized', 'Block.Manage.admin_view')) {
				$data['list'][] = $this->_View->Html->link(__t('Details'), '/admin/comment/list/view/' . $id);
			}

			if ($this->is('user.authorized', 'Block.Manage.admin_delete')) {
				$data['list'][] = $this->_View->Html->link(__t('Delete'), '/admin/comment/list/delete/' . $id, array('onclick' => 'return confirm("' . __t('Delete selected comment ?') . '");'));
			}
		}
	}
}