<?php
/**
 * Permissions Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.User.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class PermissionsController extends UserAppController {
    public $name = 'Permissions';
    public $uses = array('User.User');
    public $helpers = array('User.Tree');

    public function admin_index() {
        $this->Layout['stylesheets']['all'][] = '/user/css/treeview.css';
        $this->Layout['javascripts']['file'][] = '/user/js/jquery.cookie.js';
        $this->Layout['javascripts']['file'][] = '/user/js/treeview.js';
        $this->Layout['javascripts']['file'][] = '/user/js/acos.js';
        $this->Layout['javascripts']['embed'][] = '$(document).ready(function() { $("#acos").treeview({collapsed: true}); });';
        $results = $this->Acl->Aco->find('all',
            array(
                'order' => array('lft' => 'ASC'),
                'recursive' => -1,
                'fields' => array('alias', 'id', 'lft', 'rght')
            )
        );

        $this->set('results', $results);
        $this->setCrumb('/admin/user/');
        $this->setCrumb( array(__t('User Permissions'), ''));
        $this->title(__t('User Permissions'));
    }

    public function admin_edit($acoId) {
        $acoPath = $this->Acl->Aco->getPath($acoId);

        if (!$acoPath) {
            return;
        }

        $aros = array();

        $this->loadModel('Permission');

        foreach ($this->User->Role->find('all') as $role) {
            $hasAny = array(
                'aco_id'  => $acoId,
                'aro_id'  => $role['Role']['id'],
                '_create' => 1,
                '_read'   => 1,
                '_update' => 1,
                '_delete' => 1
            );
            $aros[$role['Role']['name']] = array(
                'id' => $role['Role']['id'],
                'allowed' => (int)$this->Permission->hasAny($hasAny)
            );
        }

        $this->set('acoPath', $acoPath);
        $this->set('aros', $aros);
    }

    public function admin_toggle($acoId, $aroId) {
        if ($aroId != 1) {
            $this->loadModel('Permission');

            $conditions = array(
                'Permission.aco_id' => $acoId,
                'Permission.aro_id' => $aroId,
            );

            if ($this->Permission->hasAny($conditions)) {
                $data = $this->Permission->find('first', array('conditions' => $conditions));

               if ($data['Permission']['_create'] == 1 &&
                    $data['Permission']['_read'] == 1 &&
                    $data['Permission']['_update'] == 1 &&
                    $data['Permission']['_delete'] == 1) {
                    // from 1 to 0
                    $data['Permission']['_create'] = 0;
                    $data['Permission']['_read'] = 0;
                    $data['Permission']['_update'] = 0;
                    $data['Permission']['_delete'] = 0;
                    $allowed = 0;
                } else {
                    // from 0 to 1
                    $data['Permission']['_create'] = 1;
                    $data['Permission']['_read'] = 1;
                    $data['Permission']['_update'] = 1;
                    $data['Permission']['_delete'] = 1;
                    $allowed = 1;
                }
            } else {
                // create - CRUD with 1
                $data['Permission']['aco_id'] = $acoId;
                $data['Permission']['aro_id'] = $aroId;
                $data['Permission']['_create'] = 1;
                $data['Permission']['_read'] = 1;
                $data['Permission']['_update'] = 1;
                $data['Permission']['_delete'] = 1;
                $allowed = 1;
            }

            $this->Permission->save($data);
            $this->set('allowed', $allowed);
        } else {
            $this->set('allowed', 1);
        }
    }
}