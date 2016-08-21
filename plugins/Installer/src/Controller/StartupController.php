<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Installer\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Filesystem\Folder;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use CMS\Core\Plugin;
use Installer\Utility\DatabaseInstaller;
use Installer\Utility\ServerTest;

/**
 * Controller for handling new QuickAppsCMS installations.
 *
 * This controller starts the installation process for a new QuickAppsCMS setup.
 *
 * @property \User\Model\Table\UsersTable $Users
 */
class StartupController extends Controller
{

    /**
     * {@inheritDoc}
     *
     * @var string
     */
    public $components = ['Flash'];

    /**
     * {@inheritDoc}
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        if (is_readable(ROOT . '/config/settings.php')) {
            $this->redirect('/');
        }

        $this->_prepareLayout();
        $this->viewBuilder()
            ->className('CMS\View\View')
            ->theme(false)
            ->layout('Installer.startup')
            ->helpers(['Menu.Menu']);

        if (!empty($this->request->query['locale']) && !in_array($this->request->params['action'], ['language', 'index'])) {
            I18n::locale($this->request->query['locale']);
            $this->request->session()->write('installation.language', I18n::locale());
        } elseif ($this->request->session()->read('installation.language')) {
            I18n::locale($this->request->session()->read('installation.language'));
        }

        Router::addUrlFilter(function ($params, $request) {
            if (!in_array($request->params['action'], ['language', 'index'])) {
                $params['locale'] = I18n::locale();
            }

            return $params;
        });
    }

    /**
     * Main action.
     *
     * We redirect to first step of the installation process: `language`.
     *
     * @return void
     */
    public function index()
    {
        $this->redirect([
            'plugin' => 'Installer',
            'controller' => 'startup',
            'action' => 'language'
        ]);
    }

    /**
     * First step of the installation process.
     *
     * User must select the language they want to use for the installation process.
     *
     * @return void
     */
    public function language()
    {
        $languages = [
            'en_US' => [
                'url' => '/installer/startup/requirements?locale=en_US',
                'welcome' => 'Welcome to QuickAppsCMS',
                'action' => 'Click here to install in English'
            ]
        ];

        $Folder = new Folder(Plugin::classPath('Installer') . 'Locale');
        foreach ($Folder->read(false, true, true)[0] as $path) {
            $code = basename($path);
            $file = $path . '/installer.po';

            if (is_readable($file)) {
                I18n::locale($code); // trick for __d()
                $languages[$code] = [
                    'url' => "/installer/startup/requirements?locale={$code}",
                    'welcome' => __d('installer', 'Welcome to QuickAppsCMS'),
                    'action' => __d('installer', 'Click here to install in English')
                ];
            }
        }

        I18n::locale('en_US');
        $this->title('Welcome to QuickAppsCMS');
        $this->set('languages', $languages);
        $this->_step();
    }

