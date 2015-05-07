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
namespace System\Controller;

use QuickApps\Controller\Controller;
use QuickApps\Core\Plugin;

/**
 * Main controller for System plugin.
 *
 */
class AppController extends Controller
{

    /**
     * Look for plugin/themes awaiting for installation and sets a flash message
     * with instructions about how to proceed.
     *
     * @param string $type Possible values `plugin` (default) or `theme`, defaults
     *  to "plugin"
     * @return void
     */
    protected function _awaitingPlugins($type = 'plugin')
    {
        $type = !in_array($type, ['plugin', 'theme']) ? 'plugin' : $type;
        $ignoreThemes = $type === 'plugin';
        $plugins = Plugin::scan($ignoreThemes);

        foreach ($plugins as $name => $path) {
            if (Plugin::exists($name) ||
                ($type == 'theme' && !str_ends_with($name, 'Theme'))
            ) {
                unset($plugins[$name]);
            }
        }

        if (!empty($plugins)) {
            $this->Flash->set(__d('system', 'Some {type, select, plugin{plugins} other{themes}} are awaiting for installation', compact('type')), [
                'element' => 'System.stashed_plugins',
                'params' => compact('plugins'),
            ]);
        }
    }
}
