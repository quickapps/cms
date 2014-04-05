<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

/**
 * Gets a list of information for every registered field in the system.
 *
 * **Example:**
 *
 * Using `listFields(true)` may produce something like:
 *
 *     array(
 *         [0] => array(
 *             'name' => 'Textarea',
 *             'description' => 'Allows to store long texts',
 *             'hidden' => false
 *         ),
 *         [1] => array(
 *             'name' => 'Secret Field',
 *             'description' => 'This field should only be used internally by plugins',
 *             'hidden' => true
 *         )
 *     )
 *
 * Some fields may register themselves as hidden when they are intended to be used
 * exclusively by plugins. So users can not `attach` them to entities using Field UI.
 *
 * @param boolean $includeHidden Set to true t include fields marked as hidden
 * @return array List of fields
 */
function listFields($includeHidden = false) {
	$fields = [];
	$EventManager = \Cake\Event\EventManager::instance();

	foreach (\Cake\Core\Configure::read('QuickApps._snapshot.fields') as $plugin => $fields) {
		foreach ($fields as $field) {
			$event = new \Cake\Event\Event("{$field['className']}.Instance.info", null, null);
			$EventManager->dispatch($event);
			$response = (array)$event->result;
			$response += ['name' => null, 'description' => null, 'hidden' => false];

			if ($response['name'] && $response['description']) {
				if (
					!$response['hidden'] ||
					($response['hidden'] && $includeHidden)
				) {
					$fields[] = $response;
				}
			}
		}
	}

	return $fields;
}