    /**
     * Second step of the installation process.
     *
     * We check server requirements here.
     *
     * @return void
     */
    public function requirements()
    {
        if (!$this->_step('language')) {
            $this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'language']);
        }

        $tests = $this->_getTester();
        $errors = $tests->errors();

        if (empty($errors)) {
            $this->_step();
        }

        $this->title(__d('installer', 'Server Requirements'));
        $this->set('errors', $errors);
    }

    /**
     * Third step of the installation process.
     *
     * License agreement.
     *
     * @return void
     */
    public function license()
    {
        if (!$this->_step('requirements')) {
            $this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'requirements']);
        }

        $this->title(__d('installer', 'License Agreement'));
        $this->_step();
    }

    /**
     * Fourth step of the installation process.
     *
     * User must introduce database connection information.
     *
     * @return void
     */
    public function database()
    {
        if (!$this->_step('license')) {
            $this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'license']);
        }

        if (!empty($this->request->data)) {
            $dbInstaller = new DatabaseInstaller();
            if ($dbInstaller->install($this->request->data())) {
                $this->_step();
                $this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'account']);
            } else {
                $errors = '';
                foreach ($dbInstaller->errors() as $error) {
                    $errors .= "\t<li>{$error}</li>\n";
                }
                $this->Flash->danger("<ul>\n{$errors}</ul>\n");
            }
        }

        $this->title(__d('installer', 'Database Configuration'));
    }

    /**
     * Fifth step of the installation process.
     *
     * Create a new administrator user account.
     *
     * @return void
     */
    public function account()
    {
        if (!$this->_step('database')) {
            $this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'database']);
        }

        $this->loadModel('User.Users');
        $user = $this->Users->newEntity();

        if ($this->request->data()) {
            $data = $this->request->data;
            $data['roles'] = ['_ids' => [1]];
            $user = $this->Users->newEntity($data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__d('installer', 'Account created you can now login!'));
                $this->_step();
                $this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'finish']);
            } else {
                $this->Flash->danger(__d('installer', 'Account could not be created, please check your information.'));
            }
        }

        $this->title(__d('installer', 'Create New Account'));
        $this->set('user', $user);
    }

    /**
     * Last step of the installation process.
     *
     * Here we say "thanks" and redirect to site's frontend or backend.
     *
     * @return void
     */
    public function finish()
    {
        if ($this->request->data()) {
            if (rename(ROOT . '/config/settings.php.tmp', ROOT . '/config/settings.php')) {
                snapshot();
                $this->request->session()->delete('Startup');

                if (!empty($this->request->data['home'])) {
                    $this->redirect('/');
                } else {
                    $this->redirect('/admin');
                }
            } else {
                $this->Flash->danger(__d('installer', 'Unable to continue, check write permission for the "/config" directory.'));
            }
        }

        $this->title(__d('installer', 'Finish Installation'));
    }

    // @codingStandardsIgnoreStart
    /**
     * Shortcut for Controller::set('title_for_layout', ...)
     *
     * @param string $titleForLayout Page's title
     * @return void
     */
    protected function title($titleForLayout)
    {
        $this->set('title_for_layout', $titleForLayout);
    }
    // @codingStandardsIgnoreEnd

    // @codingStandardsIgnoreStart
    /**
     * Shortcut for Controller::set('description_for_layout', ...)
     *
     * @param string $descriptionForLayout Page's description
     * @return void
     */
    protected function description($descriptionForLayout)
    {
        $this->set('description_for_layout', $descriptionForLayout);
    }
    // @codingStandardsIgnoreEnd

    /**
     * Gets an instance of ServerTest class.
     *
     * @return \Installer\Utility\ServerTest
     */
    protected function _getTester()
    {
        $tests = new ServerTest();
        $tests
            ->add('php', (bool)version_compare(PHP_VERSION, '5.4.19', '>='), __d('installer', 'Your php version is not supported. check that your version is 5.4.19 or newer.'))
            ->add('mbstring', (bool)extension_loaded('mbstring'), __d('installer', 'Missing extension: {0}', 'mbstring'))
            ->add('mcrypt', (bool)extension_loaded('mcrypt'), __d('installer', 'Missing extension: {0}', 'mcrypt'))
            ->add('intl', (bool)extension_loaded('intl'), __d('installer', 'Missing extension: {0}', 'intl'))
            ->add('fileinfo', (bool)extension_loaded('fileinfo'), __d('installer', 'Missing extension: {0}', 'fileinfo'))
            ->add('tmp_writable', is_writable(TMP), __d('installer', '"{0}" folder is not writable.', 'tmp/'))
            ->add('cache_writable', is_writable(TMP . 'cache'), __d('installer', '"{0}" folder is not writable.', 'tmp/cache/'))
            ->add('models_writable', is_writable(TMP . 'cache/models'), __d('installer', '"{0}" folder is not writable.', 'tmp/cache/models/'))
            ->add('persistent_writable', is_writable(TMP . 'cache/persistent'), __d('installer', '"{0}" folder is not writable.', 'tmp/cache/persistent/'))
            ->add('config_writable', is_writable(ROOT . '/config'), __d('installer', '"{0}" folder is not writable.', 'config/'))
            ->add('pdo', [
                'rule' => function () {
                    return
                        extension_loaded('pdo') &&
                        defined('PDO::ATTR_DEFAULT_FETCH_MODE');
                },
                'message' => __d('installer', 'Missing extension: {0}', 'PDO'),
            ])
            ->add('no_safe_mode', [
                'rule' => function () {
                    return
                        ini_get('safe_mode') === false ||
                        ini_get('safe_mode') === '' ||
                        strtolower(ini_get('safe_mode')) == 'off';
                },
                'message' => __d('installer', 'Your server has SafeMode on, please turn it off before continuing.'),
            ]);

        return $tests;
    }

    /**
     * Check if the given step name was completed. Or marks current step as
     * completed.
     *
     * If $check is set to NULL, it marks current step (controller's action name)
     * as completed. If $check is set to a string, it checks if that step was
     * completed before.
     *
     * This allows steps to control user navigation, so users can not pass to the
     * next step without completing all previous steps.
     *
     * @param null|string $check Name of the step to check, or false to mark as
     *  completed current step
     * @return bool
     */
    protected function _step($check = null)
    {
        $_steps = (array)$this->request->session()->read('Startup._steps');
        if ($check === null) {
            $_steps[] = $this->request->params['action'];
            $_steps = array_unique($_steps);
            $this->request->session()->write('Startup._steps', $_steps);
        } elseif (is_string($check)) {
            return in_array($check, $_steps);
        }

        return false;
    }

    /**
     * Sets some view-variables used across all steps.
     *
     * @return void
     */
    protected function _prepareLayout()
    {
        $menu = [
            __d('installer', 'Welcome') => [
                'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'language'],
                'active' => ($this->request->action === 'language')
            ],
            __d('installer', 'System Requirements') => [
                'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'requirements'],
                'active' => ($this->request->action === 'requirements')
            ],
            __d('installer', 'License Agreement') => [
                'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'license'],
                'active' => ($this->request->action === 'license')
            ],
            __d('installer', 'Database Setup') => [
                'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'database'],
                'active' => ($this->request->action === 'database')
            ],
            __d('installer', 'Your Account') => [
                'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'account'],
                'active' => ($this->request->action === 'account')
            ],
            __d('installer', 'Finish') => [
                'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'finish'],
                'active' => ($this->request->action === 'finish')
            ],
        ];

        $this->set('menu', $menu);
    }
}
