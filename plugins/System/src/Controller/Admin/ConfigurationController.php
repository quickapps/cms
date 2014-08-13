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

use System\Controller\AppController;
use Locale\Utility\LocaleToolbox;

/**
 * Configuration Controller.
 *
 * For handling site's variables.
 */
class ConfigurationController extends AppController {

/**
 * Main action.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('System.Options');
		$variables = $this->Options
			->find()
			->all();
		$arrayContext = [
			'schema' => [],
			'defaults' => [],
			'errors' => [],
		];

		foreach ($variables as $var) {
			$arrayContext['schema'][$var->name] = ['type' => 'string'];
			$arrayContext['defaults'][$var->name] = $var->value;
		}

		$this->set('arrayContext', $arrayContext);
		$this->set('languages', LocaleToolbox::languagesList());
		$this->set('variables', $variables);
	}

}
