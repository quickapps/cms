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
namespace User\Controller\Admin;

use Cake\Filesystem\File;
use User\Controller\AppController;
use User\Utility\AcoManager;

/**
 * Permissions manager controller.
 *
 * Provides full CRUD for permissions.
 */
class PermissionsController extends AppController
{

    /**
     * Shows tree list of ACOS grouped by plugin.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('User.Acos');
        $tree = $this->Acos
            ->find('threaded')
            ->order(['lft' => 'ASC'])
            ->all();

        $this->set(compact('tree'));
        $this->Breadcrumb
            ->push('/admin/user/manage')
            ->push(__d('user', 'Permissions'), ['plugin' => 'User', 'controller' => 'permissions', 'action' => 'index']);
    }

    /**
     * Shows the permissions table for the given ACO.
     *
     * @param int $acoId ACO's ID
     * @return void
     */
    public function aco($acoId)
    {
        $this->loadModel('User.Acos');
        $aco = $this->Acos->get($acoId, ['contain' => ['Roles']]);
        $path = $this->Acos->find('path', ['for' => $acoId])->extract('alias')->toArray();

        if (!empty($this->request->data['roles'])) {
            $aco = $this->Acos->patchEntity($aco, $this->request->data);
            $save = $this->Acos->save($aco);

            if (!$this->request->isAjax()) {
                if ($save) {
                    $this->Flash->success(__d('user', 'Permissions were successfully saved!'));
                } else {
                    $this->Flash->danger(__d('user', 'Permissions could not be saved'));
                }
            }
        }

        $roles = $this->Acos->Roles->find('list');
        $this->set(compact('aco', 'roles', 'path'));
    }

    /**
     * Analyzes each plugin and adds any missing ACO path to the tree. It won't
     * remove any invalid ACO unless 'sync' GET parameter is present in URL.
     *
     * @return void Redirects to previous page
     */
    public function update()
    {
        $sync = !empty($this->request->query['sync']) ? true : false;
        if (AcoManager::buildAcos(null, $sync)) {
            $this->Flash->success(__d('user', 'Permissions tree was successfully updated!'));
        } else {
            $this->Flash->danger(__d('user', 'Some errors occur while updating permissions tree.'));
        }

        $this->redirect($this->referer());
    }

    /**
     * Exports all permissions as a JSON file.
     *
     * @return \Cake\Network\Response Forces JSON download
     */
    public function export()
    {
        $this->loadModel('User.Acos');
        $out = [];
        $permissions = $this->Acos->Permissions
            ->find()
            ->contain(['Acos', 'Roles'])
            ->all();

        foreach ($permissions as $permission) {
            if (!isset($out[$permission->role->slug])) {
                $out[$permission->role->slug] = [];
            }
            $out[$permission->role->slug][] = implode(
                '/',
                $this->Acos
                ->find('path', ['for' => $permission->aco->id])
                ->extract('alias')
                ->toArray()
            );
        }

        $this->response->body(json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->response->type('json');
        $this->response->download('permissions_' . date('Y-m-d@H:i:s-\U\TC', time()) . '.json');
        return $this->response;
    }

    /**
     * Imports the given permissions given as a JSON file.
     *
     * @return void Redirects to previous page
     */
    public function import()
    {
        if (!empty($this->request->data['json'])) {
            $this->loadModel('User.Acos');
            $json = $this->request->data['json'];
            $dst = TMP . $json['name'];
            if (file_exists($dst)) {
                $file = new File($dst);
                $file->delete();
            }

            if (!move_uploaded_file($json['tmp_name'], $dst)) {
                $this->Flash->danger(__d('user', 'File could not be uploaded, please check write permissions on /tmp directory.'));
            } else {
                $file = new File($dst);
                $info = json_decode($file->read(), true);
                $added = [];
                $error = false;

                if (is_array($info) && !empty($info)) {
                    foreach ($info as $role => $paths) {
                        if (!is_string($role)) {
                            $error = true;
                            $this->Flash->danger(__d('user', 'Given file seems to be corrupt.'));
                            break;
                        }
                        $role = $this->Acos->Roles
                            ->find()
                            ->where(['slug' => $role])
                            ->limit(1)
                            ->first();

                        if (!$role) {
                            continue;
                        }

                        if (is_array($paths)) {
                            foreach ($paths as $path) {
                                $nodes = $this->Acos->node($path);

                                if ($nodes) {
                                    $leaf = $nodes->first();
                                    $exists = $this->Acos->Permissions
                                        ->exists([
                                            'aco_id' => $leaf->id,
                                            'role_id' => $role->id
                                        ]);
                                    if (!$exists) {
                                        $newPermission = $this->Acos->Permissions->newEntity([
                                            'aco_id' => $leaf->id,
                                            'role_id' => $role->id
                                        ]);
                                        if ($this->Acos->Permissions->save($newPermission)) {
                                            $added[] = "<strong>{$role->name}</strong>: {$path}";
                                        }
                                    }
                                }
                            }
                        } else {
                            $error = true;
                            $this->Flash->danger(__d('user', 'Given file seems to be corrupt.'));
                            break;
                        }
                    }
                } else {
                    $error = true;
                    $this->Flash->danger(__d('user', 'Invalid file given.'));
                }
            }

            if (!$error) {
                if (!empty($added)) {
                    $imported = '<br />' . implode('<br />', $added);
                    $this->Flash->success(__d('user', 'The following entries were imported: {0}', $imported));
                } else {
                    $this->Flash->success(__d('user', 'Success, but nothing was imported'));
                }
            }
        }

        $this->redirect($this->referer());
    }
}
