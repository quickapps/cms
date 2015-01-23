<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace System\Controller\Admin;

use Cake\ORM\Entity;
use Cake\Validation\Validator;
use Locale\Utility\LocaleToolbox;
use QuickApps\Core\Plugin;
use System\Controller\AppController;

/**
 * Configuration Controller.
 *
 * For handling site's variables.
 */
class ConfigurationController extends AppController
{

    /**
     * Main action.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('System.Options');
        $languages = LocaleToolbox::languagesList();
        $arrayContext = [
            'schema' => [
                'site_title' => 'string',
                'site_slogan' => 'string',
                'site_description' => 'string',
                'site_email' => 'string',
                'site_nodes_home' => 'integer',

                'site_maintenance' => 'boolean',
                'site_maintenance_ip' => 'string',
                'site_maintenance_message' => 'string',

                'default_language' => 'string',
                'url_locale_prefix' => 'string',
            ],
            'defaults' => [],
            'errors' => [],
        ];
        $variables = $this->Options
            ->find()
            ->where(['name IN' => array_keys($arrayContext['schema'])])
            ->all();

        foreach ($variables as $var) {
            $arrayContext['defaults'][$var->name] = $var->value;
        }

        if ($this->request->data) {
            $validator = $this->_mockValidator();
            $errors = $validator->errors($this->request->data());

            if (empty($errors)) {
                $mockEntity = new Entity($this->request->data());
                foreach ($this->request->data as $k => $v) {
                    $this->Options->update($k, $v, null, false);
                }
                snapshot();
                $this->Flash->success(__d('system', 'Configuration successfully saved!'));
                $this->redirect($this->referer());
            } else {
                $arrayContext['errors'] = $errors;
                $this->Flash->danger(__d('system', 'Configuration could not be saved, please check your information.'));
            }
        }

        $pluginSettings = Plugin::collection(true)->match(['hasSettings' => true]);
        $this->set(compact('arrayContext', 'languages', 'variables', 'pluginSettings'));
        $this->Breadcrumb->push('/admin/system/configuration');
    }

    /**
     * Created a mock validator object used when validating options
     *
     * @return \Cake\Validation\Validator
     */
    protected function _mockValidator()
    {
        $validator = new Validator();
        return $validator
            ->requirePresence('site_title')
            ->add('site_title', 'length', [
                'rule' => ['minLength', 3],
                'message' => __d('system', "Site's name must be at least 3 characters long."),
            ])
            ->requirePresence('site_email')
            ->add('site_email', 'validEmail', [
                'rule' => 'email',
                'message' => __d('system', 'Invalid e-Mail.'),
            ]);
    }
}
