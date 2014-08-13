<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace System\Controller\Admin;

use Cake\Error\NotFoundException;
use Cake\Utility\Inflector;
use System\Controller\AppController;
use QuickApps\Core\Plugin;

/**
 * Controller for handling plugin tasks.
 *
 * Here is where can install new plugin or remove existing ones.
 */
class ThemesController extends AppController {

/**
 * An array containing the names of components controllers uses.
 *
 * @var array
 */
	public $components = ['System.Installer'];

/**
 * Main action.
 *
 * @return void
 */
	public function index() {
		$themes = Plugin::collection(true)->match(['isTheme' => true]);
		$front_themes = $themes
			->match(['composer.extra.admin' => false])
			->sortBy(function ($theme) {
				if ($theme['name'] === option('front_theme')) {
					return 0;
				}
				return 1;
			});
		$back_themes = $themes
			->match(['composer.extra.admin' => true])
			->sortBy(function ($theme) {
				if ($theme['name'] === option('back_theme')) {
					return 0;
				}
				return 1;
			});
		$front_count = count($front_themes->toArray());
		$back_count = count($back_themes->toArray());

		$this->set(compact('front_count', 'back_count', 'front_themes', 'back_themes'));
		$this->Breadcrumb->push('/admin/system/themes');
	}

/**
 * Install a new theme.
 *
 * @return void
 */
	public function install() {
		if ($this->request->data) {
			if (isset($this->request->data['download'])) {
				$success = $this->Installer->download($this->request->data['url'])->install();
			} else {
				$success = $this->Installer->upload($this->request->data['file'])->install();
			}

			if ($success) {
				$this->alert(__d('system', 'Theme successfully installed!'));
				$this->redirect($this->referer());
			} else {
				$messages = $this->Installer->errors();
				$messages = array_map(function ($v) {
					return "<li>{$v}</li>";
				}, $messages);
				$messages = implode("\n", $messages);
				$this->alert(__d('system', 'Theme could not be installed:<br/> {0}', "<ul>{$messages}</ul>"), 'danger');
			}
		}
	}

/**
 * Detailed theme's information.
 *
 * @return void
 */
	public function details($themeName) {
		$theme = Plugin::info($themeName, true);
		$this->set(compact('theme'));
		$this->Breadcrumb->push('/admin/system/themes');
		$this->Breadcrumb->push($theme['human_name'], '#');
		$this->Breadcrumb->push(__d('system', 'Details'), '#');
	}

/**
 * Renders theme's "screenshot.png"
 *
 * @param string $themeName
 * @return Image
 */
	public function screenshot($themeName) {
		$info = Plugin::info($themeName);
		$this->response->file("{$info['path']}/webroot/screenshot.png");
		return $this->response;
	}

/**
 * Handles theme's specifics settings.
 *
 * @return void
 */
	public function settings($themeName) {
		$theme = Plugin::info($themeName, true);
		$arrayContext = [
			'schema' => [],
			'defaults' => [],
			'errors' => [],
		];

		if (!$theme['hasSettings'] || !$theme['isTheme']) {
			throw new NotFoundException(__d('system', 'The requested page was not found.'));
		}

		if (!empty($this->request->data)) {
			$this->loadModel('System.Plugins');
			$themeEntity = $this->Plugins->get($themeName);
			$themeEntity->set('settings', $this->request->data);

			if ($this->Plugins->save($themeEntity)) {
				$this->alert(__d('system', 'Theme settings saved!'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->alert(__d('system', 'Theme settings could not be saved'), 'danger');
				$errors = $themeEntity->errors();

				if (!empty($errors)) {
					foreach ($errors as $field => $message) {
						$arrayContext['errors'][$field] = $message;
					}
				}
			}
		} else {
			$this->request->data = $theme['settings'];
		}

		$this->set(compact('arrayContext', 'theme'));
		$this->Breadcrumb->push('/admin/system/themes');
		$this->Breadcrumb->push(__d('system', 'Settings for {0} theme', $theme['name']), '#');
	}

}
