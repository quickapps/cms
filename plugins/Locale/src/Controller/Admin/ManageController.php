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
namespace Locale\Controller\Admin;

use Locale\Controller\AppController;
use Locale\Utility\LocaleToolbox;

/**
 * Locale manager controller.
 *
 * Provides full CRUD for languages.
 */
class ManageController extends AppController
{

    /**
     * Shows a list of languages.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Locale.Languages');
        $languages = $this->Languages
            ->find()
            ->order(['ordering' => 'ASC'])
            ->all();

        $this->set('languages', $languages);
        $this->Breadcrumb->push('/admin/locale');
    }

    /**
     * Registers a new language in the system.
     *
     * @return void
     */
    public function add()
    {
        $this->loadModel('Locale.Languages');
        $language = $this->Languages->newEntity();
        $languages = LocaleToolbox::languagesList(true);
        $icons = LocaleToolbox::flagsList();

        if (!empty($this->request->data['code'])) {
            $info = LocaleToolbox::info($this->request->data['code']);
            $language = $this->Languages->patchEntity($language, [
                'code' => $this->request->data['code'],
                'name' => $info['language'],
                'direction' => $info['direction'],
                'status' => (isset($this->request->data['status']) ? $this->request->data['status'] : false),
                'icon' => (!empty($this->request->data['icon']) ? $this->request->data['icon'] : null)
            ]);

            if ($this->Languages->save($language)) {
                $this->Flash->success(__d('locale', 'Language was successfully registered!'));
                $this->redirect(['plugin' => 'Locale', 'controller' => 'manage', 'action' => 'index']);
            } else {
                $this->Flash->danger(__d('locale', 'Language could not be registered, please check your information'));
            }
        }

        $this->set(compact('language', 'languages', 'icons'));
        $this->Breadcrumb
            ->push('/admin/locale')
            ->push(__d('locale', 'Add new language'), '');
    }

    /**
     * Edits language.
     *
     * @param int $id Language's ID
     * @return void
     */
    public function edit($id)
    {
        $this->loadModel('Locale.Languages');
        $language = $this->Languages->get($id);
        $languages = LocaleToolbox::languagesList(true);
        $icons = LocaleToolbox::flagsList();

        if ($this->request->data) {
            $language = $this->Languages->patchEntity($language, $this->request->data, [
                'fieldList' => [
                    'name',
                    'direction',
                    'icon',
                ]
            ]);

            if ($this->Languages->save($language)) {
                $this->Flash->success(__d('locale', 'Language was successfully saved!'));
                $this->redirect($this->referer());
            } else {
                $this->Flash->success(__d('locale', 'Language could not be saved, please check your information.'));
            }
        }

        $this->set(compact('language', 'languages', 'icons'));
        $this->Breadcrumb
            ->push('/admin/locale')
            ->push(__d('locale', 'Editing language'), '');
    }

    /**
     * Sets the given language as site's default language.
     *
     * @param int $id Language's ID
     * @return void Redirects to previous page
     */
    public function setDefault($id)
    {
        $this->loadModel('Locale.Languages');
        $this->loadModel('System.Options');
        $language = $this->Languages->get($id);

        if ($language->status) {
            if ($this->Options->update('default_language', $language->code)) {
                $this->Flash->success(__d('locale', 'Default language changed!'));
            } else {
                $this->Flash->danger(__d('locale', 'Default language could not be changed.'));
            }
        } else {
            $this->Flash->danger(__d('locale', 'You cannot set as default a disabled language.'));
        }

        $this->redirect($this->referer());
    }

    /**
     * Moves language up or down.
     *
     * @param int $id Language's ID
     * @param string $direction Direction, 'up' or 'down'
     * @return void Redirects to previous page
     */
    public function move($id, $direction)
    {
        $this->loadModel('Locale.Languages');
        $language = $this->Languages->get($id);
        $unordered = [];
        $direction = !in_array($direction, ['up', 'down']) ? 'up' : $direction;
        $position = false;
        $list = $this->Languages->find()
            ->select(['id', 'ordering'])
            ->order(['ordering' => 'ASC'])
            ->all();

        foreach ($list as $k => $l) {
            if ($l->id === $language->id) {
                $position = $k;
            }

            $unordered[] = $l;
        }

        if ($position !== false) {
            $ordered = array_move($unordered, $position, $direction);
            $before = md5(serialize($unordered));
            $after = md5(serialize($ordered));

            if ($before != $after) {
                foreach ($ordered as $k => $l) {
                    $l->set('ordering', $k);
                    $this->Languages->save($l, ['validate' => false]);
                }
            }
        }

        $this->redirect($this->referer());
    }

    /**
     * Enables the given language.
     *
     * @param int $id Language's ID
     * @return void Redirects to previous page
     */
    public function enable($id)
    {
        $this->loadModel('Locale.Languages');
        $language = $this->Languages->get($id);
        $language->set('status', true);

        if ($this->Languages->save($language)) {
            $this->Flash->success(__d('locale', 'Language successfully enabled!'));
        } else {
            $this->Flash->danger(__d('locale', 'Language could not be enabled, please try again.'));
        }

        $this->redirect($this->referer());
    }

    /**
     * Disables the given language.
     *
     * @param int $id Language's ID
     * @return void Redirects to previous page
     */
    public function disable($id)
    {
        $this->loadModel('Locale.Languages');
        $language = $this->Languages->get($id);

        if (!in_array($language->code, [CORE_LOCALE, option('default_language')])) {
            $language->set('status', false);
            if ($this->Languages->save($language)) {
                $this->Flash->success(__d('locale', 'Language successfully disabled!'));
            } else {
                $this->Flash->danger(__d('locale', 'Language could not be disabled, please try again.'));
            }
        } else {
            $this->Flash->danger(__d('locale', 'You cannot disable this language as it still in use.'));
        }

        $this->redirect($this->referer());
    }

    /**
     * Unregisters the given language.
     *
     * @param int $id Language's ID
     * @return void Redirects to previous page
     */
    public function delete($id)
    {
        $this->loadModel('Locale.Languages');
        $language = $this->Languages->get($id);

        if (!in_array($language->code, [CORE_LOCALE, option('default_language')])) {
            if ($this->Languages->delete($language)) {
                $this->Flash->success(__d('locale', 'Language successfully removed!'));
            } else {
                $this->Flash->danger(__d('locale', 'Language could not be removed, please try again.'));
            }
        } else {
            $this->Flash->danger(__d('locale', 'You cannot remove this language as it still in use.'));
        }

        $this->redirect($this->referer());
    }
}
